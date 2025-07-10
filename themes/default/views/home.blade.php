@extends('layouts.main')

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
        
        /* Radius */
        --radius-sm: 0.375rem;
        --radius-md: 0.5rem;
        --radius-lg: 0.75rem;
        --radius-xl: 1rem;
        --radius-2xl: 1.5rem;
        
        /* Animations */
        --transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
        --transition-normal: 250ms cubic-bezier(0.4, 0, 0.2, 1);
        --transition-slow: 350ms cubic-bezier(0.4, 0, 0.2, 1);
    }

    * {
        box-sizing: border-box;
    }

    body {
        background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
        color: var(--text-primary);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        line-height: 1.6;
        margin: 0;
        padding: 0;
    }

    /* Header Styles */
    .content-header {
        padding: var(--space-8) 0;
        border-bottom: 1px solid var(--border);
        margin-bottom: var(--space-8);
        background: rgba(15, 15, 15, 0.8);
        backdrop-filter: blur(10px);
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .content-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0;
    }

    .breadcrumb {
        background: none;
        padding: 0;
        margin: 0;
        font-size: 0.875rem;
        display: flex;
        gap: var(--space-2);
        align-items: center;
    }

    .breadcrumb-item {
        color: var(--text-muted);
    }

    .breadcrumb-item + .breadcrumb-item::before {
        content: ">";
        color: var(--orange-primary);
        margin: 0 var(--space-2);
    }

    .breadcrumb-item a {
        color: var(--text-muted);
        text-decoration: none;
        transition: color var(--transition-fast);
    }

    .breadcrumb-item a:hover {
        color: var(--orange-primary);
    }

    /* Alert Styles */
    .callout {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid var(--accent-red);
        border-radius: var(--radius-xl);
        padding: var(--space-6);
        margin-bottom: var(--space-8);
        animation: slideInDown 0.5s ease-out;
        position: relative;
        overflow: hidden;
    }

    .callout::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--accent-red), #dc2626);
    }

    .callout h4 {
        color: var(--accent-red);
        margin-bottom: var(--space-3);
        font-weight: 600;
        font-size: 1.125rem;
    }

    .callout p {
        color: var(--text-secondary);
        margin-bottom: var(--space-4);
        line-height: 1.5;
    }

    .alert {
        background: rgba(255, 107, 53, 0.1);
        border: 1px solid var(--orange-primary);
        border-radius: var(--radius-xl);
        padding: var(--space-6);
        margin-bottom: var(--space-8);
        color: var(--text-primary);
        animation: slideInDown 0.5s ease-out;
        position: relative;
        overflow: hidden;
    }

    .alert::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--orange-primary), var(--orange-secondary));
    }

    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        border-color: var(--accent-green);
    }

    .alert-success::before {
        background: linear-gradient(90deg, var(--accent-green), #059669);
    }

    .alert-warning {
        background: rgba(245, 158, 11, 0.1);
        border-color: var(--accent-yellow);
    }

    .alert-warning::before {
        background: linear-gradient(90deg, var(--accent-yellow), #d97706);
    }

    .alert-danger {
        background: rgba(239, 68, 68, 0.1);
        border-color: var(--accent-red);
    }

    .alert-danger::before {
        background: linear-gradient(90deg, var(--accent-red), #dc2626);
    }

    .alert-info {
        background: rgba(59, 130, 246, 0.1);
        border-color: var(--accent-blue);
    }

    .alert-info::before {
        background: linear-gradient(90deg, var(--accent-blue), #1d4ed8);
    }

    /* Button Styles */
    .btn {
        padding: var(--space-3) var(--space-6);
        border-radius: var(--radius-lg);
        border: none;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: var(--space-2);
        transition: all var(--transition-normal);
        cursor: pointer;
        font-size: 0.875rem;
    }

    .btn-outline-danger {
        background: transparent;
        border: 2px solid var(--accent-red);
        color: var(--accent-red);
    }

    .btn-outline-danger:hover {
        background: var(--accent-red);
        color: white;
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: var(--space-6);
        margin-bottom: var(--space-12);
    }

    .info-box {
        background: var(--bg-card);
        border-radius: var(--radius-2xl);
        padding: var(--space-6);
        border: 1px solid var(--border);
        position: relative;
        overflow: hidden;
        transition: all var(--transition-normal);
        animation: fadeInUp 0.6s ease-out;
        display: flex;
        align-items: center;
        gap: var(--space-5);
    }

    .info-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--orange-primary), var(--orange-secondary));
    }

    .info-box:hover {
        transform: translateY(-4px);
        background: var(--bg-card-hover);
        box-shadow: var(--shadow-xl);
        border-color: var(--border-light);
    }

    .info-box-icon {
        width: 60px;
        height: 60px;
        border-radius: var(--radius-xl);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        flex-shrink: 0;
        position: relative;
        overflow: hidden;
    }

    .info-box-icon::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transform: rotate(45deg);
        transition: all var(--transition-slow);
        opacity: 0;
    }

    .info-box:hover .info-box-icon::after {
        opacity: 1;
        animation: shimmer 1s ease-in-out;
    }

    .info-box-icon.bg-info { 
        background: linear-gradient(135deg, var(--accent-blue), #1d4ed8); 
    }
    
    .info-box-icon.bg-secondary { 
        background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary)); 
    }
    
    .info-box-icon.bg-warning { 
        background: linear-gradient(135deg, var(--accent-yellow), #d97706); 
    }
    
    .info-box-icon.bg-success { 
        background: linear-gradient(135deg, var(--accent-green), #059669); 
    }
    
    .info-box-icon.bg-danger { 
        background: linear-gradient(135deg, var(--accent-red), #dc2626); 
    }

    .info-box-content {
        flex: 1;
    }

    .info-box-text {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: var(--space-1);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .info-box-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: baseline;
        gap: var(--space-2);
        line-height: 1;
    }

    .info-box-number sup {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 400;
        margin-left: var(--space-1);
    }

    /* Card Styles */
    .card {
        background: var(--bg-card);
        border-radius: var(--radius-2xl);
        border: 1px solid var(--border);
        overflow: hidden;
        transition: all var(--transition-normal);
        margin-bottom: var(--space-8);
        animation: fadeInUp 0.6s ease-out;
    }

    .card:hover {
        box-shadow: var(--shadow-lg);
        border-color: var(--border-light);
        transform: translateY(-2px);
    }

    .card-header {
        background: linear-gradient(135deg, var(--bg-secondary), var(--bg-card));
        padding: var(--space-6);
        border-bottom: 1px solid var(--border);
        position: relative;
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

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: var(--space-3);
        margin: 0;
    }

    .card-title i {
        color: var(--orange-primary);
        width: 20px;
        text-align: center;
    }

    .card-body {
        padding: var(--space-6);
        background: var(--bg-card);
        color: var(--text-primary);
    }

    /* Badge Styles */
    .badge {
        padding: var(--space-2) var(--space-4);
        border-radius: var(--radius-lg);
        font-size: 0.875rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: var(--space-2);
        transition: all var(--transition-fast);
    }

    .badge:hover {
        transform: scale(1.05);
    }

    .badge-success {
        background: rgba(16, 185, 129, 0.2);
        color: var(--accent-green);
        border: 1px solid var(--accent-green);
    }

    .badge-info {
        background: rgba(59, 130, 246, 0.2);
        color: var(--accent-blue);
        border: 1px solid var(--accent-blue);
    }

    .badge-warning {
        background: rgba(245, 158, 11, 0.2);
        color: var(--accent-yellow);
        border: 1px solid var(--accent-yellow);
    }

    /* Table Styles */
    .table {
        width: 100%;
        border-collapse: collapse;
        margin: var(--space-4) 0;
        color: var(--text-primary);
    }

    .table th,
    .table td {
        padding: var(--space-4);
        text-align: left;
        border-bottom: 1px solid var(--border);
        transition: all var(--transition-fast);
    }

    .table th {
        background: var(--bg-secondary);
        color: var(--text-primary);
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .table td {
        color: var(--text-secondary);
    }

    .table tbody tr:hover {
        background: rgba(255, 107, 53, 0.05);
    }

    /* List Styles */
    .list-group {
        background: transparent;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .list-group-item {
        background: transparent;
        border: none;
        border-bottom: 1px solid var(--border);
        padding: var(--space-4) 0;
        color: var(--text-secondary);
        transition: all var(--transition-normal);
        position: relative;
    }

    .list-group-item:hover {
        background: rgba(255, 107, 53, 0.05);
        margin: 0 calc(-1 * var(--space-4));
        padding-left: var(--space-4);
        padding-right: var(--space-4);
        border-radius: var(--radius-lg);
        transform: translateX(var(--space-2));
    }

    .list-group-item:last-child {
        border-bottom: none;
    }

    .list-group-item ul {
        list-style: none;
        padding-left: var(--space-4);
        margin: var(--space-2) 0;
    }

    .list-group-item ul li {
        color: var(--text-muted);
        font-size: 0.875rem;
        margin-bottom: var(--space-1);
        line-height: 1.4;
    }

    .list-group-item ul li strong {
        color: var(--text-secondary);
    }

    /* Copy Link Styles */
    .copy-link {
        cursor: pointer;
        transition: all var(--transition-normal);
        padding: var(--space-1) var(--space-2);
        border-radius: var(--radius-md);
        display: inline-flex;
        align-items: center;
        gap: var(--space-1);
    }

    .copy-link:hover {
        background: rgba(255, 107, 53, 0.2);
        color: var(--orange-light);
        transform: scale(1.05);
    }

    /* Alert Dismissible */
    .alert-dismissible {
        background: rgba(255, 107, 53, 0.1);
        border: 1px solid var(--orange-primary);
        border-radius: var(--radius-xl);
        padding: var(--space-4);
        margin-bottom: var(--space-4);
        position: relative;
        transition: all var(--transition-normal);
        overflow: hidden;
    }

    .alert-dismissible::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--orange-primary), var(--orange-secondary));
    }

    .alert-dismissible:hover {
        background: rgba(255, 107, 53, 0.15);
        transform: translateX(var(--space-1));
        box-shadow: var(--shadow-md);
    }

    .alert-dismissible .close {
        position: absolute;
        top: var(--space-3);
        right: var(--space-4);
        background: none;
        border: none;
        color: var(--text-muted);
        font-size: 1.25rem;
        cursor: pointer;
        opacity: 0.7;
        transition: all var(--transition-fast);
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--radius-md);
    }

    .alert-dismissible .close:hover {
        opacity: 1;
        color: var(--orange-primary);
        background: rgba(255, 107, 53, 0.1);
        transform: rotate(90deg);
    }

    .alert-link {
        color: var(--orange-primary);
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: var(--space-2);
        margin-bottom: var(--space-2);
        transition: all var(--transition-fast);
    }

    .alert-link:hover {
        color: var(--orange-secondary);
        transform: translateX(var(--space-1));
    }

    /* Utility Classes */
    .text-success { color: var(--accent-green) !important; }
    .text-danger { color: var(--accent-red) !important; }
    .text-info { color: var(--accent-info) !important; }
    .text-muted { color: var(--text-muted) !important; }
    .w-100 { width: 100%; }
    .d-flex { display: flex; }
    .justify-content-between { justify-content: space-between; }
    .align-items-center { align-items: center; }
    .mb-2 { margin-bottom: var(--space-2); }
    .mb-3 { margin-bottom: var(--space-3); }
    .mt-3 { margin-top: var(--space-3); }
    .mt-4 { margin-top: var(--space-4); }
    .mr-2 { margin-right: var(--space-2); }
    .ml-3 { margin-left: var(--space-3); }
    .py-0 { padding-top: 0; padding-bottom: 0; }
    .pb-2 { padding-bottom: var(--space-2); }
    .pl-0 { padding-left: 0; }

    /* Responsive Design */
    @media (max-width: 768px) {
        .content-header h1 {
            font-size: 2rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
            gap: var(--space-4);
        }
        
        .info-box {
            flex-direction: column;
            text-align: center;
            gap: var(--space-4);
        }

        .info-box-icon {
            margin: 0 auto;
        }

        .card-body {
            padding: var(--space-4);
        }

        .card-header {
            padding: var(--space-4);
        }
    }

    /* Animations */
    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
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

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }

    .elevation-1 {
        box-shadow: var(--shadow-md);
    }

    /* Content Grid */
    .content-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--space-8);
    }

    @media (max-width: 1024px) {
        .content-grid {
            grid-template-columns: 1fr;
            gap: var(--space-6);
        }
    }
