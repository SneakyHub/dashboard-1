<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Settings\GeneralSettings;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Services\ProtectCordService;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait to
    | conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * @var ProtectCordService
     */
    private $protectCordService;

    /**
     * Create a new controller instance.
     *
     * @param ProtectCordService $protectCordService
     * @return void
     */
    public function __construct(ProtectCordService $protectCordService)
    {
        $this->middleware('guest')->except('logout');
        $this->protectCordService = $protectCordService;
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

    public function login(Request $request, GeneralSettings $general_settings)
    {
        // Perform ProtectCord IP check before performing other validations
        $ip = request()->ip();

        // Perform ProtectCord IP check before validations
        $ip = request()->ip();

        try {
            $protectCordResult = $this->protectCordService->checkIp($ip);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'protectcord_validation' => __('An error occurred while verifying your access. Please try again later.'),
            ]);
        }

        if ($protectCordResult['block']) {
            throw ValidationException::withMessages([
                'protectcord_validation' => __('Your access has been blocked due to a flagged IP address: ' . $protectCordResult['reasonText']),
            ]);
        }
        try {
            $protectCordResult = $this->protectCordService->checkIp($ip);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'protectcord_validation' => __('An error occurred while verifying your access. Please try again later.'),
            ]);
        }

        if ($protectCordResult['block']) {
            throw ValidationException::withMessages([
                'protectcord_validation' => __('Your access has been blocked due to a flagged IP address: ' . $protectCordResult['reasonText']),
            ]);
        }

        // Perform other validations
        $validationRules = [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ];

        if ($general_settings->recaptcha_version) {
            switch ($general_settings->recaptcha_version) {
                case "v2":
                    $validationRules['g-recaptcha-response'] = ['required', 'recaptcha'];
                    break;
                case "v3":
                    $validationRules['g-recaptcha-response'] = ['required', 'recaptchav3:recaptchathree,0.5'];
                    break;
            }
        }

        $request->validate($validationRules);

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

        // Attempt to authenticate the user.
        if ($this->attemptLogin($request)) {
            $user = Auth::user();
            $user->last_seen = now();
            $user->save();

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful, we will increment the number of attempts
        // and redirect the user back to the login form. If the user surpasses their maximum
        // number of attempts, they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}
