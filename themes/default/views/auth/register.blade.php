@extends('layouts.app')

@section('content')

<style>
    :root {
        /* Core Colors */
        --bg-primary: #0f0f0f;
        --bg-secondary: #1a1a1a;
        --bg-card: #242427;
        --bg-card-hover: #2a2a2e;
        --bg-elevated: #313137;
        
        /* Brand Colors */
        --orange-primary: #ff6b35;
        --orange-secondary: #ff8c42;
        --orange-light: #ffb366;
        --orange-dark: #e55a2e;
        
        /* Text Colors */
        --text-primary: #ffffff;
        --text-secondary: #b8b8b8;
        --text-muted: #6b6b6b;
        
        /* Accent Colors */
        --accent-blue: #3b82f6;
        --accent-green: #10b981;
        --accent-purple: #8b5cf6;
        --accent-red: #ef4444;
        --accent-yellow: #f59e0b;
        --accent-info: #17a2b8;
        
        /* Border & Shadow */
        --border: #333338;
        --border-light: #404047;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        
        /* Spacing */
        --space-1: 0.25rem;
        --space-2: 0.5rem;
        --space-3: 0.75rem;
        --space-4: 1rem;
        --space-5: 1.25rem;
        --space-6: 1.5rem;
        --space-8: 2rem;
        --space-10: 2.5rem;
        --space-12: 3rem;
        --space-16: 4rem;
        
        /* Radius */
        --radius-sm: 0.375rem;
        --radius-md: 0.5rem;
        --radius-lg: 0.75rem;
        --radius-xl: 1rem;
        --radius-2xl: 1.5rem;
        --radius-3xl: 2rem;
        
        /* Animations */
        --transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
        --transition-normal: 250ms cubic-bezier(0.4, 0, 0.2, 1);
        --transition-slow: 350ms cubic-bezier(0.4, 0, 0.2, 1);
    }

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0 !important;
        padding: 0 !important;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 50%, var(--bg-primary) 100%) !important;
        min-height: 100vh !important;
        color: var(--text-primary) !important;
        position: relative;
        overflow-x: hidden;
    }

    html {
        background: var(--bg-primary) !important;
    }

    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 80%, rgba(255, 107, 53, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(255, 140, 66, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 40% 40%, rgba(255, 107, 53, 0.05) 0%, transparent 50%);
        z-index: -1;
    }

    .register-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: var(--space-6);
        position: relative;
    }

    .register-box {
        width: 100%;
        max-width: 480px;
        animation: fadeInUp 0.8s ease-out;
    }

    .register-card {
        background: var(--bg-card);
        border-radius: var(--radius-3xl);
        border: 1px solid var(--border);
        overflow: hidden;
        position: relative;
        box-shadow: var(--shadow-2xl);
        backdrop-filter: blur(20px);
    }

    .register-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--orange-primary), var(--orange-secondary), var(--orange-primary));
    }

    .register-card::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255, 107, 53, 0.03), transparent);
        transform: rotate(45deg);
        animation: shimmer 3s ease-in-out infinite;
    }

    .card-header {
        background: linear-gradient(135deg, var(--bg-secondary), var(--bg-card));
        padding: var(--space-6);
        text-align: center;
        position: relative;
        border-bottom: 1px solid var(--border);
    }

    .card-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--orange-primary), transparent);
    }

    .brand-title {
        font-size: 2.25rem;
        font-weight: 700;
        background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0 0 var(--space-1) 0;
        text-decoration: none;
        display: inline-block;
        transition: all var(--transition-normal);
    }

    .brand-title:hover {
        transform: scale(1.05);
        text-decoration: none;
        color: inherit;
    }

    .brand-subtitle {
        color: var(--text-secondary);
        font-size: 0.875rem;
        font-weight: 500;
        margin: 0;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .card-body {
        padding: var(--space-8) var(--space-6);
        position: relative;
        z-index: 10;
    }

    .register-subtitle {
        text-align: center;
        color: var(--text-secondary);
        margin-bottom: var(--space-8);
        font-size: 1rem;
        font-weight: 500;
    }

    .form-group {
        margin-bottom: var(--space-5);
    }

    .input-group {
        position: relative;
        display: flex;
        align-items: stretch;
        margin-bottom: var(--space-1);
    }

    .form-control {
        flex: 1;
        padding: var(--space-4) var(--space-5);
        background: var(--bg-elevated);
        border: 2px solid var(--border);
        border-radius: var(--radius-xl) 0 0 var(--radius-xl);
        color: var(--text-primary);
        font-size: 0.875rem;
        transition: all var(--transition-normal);
        outline: none;
    }

    .form-control::placeholder {
        color: var(--text-muted);
    }

    .form-control:focus {
        border-color: var(--orange-primary);
        box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
        background: var(--bg-card-hover);
    }

    .form-control.is-invalid {
        border-color: var(--accent-red);
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }

    .input-group-append {
        display: flex;
    }

    .input-group-text {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: var(--space-4) var(--space-5);
        background: var(--bg-elevated);
        border: 2px solid var(--border);
        border-left: none;
        border-radius: 0 var(--radius-xl) var(--radius-xl) 0;
        color: var(--text-muted);
        transition: all var(--transition-normal);
    }

    .form-control:focus + .input-group-append .input-group-text {
        border-color: var(--orange-primary);
        background: var(--bg-card-hover);
        color: var(--orange-primary);
    }

    .form-control.is-invalid + .input-group-append .input-group-text {
        border-color: var(--accent-red);
        color: var(--accent-red);
    }

    .error-message {
        color: var(--accent-red);
        font-size: 0.75rem;
        margin-top: var(--space-1);
        display: flex;
        align-items: center;
        gap: var(--space-1);
    }

    .alert {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid var(--accent-red);
        border-radius: var(--radius-xl);
        padding: var(--space-4);
        margin-bottom: var(--space-6);
        color: var(--accent-red);
        font-size: 0.875rem;
        position: relative;
        overflow: hidden;
    }

    .alert::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--accent-red), #dc2626);
    }

    .alert-warning {
        background: rgba(245, 158, 11, 0.1);
        border-color: var(--accent-yellow);
        color: var(--accent-yellow);
    }

    .alert-warning::before {
        background: linear-gradient(90deg, var(--accent-yellow), #d97706);
    }

    .alert h5 {
        color: inherit;
        margin-bottom: var(--space-2);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: var(--space-2);
    }

    .recaptcha-container {
        margin-bottom: var(--space-6);
        display: flex;
        justify-content: center;
    }

    .terms-group {
        margin-bottom: var(--space-6);
    }

    .custom-checkbox {
        position: relative;
        display: inline-block;
    }

    .custom-checkbox input[type="checkbox"] {
        opacity: 0;
        position: absolute;
        width: 0;
        height: 0;
    }

    .checkbox-label {
        display: flex;
        align-items: flex-start;
        gap: var(--space-2);
        cursor: pointer;
        font-size: 0.875rem;
        color: var(--text-secondary);
        transition: color var(--transition-fast);
        line-height: 1.4;
    }

    .checkbox-label:hover {
        color: var(--text-primary);
    }

    .checkmark {
        width: 18px;
        height: 18px;
        background: var(--bg-elevated);
        border: 2px solid var(--border);
        border-radius: var(--radius-sm);
        position: relative;
        transition: all var(--transition-normal);
        flex-shrink: 0;
        margin-top: 2px;
    }

    .custom-checkbox input:checked ~ .checkbox-label .checkmark {
        background: var(--orange-primary);
        border-color: var(--orange-primary);
    }

    .checkmark::after {
        content: '';
        position: absolute;
        display: none;
        left: 5px;
        top: 2px;
        width: 4px;
        height: 8px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }

    .custom-checkbox input:checked ~ .checkbox-label .checkmark::after {
        display: block;
    }

    .checkbox-label a {
        color: var(--orange-primary);
        text-decoration: none;
        transition: color var(--transition-fast);
    }

    .checkbox-label a:hover {
        color: var(--orange-secondary);
        text-decoration: underline;
    }

    .btn {
        padding: var(--space-4) var(--space-8);
        border-radius: var(--radius-xl);
        border: none;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-2);
        transition: all var(--transition-normal);
        cursor: pointer;
        font-size: 0.875rem;
        position: relative;
        overflow: hidden;
        outline: none;
        width: 100%;
        margin-bottom: var(--space-6);
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left var(--transition-normal);
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
        color: white;
        box-shadow: var(--shadow-lg);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-xl);
        background: linear-gradient(135deg, var(--orange-dark), var(--orange-primary));
    }

    .btn-secondary {
        background: var(--bg-elevated);
        color: var(--text-primary);
        border: 2px solid var(--border);
    }

    .btn-secondary:hover {
        background: var(--bg-card-hover);
        border-color: var(--border-light);
        transform: translateY(-1px);
    }

    .auth-links {
        padding-top: var(--space-6);
        border-top: 1px solid var(--border);
        text-align: center;
    }

    .auth-links a {
        color: var(--text-secondary);
        text-decoration: none;
        font-size: 0.875rem;
        transition: all var(--transition-fast);
        position: relative;
    }

    .auth-links a::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: var(--orange-primary);
        transition: width var(--transition-normal);
    }

    .auth-links a:hover {
        color: var(--orange-primary);
        text-decoration: none;
    }

    .auth-links a:hover::after {
        width: 100%;
    }

    .footer-links {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(15, 15, 15, 0.8);
        backdrop-filter: blur(10px);
        border-top: 1px solid var(--border);
        padding: var(--space-4);
        text-align: center;
        z-index: 1000;
    }

    .footer-links a {
        color: var(--text-muted);
        text-decoration: none;
        font-size: 0.75rem;
        font-weight: 600;
        transition: color var(--transition-fast);
        margin: 0 var(--space-2);
    }

    .footer-links a:hover {
        color: var(--orange-primary);
        text-decoration: none;
    }

    .separator {
        color: var(--text-muted);
        margin: 0 var(--space-1);
    }

    /* Responsive Design */
    @media (max-width: 480px) {
        .register-container {
            padding: var(--space-4);
        }

        .card-body {
            padding: var(--space-6) var(--space-4);
        }

        .card-header {
            padding: var(--space-5) var(--space-4);
        }

        .brand-title {
            font-size: 1.875rem;
        }

        .brand-subtitle {
            font-size: 0.75rem;
        }

        .footer-links {
            padding: var(--space-3);
        }

        .footer-links a {
            display: block;
            margin: var(--space-1) 0;
        }

        .separator {
            display: none;
        }
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
        100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
    }

    .fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }

    /* Override any existing styles */
    .dark-mode {
        background: var(--bg-primary) !important;
    }

    .register-page {
        background: var(--bg-primary) !important;
    }

    .hold-transition {
        background: var(--bg-primary) !important;
    }

    /* Ensure all containers have dark background */
    .container,
    .container-fluid,
    .content-wrapper,
    .main-wrapper {
        background: transparent !important;
    }
