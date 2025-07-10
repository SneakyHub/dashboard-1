<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Rules\TurnstileRule;
use App\Settings\GeneralSettings;
use App\Traits\ProtectcordTrait;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers, ProtectcordTrait;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        $login = request()->input('email');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        request()->merge([$field => $login]);
        return $field;
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Settings\GeneralSettings  $general_settings
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request, GeneralSettings $general_settings)
    {
        // Check IP with Protectcord first
        $userIp = $request->ip();
        $this->checkIpWithProtectcord($userIp, 'login');

        // Validate the login request
        $this->validateLogin($request, $general_settings);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $user = Auth::user();
            $user->last_seen = now();
            $user->save();

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Settings\GeneralSettings  $general_settings
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request, GeneralSettings $general_settings)
    {
        $validationRules = [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ];

        $customMessages = [
            'email.required' => 'Email or username is required.',
            'name.required' => 'Email or username is required.',
            'password.required' => 'Password is required.',
        ];

        // Add reCAPTCHA validation if enabled
        if ($general_settings->recaptcha_version) {
            switch ($general_settings->recaptcha_version) {
                case "v2":
                    $validationRules['g-recaptcha-response'] = ['required', 'recaptcha'];
                    $customMessages['g-recaptcha-response.required'] = 'Please complete the reCAPTCHA verification.';
                    break;
                case "v3":
                    $validationRules['g-recaptcha-response'] = ['required', 'recaptchav3:recaptchathree,0.5'];
                    $customMessages['g-recaptcha-response.required'] = 'Please complete the reCAPTCHA verification.';
                    break;
            }
        }

        // Add Cloudflare Turnstile validation if enabled
        if (env('TURNSTILE_SITE_KEY') && env('TURNSTILE_SECRET_KEY')) {
            $validationRules['cf-turnstile-response'] = ['required', new TurnstileRule()];
            $customMessages['cf-turnstile-response.required'] = 'Please complete the security verification.';
        }

        $request->validate($validationRules, $customMessages);
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Update last seen timestamp
        $user->last_seen = now();
        $user->save();

        // You can add additional logic here if needed
        // For example, logging successful login attempts

        return redirect()->intended($this->redirectPath());
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }
}