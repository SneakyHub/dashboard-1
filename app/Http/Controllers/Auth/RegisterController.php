<?php

namespace App\Http\Controllers\Auth;

use App\Classes\PhoenixPanelClient;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\ReferralNotification;
use App\Providers\RouteServiceProvider;
use App\Rules\TurnstileRule;
use App\Settings\GeneralSettings;
use App\Settings\PhoenixPanelSettings;
use App\Settings\ReferralSettings;
use App\Settings\UserSettings;
use App\Settings\WebsiteSettings;
use App\Traits\ProtectcordTrait;
use App\Traits\Referral;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers, Referral, ProtectcordTrait;

    /**
     * @var PhoenixPanelClient
     */
    private $phoenixpanel;

    /**
     * @var string
     */
    private $credits_display_name;

    /**
     * @var bool
     */
    private $website_show_tos;

    /**
     * @var bool
     */
    private $register_ip_check;

    /**
     * @var int
     */
    private $initial_credits;

    /**
     * @var int
     */
    private $initial_server_limit;

    /**
     * @var string
     */
    private $referral_mode;

    /**
     * @var int
     */
    private $referral_reward;

    /**
     * @var string|null
     */
    private $recaptcha_version;

    /**
     * @var bool
     */
    private $user_creation_enabled;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @param PhoenixPanelSettings $phoenix_settings
     * @param GeneralSettings $general_settings
     * @param WebsiteSettings $website_settings
     * @param UserSettings $user_settings
     * @param ReferralSettings $referral_settings
     */
    public function __construct(
        PhoenixPanelSettings $phoenix_settings,
        GeneralSettings $general_settings,
        WebsiteSettings $website_settings,
        UserSettings $user_settings,
        ReferralSettings $referral_settings
    ) {
        $this->middleware('guest');
        
        $this->phoenixpanel = new PhoenixPanelClient($phoenix_settings);
        $this->credits_display_name = $general_settings->credits_display_name;
        $this->recaptcha_version = $general_settings->recaptcha_version;
        $this->website_show_tos = $website_settings->show_tos;
        $this->register_ip_check = $user_settings->register_ip_check;
        $this->initial_credits = $user_settings->initial_credits;
        $this->initial_server_limit = $user_settings->initial_server_limit;
        $this->user_creation_enabled = $user_settings->creation_enabled;
        $this->referral_mode = $referral_settings->mode;
        $this->referral_reward = $referral_settings->reward;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Check if user creation is enabled
        if (!$this->user_creation_enabled) {
            return redirect()->back()->withErrors([
                'registration' => __('User registration is currently disabled by the administrator.')
            ]);
        }

        // Check IP with Protectcord first
        $userIp = $request->ip();
        $this->checkIpWithProtectcord($userIp, 'register');

        // Validate the registration request
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect($this->redirectPath());
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validationRules = [
            'name' => [
                'required',
                'string',
                'min:4',
                'max:30',
                'alpha_num',
                'unique:users,name',
                'regex:/^[a-zA-Z0-9_]+$/'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:64',
                'unique:users,email'
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed'
            ],
        ];

        $customMessages = [
            'name.required' => __('Username is required.'),
            'name.min' => __('Username must be at least 4 characters.'),
            'name.max' => __('Username cannot exceed 30 characters.'),
            'name.alpha_num' => __('Username can only contain letters and numbers.'),
            'name.unique' => __('This username is already taken.'),
            'name.regex' => __('Username can only contain letters, numbers, and underscores.'),
            'email.required' => __('Email address is required.'),
            'email.email' => __('Please enter a valid email address.'),
            'email.unique' => __('This email address is already registered.'),
            'email.max' => __('Email address cannot exceed 64 characters.'),
            'password.required' => __('Password is required.'),
            'password.min' => __('Password must be at least 8 characters.'),
            'password.confirmed' => __('Password confirmation does not match.'),
        ];

        // Add reCAPTCHA validation if enabled
        if ($this->recaptcha_version) {
            switch ($this->recaptcha_version) {
                case "v2":
                    $validationRules['g-recaptcha-response'] = ['required', 'recaptcha'];
                    $customMessages['g-recaptcha-response.required'] = __('Please complete the reCAPTCHA verification.');
                    break;
                case "v3":
                    $validationRules['g-recaptcha-response'] = ['required', 'recaptchav3:recaptchathree,0.5'];
                    $customMessages['g-recaptcha-response.required'] = __('Please complete the reCAPTCHA verification.');
                    break;
            }
        }

        // Add Cloudflare Turnstile validation if enabled
        if (env('TURNSTILE_SITE_KEY') && env('TURNSTILE_SECRET_KEY')) {
            $validationRules['cf-turnstile-response'] = ['required', new TurnstileRule()];
            $customMessages['cf-turnstile-response.required'] = __('Please complete the security verification.');
        }

        // Add Terms of Service validation if enabled
        if ($this->website_show_tos) {
            $validationRules['terms'] = ['required', 'accepted'];
            $customMessages['terms.required'] = __('You must agree to the Terms of Service.');
            $customMessages['terms.accepted'] = __('You must agree to the Terms of Service.');
        }

        // Add referral code validation if provided
        if (isset($data['referral_code']) && !empty($data['referral_code'])) {
            $validationRules['referral_code'] = ['string', 'max:20', 'exists:users,referral_code'];
            $customMessages['referral_code.exists'] = __('Invalid referral code.');
            $customMessages['referral_code.max'] = __('Referral code cannot exceed 20 characters.');
        }

        // Add IP validation if enabled
        if ($this->register_ip_check) {
            $data['ip'] = session()->get('ip') ?? request()->ip();
            if (User::where('ip', '=', request()->ip())->exists()) {
                session()->put('ip', request()->ip());
            }
            $validationRules['ip'] = ['unique:users,ip'];
            $customMessages['ip.unique'] = __('You have already created an account from this IP address! Please contact support if you think this is incorrect.');
        }

        return Validator::make($data, $validationRules, $customMessages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     * @throws ValidationException
     */
    protected function create(array $data)
    {
        try {
            // Create user in database first
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'credits' => $this->initial_credits,
                'server_limit' => $this->initial_server_limit,
                'password' => Hash::make($data['password']),
                'referral_code' => $this->createReferralCode(),
                'phoenixpanel_id' => Str::uuid(),
                'ip' => $this->register_ip_check ? request()->ip() : null,
                'last_seen' => now(),
            ]);

            // Assign user role
            $userRole = Role::findById(4); // user role
            if ($userRole) {
                $user->syncRoles($userRole);
            } else {
                Log::warning('User role (ID: 4) not found. User created without role assignment.', ['user_id' => $user->id]);
            }

            // Create user in PhoenixPanel
            $this->createPhoenixPanelUser($user, $data['password']);

            // Handle referral if provided
            if (!empty($data['referral_code'])) {
                $this->handleReferral($user, $data['referral_code']);
            }

            // Clean up activity logs for user creation/deletion
            $this->cleanupActivityLogs($user->id);

            Log::info('User registration successful', [
                'user_id' => $user->id,
                'username' => $user->name,
                'email' => $user->email,
                'has_referral' => !empty($data['referral_code']),
                'ip' => request()->ip()
            ]);

            return $user;

        } catch (\Exception $e) {
            // If user was created but something failed later, clean up
            if (isset($user) && $user->exists) {
                $user->delete();
            }
            
            Log::error('User registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => [
                    'name' => $data['name'] ?? null,
                    'email' => $data['email'] ?? null,
                    'ip' => request()->ip()
                ]
            ]);
            
            throw $e;
        }
    }

    /**
     * Create user in PhoenixPanel.
     *
     * @param User $user
     * @param string $password
     * @throws ValidationException
     */
    private function createPhoenixPanelUser(User $user, string $password): void
    {
        try {
            $response = $this->phoenixpanel->application->post('/application/users', [
                'external_id' => null,
                'username' => $user->name,
                'email' => $user->email,
                'first_name' => $user->name,
                'last_name' => $user->name,
                'password' => $password,
                'root_admin' => false,
                'language' => 'en',
            ]);

            if ($response->failed()) {
                $errorMessage = 'Unknown error';
                $errorDetails = [];

                if ($response->json() && isset($response->json()['errors'])) {
                    $errors = $response->json()['errors'];
                    if (is_array($errors) && !empty($errors)) {
                        $errorMessage = $errors[0]['detail'] ?? $errorMessage;
                        $errorDetails = $errors;
                    }
                }

                Log::error('PhoenixPanel user creation failed', [
                    'user_id' => $user->id,
                    'status' => $response->status(),
                    'error_message' => $errorMessage,
                    'error_details' => $errorDetails,
                    'response_body' => $response->body()
                ]);

                throw ValidationException::withMessages([
                    'phoenix_registration_error' => [__('Failed to create account on PhoenixPanel. Please contact Support! Error: :error', ['error' => $errorMessage])],
                ]);
            }

            $responseData = $response->json();
            if (!isset($responseData['attributes']['id'])) {
                Log::error('PhoenixPanel user creation: Missing user ID in response', [
                    'user_id' => $user->id,
                    'response' => $responseData
                ]);

                throw ValidationException::withMessages([
                    'phoenix_registration_error' => [__('Failed to create account on PhoenixPanel. Please contact Support!')],
                ]);
            }

            // Update user with PhoenixPanel ID
            $user->update([
                'phoenixpanel_id' => $responseData['attributes']['id'],
            ]);

            Log::info('PhoenixPanel user created successfully', [
                'user_id' => $user->id,
                'phoenixpanel_id' => $responseData['attributes']['id']
            ]);

        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('PhoenixPanel user creation exception', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw ValidationException::withMessages([
                'phoenix_registration_error' => [__('Failed to create account on PhoenixPanel. Please contact Support!')],
            ]);
        }
    }

    /**
     * Handle referral logic.
     *
     * @param User $user
     * @param string $referralCode
     */
    private function handleReferral(User $user, string $referralCode): void
    {
        try {
            $referrer = User::where('referral_code', $referralCode)->first();
            
            if (!$referrer) {
                Log::warning('Referral code not found', [
                    'code' => $referralCode,
                    'user_id' => $user->id
                ]);
                return;
            }

            // Insert into user_referrals table
            DB::table('user_referrals')->insert([
                'referral_id' => $referrer->id,
                'registered_user_id' => $user->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Handle referral rewards based on mode
            if ($this->referral_mode === 'sign-up' || $this->referral_mode === 'both') {
                // Give reward to referrer
                $referrer->increment('credits', $this->referral_reward);
                
                // Send notification to referrer
                $referrer->notify(new ReferralNotification($referrer->id, $user->id));

                // Log the referral activity
                activity()
                    ->performedOn($user)
                    ->causedBy($referrer)
                    ->log('gained ' . $this->referral_reward . ' ' . $this->credits_display_name . ' for sign-up-referral of ' . $user->name . ' (ID:' . $user->id . ')');

                Log::info('Referral reward processed', [
                    'referrer_id' => $referrer->id,
                    'new_user_id' => $user->id,
                    'reward_amount' => $this->referral_reward,
                    'referral_code' => $referralCode
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Referral processing failed', [
                'error' => $e->getMessage(),
                'referral_code' => $referralCode,
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString()
            ]);
            
            // Don't throw exception here as referral failure shouldn't block registration
        }
    }

    /**
     * Clean up activity logs for user creation/deletion.
     *
     * @param int $userId
     */
    private function cleanupActivityLogs(int $userId): void
    {
        try {
            DB::table('activity_log')
                ->where('subject_id', $userId)
                ->whereIn('description', ['created', 'deleted'])
                ->delete();
                
        } catch (\Exception $e) {
            Log::warning('Failed to cleanup activity logs', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * The user has been registered.
     *
     * @param Request $request
     * @param User $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        // Update last seen timestamp
        $user->update(['last_seen' => now()]);

        // Log successful registration
        Log::info('User successfully registered and logged in', [
            'user_id' => $user->id,
            'username' => $user->name,
            'ip' => $request->ip()
        ]);

        return redirect($this->redirectPath())->with('success', __('Registration successful! Welcome to SneakyHub.'));
    }

    /**
     * Create a unique referral code.
     *
     * @return string
     */
    private function createReferralCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (User::where('referral_code', $code)->exists());

        return $code;
    }
}