</style>

<body class="hold-transition dark-mode register-page">
    <div class="register-container">
        <div class="register-box">
            @if (!app(App\Settings\UserSettings::class)->creation_enabled)
                <div class="register-card">
                    <div class="card-header">
                        <a href="{{ route('welcome') }}" class="brand-title">
                            <b>SneakyHub</b>
                        </a>
                        <p class="brand-subtitle">Server Management</p>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <h5>
                                <i class="fas fa-exclamation-circle"></i>
                                {{ __('Warning!') }}
                            </h5>
                            {{ __('The system administrator has blocked the registration of new users') }}
                        </div>
                        <a class="btn btn-secondary" href="{{ route('login') }}">
                            <i class="fas fa-arrow-left"></i>
                            {{ __('Back to Login') }}
                        </a>
                    </div>
                </div>
            @else
                <div class="register-card">
                    <div class="card-header">
                        <a href="{{ route('welcome') }}" class="brand-title">
                            <b>SneakyHub</b>
                        </a>
                        <p class="brand-subtitle">Server Management</p>
                    </div>
                    
                    <div class="card-body">
                        <p class="register-subtitle">{{ __('Register a new membership') }}</p>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            @error('ip')
                                <div class="alert">
                                    <i class="fas fa-exclamation-circle" style="margin-right: var(--space-2);"></i>
                                    {{ $message }}
                                </div>
                            @enderror

                            @error('registered')
                                <div class="alert">
                                    <i class="fas fa-exclamation-circle" style="margin-right: var(--space-2);"></i>
                                    {{ $message }}
                                </div>
                            @enderror

                            @if ($errors->has('phoenix_registration_error'))
                                <div class="alert">
                                    <i class="fas fa-exclamation-circle" style="margin-right: var(--space-2);"></i>
                                    @foreach ($errors->get('phoenix_registration_error') as $err)
                                        {{ $err }}
                                    @endforeach
                                </div>
                            @endif

                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror"
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="{{ __('Username') }}"
                                           required 
                                           autocomplete="name" 
                                           autofocus>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                </div>
                                @error('name')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <input type="email" 
                                           name="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           placeholder="{{ __('Email') }}" 
                                           value="{{ old('email') }}" 
                                           required
                                           autocomplete="email">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                    </div>
                                </div>
                                @error('email')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror"
                                           placeholder="{{ __('Password') }}" 
                                           name="password" 
                                           required
                                           autocomplete="new-password">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </div>
                                    </div>
                                </div>
                                @error('password')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control" 
                                           name="password_confirmation"
                                           placeholder="{{ __('Retype password') }}" 
                                           required 
                                           autocomplete="new-password">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if (app(App\Settings\ReferralSettings::class)->enabled)
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" 
                                               value="{{ Request::get('ref') }}" 
                                               class="form-control"
                                               name="referral_code"
                                               placeholder="{{ __('Referral code') }} ({{ __('optional') }})">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <i class="fas fa-user-check"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @php ($recaptchaVersion = app(App\Settings\GeneralSettings::class)->recaptcha_version)
                            @if ($recaptchaVersion)
                                <div class="recaptcha-container">
                                    @switch($recaptchaVersion)
                                        @case("v2")
                                            {!! htmlFormSnippet() !!}
                                            @break
                                        @case("v3")
                                            {!! RecaptchaV3::field('recaptchathree') !!}
                                            @break
                                    @endswitch
                                </div>
                                @error('g-recaptcha-response')
                                    <div class="error-message" style="text-align: center; margin-bottom: var(--space-4);">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            @endif

                            @php($website_settings = app(App\Settings\WebsiteSettings::class))
                            @if ($website_settings->show_tos)
                                <div class="terms-group">
                                    <div class="custom-checkbox">
                                        <input type="checkbox" id="agreeTerms" name="terms" value="agree">
                                        <label for="agreeTerms" class="checkbox-label">
                                            <span class="checkmark"></span>
                                            <span>
                                                {{ __("I agree to the") }} 
                                                <a target="_blank" href="{{ route('terms', 'tos') }}">
                                                    {{ __("Terms of Service") }}
                                                </a>
                                            </span>
                                        </label>
                                    </div>
                                    @error('terms')
                                        <div class="error-message" style="margin-top: var(--space-2);">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            @endif

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-user-plus"></i>
                                {{ __('Create Account') }}
                            </button>

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </form>

                        <div class="auth-links">
                            <a href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt" style="margin-right: var(--space-1);"></i>
                                {{ __('I already have a membership') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Footer Links --}}
    <div class="footer-links">
        @if ($website_settings->show_imprint)
            <a href="{{ route('terms', 'imprint') }}" target="_blank">
                {{ __('Imprint') }}
            </a>
            <span class="separator">|</span>
        @endif
        @if ($website_settings->show_privacy)
            <a href="{{ route('terms', 'privacy') }}" target="_blank">
                {{ __('Privacy') }}
            </a>
        @endif
        @if ($website_settings->show_tos)
            <span class="separator">|</span>
            <a href="{{ route('terms', 'tos') }}" target="_blank">
                {{ __('Terms of Service') }}
            </a>
        @endif
    </div>
</body>
@endsection