</style>

<!-- CONTENT HEADER -->
<section class="content-header">
    <div class="container-fluid">
        <div class="mb-2 row">
            <div class="col-sm-6">
                <h1>{{ __('Dashboard') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a class="text-muted" href="">{{ __('Dashboard') }}</a></li>
                </ol>
            </div>
        </div>
    </div>
</section>
<!-- END CONTENT HEADER -->

@if (!file_exists(base_path() . '/install.lock') && Auth::User()->hasRole("Admin"))
    <div class="callout callout-danger">
        <h4>{{ __('The installer is not locked!') }}</h4>
        <p>
            {{ __('please create a file called "install.lock" in your dashboard Root directory. Otherwise no settings will beloaded!') }}
        </p>
        <a href="/install?step=7" class="btn btn-outline-danger">
            <i class="fas fa-tools"></i>
            {{ __('or click here') }}
        </a>
    </div>
@endif

@if ($general_settings->alert_enabled && !empty($general_settings->alert_message))
    <div class="alert mt-4 alert-{{ $general_settings->alert_type }}" role="alert">
        {!! $general_settings->alert_message !!}
    </div>
@endif

<!-- MAIN CONTENT -->
<section class="content">
    <div class="container-fluid">
        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="info-box fade-in-up">
                <div class="info-box-icon bg-info elevation-1">
                    <i class="fas fa-server"></i>
                </div>
                <div class="info-box-content">
                    <div class="info-box-text">{{ __('Servers') }}</div>
                    <div class="info-box-number">{{ Auth::user()->servers()->count() }}</div>
                </div>
            </div>
            
            <div class="info-box fade-in-up">
                <div class="info-box-icon bg-secondary elevation-1">
                    <i class="fas fa-coins"></i>
                </div>
                <div class="info-box-content">
                    <div class="info-box-text">{{ $general_settings->credits_display_name }}</div>
                    <div class="info-box-number">{{ Auth::user()->Credits() }}</div>
                </div>
            </div>

            <div class="info-box fade-in-up">
                <div class="info-box-icon bg-warning elevation-1">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="info-box-content">
                    <div class="info-box-text">{{ $general_settings->credits_display_name }} {{ __('Usage') }}</div>
                    <div class="info-box-number">
                        {{ number_format($usage, 2, '.', '') }}
                        <sup>{{ __('per month') }}</sup>
                    </div>
                </div>
            </div>

            @if ($credits > 0.01 && $usage > 0)
                <div class="info-box fade-in-up">
                    <div class="info-box-icon {{ $bg }} elevation-1">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div class="info-box-content">
                        <div class="info-box-text">{{ __('Out of Credits in', ['credits' => $general_settings->credits_display_name]) }}</div>
                        <div class="info-box-number">
                            {{ $boxText }}
                            <sup>{{ $unit }}</sup>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Left Column -->
            <div>
                @if ($website_settings->motd_enabled)
                    <div class="card card-default fade-in-up">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-home"></i>
                                {{ config('app.name', 'SneakyHub') }} - MOTD
                            </h3>
                        </div>
                        <div class="card-body">
                            {!! $website_settings->motd_message !!}
                        </div>
                    </div>
                @endif

                @if ($website_settings->useful_links_enabled)
                    <div class="card card-default fade-in-up">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-link"></i>
                                {{ __('Useful Links') }}
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($useful_links_dashboard->count())
                                @foreach ($useful_links_dashboard as $useful_link)
                                    <div class="alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5>
                                            <a class="alert-link text-decoration-none" target="__blank" href="{{ $useful_link->link }}">
                                                <i class="{{ $useful_link->icon }}"></i>
                                                {{ $useful_link->title }}
                                            </a>
                                        </h5>
                                        {!! $useful_link->description !!}
                                    </div>
                                @endforeach
                            @else
                                <span class="text-muted">{{ __('No useful links available') }}</span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column -->
            <div>
                @if ($referral_settings->enabled)
                    <div class="card card-default fade-in-up">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-handshake"></i>
                                {{ __('Partner program') }}
                            </h3>
                        </div>
                        <div class="card-body py-0 pb-2">
                            @if (Auth::user()->can("user.referral"))
                                <div class="row justify-content-between">
                                    <div class="mt-3 col-12 col-md">
                                        <div class="badge badge-success w-100" style="font-size: 14px; padding: var(--space-3); justify-content: center;">
                                            <i class="fa fa-user-check"></i>
                                            {{ __('Your referral URL') }}:
                                            <span onmouseover="hoverIn()" onmouseout="hoverOut()" onclick="onClickCopy()"
                                                id="RefLink" class="copy-link">
                                                <i class="fas fa-copy"></i>
                                                {{ __('Click to copy') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-3 col-12 col-md">
                                        <div class="badge badge-info w-100" style="font-size: 14px; padding: var(--space-3); justify-content: center;">
                                            <i class="fas fa-users"></i>
                                            {{ __('Number of referred users:') }} {{ $numberOfReferrals }}
                                        </div>
                                    </div>
                                </div>
                                
                                @if ($partnerDiscount)
                                    <hr>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Your discount') }}</th>
                                                <th>{{ __('Discount for your new users') }}</th>
                                                <th>{{ __('Reward per registered user') }}</th>
                                                <th>{{ __('New user payment commision') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $partnerDiscount->partner_discount }}%</td>
                                                <td>{{ $partnerDiscount->registered_user_discount }}%</td>
                                                <td>{{ $referral_settings->reward }} {{ $general_settings->credits_display_name }}</td>
                                                <td>{{ $partnerDiscount->referral_system_commission == -1 ? $referral_settings->percentage : $partnerDiscount->referral_system_commission }}%</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @else
                                    <hr>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                @if(in_array($referral_settings->mode, ["sign-up","both"]))
                                                    <th>{{ __('Reward per registered user') }}</th>
                                                @endif
                                                @if(in_array($referral_settings->mode, ["commission","both"]))
                                                    <th>{{ __('New user payment commision') }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                @if(in_array($referral_settings->mode, ["sign-up","both"]))
                                                    <td>{{ $referral_settings->reward }} {{ $general_settings->credits_display_name }}</td>
                                                @endif
                                                @if(in_array($referral_settings->mode, ["commission","both"]))
                                                    <td>{{ $referral_settings->percentage }}%</td>
                                                @endif
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif
                            @else
                                <div class="badge badge-warning" style="width: 100%; justify-content: center; padding: var(--space-4);">
                                    <i class="fa fa-user-check"></i>
                                    {{ __('Make a purchase to reveal your referral-URL') }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
                
                <div class="card card-default fade-in-up">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-history"></i>
                            {{ __('Activity Logs') }}
                        </h3>
                    </div>
                    <div class="card-body py-0 pb-2">
                        <ul class="list-group list-group-flush">
                            @if(Auth::user()->actions()->count())
                                @foreach (Auth::user()->actions()->take(8)->orderBy('created_at', 'desc')->get() as $log)
                                    <li class="list-group-item d-flex justify-content-between text-muted">
                                        <span>
                                            @if (str_starts_with($log->description, 'created'))
                                                <i class="mr-2 fas text-success fa-plus"></i>
                                            @elseif(str_starts_with($log->description, 'redeemed'))
                                                <i class="mr-2 fas text-success fa-money-check-alt"></i>
                                            @elseif(str_starts_with($log->description, 'deleted'))
                                                <i class="mr-2 fas text-danger fa-times"></i>
                                            @elseif(str_starts_with($log->description, 'gained'))
                                                <i class="mr-2 fas text-success fa-money-bill"></i>
                                            @elseif(str_starts_with($log->description, 'updated'))
                                                <i class="mr-2 fas text-info fa-pen"></i>
                                            @endif
                                            {{ explode('\\', $log->subject_type)[2] }}
                                            {{ ucfirst($log->description) }}

                                            @php
                                                $properties = json_decode($log->properties, true);
                                            @endphp

                                            {{-- Handle Created Entries --}}
                                            @if ($log->description === 'created' && isset($properties['attributes']))
                                                <ul class="ml-3">
                                                    @foreach ($properties['attributes'] as $attribute => $value)
                                                        @if (!is_null($value))
                                                            <li>
                                                                <strong>{{ ucfirst($attribute) }}:</strong>
                                                                {{ $attribute === 'created_at' || $attribute === 'updated_at' ?
                                                                \Carbon\Carbon::parse($value)->toDayDateTimeString() : $value }}
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            @endif

                                            {{-- Handle Updated Entries --}}
                                            @if ($log->description === 'updated' && isset($properties['attributes'], $properties['old']))
                                                <ul class="ml-3">
                                                    @foreach ($properties['attributes'] as $attribute => $newValue)
                                                        @if (array_key_exists($attribute, $properties['old']) && !is_null($newValue))
                                                            <li>
                                                                <strong>{{ ucfirst($attribute) }}:</strong>
                                                                {{ $attribute === 'created_at' || $attribute === 'updated_at' ?
                                                                \Carbon\Carbon::parse($properties['old'][$attribute])->toDayDateTimeString()
                                                                . ' → ' . \Carbon\Carbon::parse($newValue)->toDayDateTimeString()
                                                                : $properties['old'][$attribute] . ' → ' . $newValue }}
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            @endif

                                            {{-- Handle Deleted Entries --}}
                                            @if ($log->description === 'deleted' && isset($properties['old']))
                                                <ul class="ml-3">
                                                    @foreach ($properties['old'] as $attribute => $value)
                                                        @if (!is_null($value))
                                                            <li>
                                                                <strong>{{ ucfirst($attribute) }}:</strong>
                                                                {{ $attribute === 'created_at' || $attribute === 'updated_at' ?
                                                                \Carbon\Carbon::parse($value)->toDayDateTimeString() : $value }}
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </span>
                                        <small style="color: var(--text-muted); font-size: 0.75rem;">
                                            {{ $log->created_at->diffForHumans() }}
                                        </small>
                                    </li>
                                @endforeach
                            @else
                                <li class="pl-0 list-group-item text-muted">
                                    <span>
                                        <i class="fas fa-info-circle mr-2"></i>
                                        {{ __('No activity logs available') }}
                                    </span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- END CONTENT -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add staggered animations
        const elements = document.querySelectorAll('.fade-in-up');
        elements.forEach((el, index) => {
            el.style.animationDelay = `${index * 0.1}s`;
        });

        // Enhanced referral link functionality
        const refLink = document.getElementById('RefLink');
        if (refLink) {
            var originalText = refLink.innerHTML;
            var link = "<?php echo route('register') . '?ref=' . Auth::user()->referral_code; ?>";
            var timeoutID;

            window.hoverIn = function() {
                refLink.innerHTML = '<i class="fas fa-link"></i> ' + link;
                timeoutID = setTimeout(function() {
                    refLink.innerHTML = originalText;
                }, 3000);
            }

            window.hoverOut = function() {
                refLink.innerHTML = originalText;
                clearTimeout(timeoutID);
            }

            window.onClickCopy = function() {
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(link).then(() => {
                        // Show success feedback
                        const originalContent = refLink.innerHTML;
                        const originalColor = refLink.style.color;
                        
                        refLink.innerHTML = '<i class="fas fa-check"></i> Copied!';
                        refLink.style.color = 'var(--accent-green)';
                        
                        setTimeout(() => {
                            refLink.innerHTML = originalContent;
                            refLink.style.color = originalColor;
                        }, 2000);

                        // Use SweetAlert if available
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: '{{ __('URL copied to clipboard') }}',
                                position: 'top-end',
                                showConfirmButton: false,
                                background: 'var(--bg-card)',
                                color: 'var(--text-primary)',
                                toast: true,
                                timer: 2000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            });
                        }
                    }).catch(() => {
                        // Fallback for older browsers
                        const textArea = document.createElement('textarea');
                        textArea.value = link;
                        document.body.appendChild(textArea);
                        textArea.select();
                        try {
                            document.execCommand('copy');
                            refLink.innerHTML = '<i class="fas fa-check"></i> Copied!';
                            refLink.style.color = 'var(--accent-green)';
                            setTimeout(() => {
                                refLink.innerHTML = originalText;
                                refLink.style.color = '';
                            }, 2000);
                        } catch (err) {
                            console.log('Copy failed');
                        }
                        document.body.removeChild(textArea);
                    });
                }
            }
        }

        // Enhanced hover effects for cards and info boxes
        const interactiveElements = document.querySelectorAll('.card, .info-box');
        interactiveElements.forEach(element => {
            element.addEventListener('mouseenter', function() {
                this.style.transform = this.classList.contains('info-box') ? 'translateY(-4px)' : 'translateY(-2px)';
            });
            
            element.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Alert dismissal with smooth animation
        const closeButtons = document.querySelectorAll('.alert-dismissible .close');
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const alert = this.closest('.alert-dismissible');
                alert.style.opacity = '0';
                alert.style.transform = 'translateX(-20px)';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 300);
            });
        });

        // Add pulse effect to stats on hover
        const statNumbers = document.querySelectorAll('.info-box-number');
        statNumbers.forEach(stat => {
            stat.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.05)';
            });
            
            stat.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });
    });
</script>
@endsection