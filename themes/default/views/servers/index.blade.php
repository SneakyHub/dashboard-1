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
        list-style: none;
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

    /* Action Bar */
    .action-bar {
        display: flex;
        gap: var(--space-4);
        margin-bottom: var(--space-8);
        align-items: center;
        flex-wrap: wrap;
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
        position: relative;
        overflow: hidden;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transition: left var(--transition-normal);
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
        color: white;
        box-shadow: var(--shadow-md);
    }

    .btn-primary:hover:not(.disabled) {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
        background: linear-gradient(135deg, var(--orange-dark), var(--orange-primary));
    }

    .btn-secondary {
        background: linear-gradient(135deg, var(--bg-elevated), var(--bg-card-hover));
        color: var(--text-primary);
        border: 1px solid var(--border);
    }

    .btn-secondary:hover {
        background: linear-gradient(135deg, var(--bg-card-hover), var(--bg-elevated));
        border-color: var(--border-light);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-info {
        background: linear-gradient(135deg, var(--accent-blue), #1d4ed8);
        color: white;
    }

    .btn-info:hover {
        background: linear-gradient(135deg, #1d4ed8, var(--accent-blue));
        transform: translateY(-1px);
    }

    .btn-warning {
        background: linear-gradient(135deg, var(--accent-yellow), #d97706);
        color: white;
    }

    .btn-warning:hover:not(:disabled) {
        background: linear-gradient(135deg, #d97706, var(--accent-yellow));
        transform: translateY(-1px);
    }

    .btn-danger {
        background: linear-gradient(135deg, var(--accent-red), #dc2626);
        color: white;
    }

    .btn-danger:hover {
        background: linear-gradient(135deg, #dc2626, var(--accent-red));
        transform: translateY(-1px);
    }

    .btn.disabled,
    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }

    /* Server Grid */
    .servers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: var(--space-6);
        margin-bottom: var(--space-8);
    }

    /* Server Card */
    .server-card {
        background: var(--bg-card);
        border-radius: var(--radius-2xl);
        border: 1px solid var(--border);
        overflow: hidden;
        transition: all var(--transition-normal);
        animation: fadeInUp 0.6s ease-out;
        position: relative;
    }

    .server-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--orange-primary), var(--orange-secondary));
    }

    .server-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-xl);
        border-color: var(--border-light);
    }

    .server-card-header {
        background: linear-gradient(135deg, var(--bg-secondary), var(--bg-card));
        padding: var(--space-5);
        border-bottom: 1px solid var(--border);
        position: relative;
    }

    .server-card-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--orange-primary), transparent);
    }

    .server-card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: var(--space-2);
    }

    .server-card-body {
        padding: var(--space-5);
    }

    .server-info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-3);
        padding: var(--space-2) 0;
        transition: all var(--transition-fast);
    }

    .server-info-row:hover {
        background: rgba(255, 107, 53, 0.05);
        margin: 0 calc(-1 * var(--space-2));
        padding: var(--space-2);
        border-radius: var(--radius-md);
    }

    .server-info-label {
        color: var(--text-secondary);
        font-weight: 500;
        font-size: 0.875rem;
    }

    .server-info-value {
        color: var(--text-primary);
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: var(--space-2);
    }

    .server-info-icon {
        color: var(--text-muted);
        cursor: help;
        transition: color var(--transition-fast);
    }

    .server-info-icon:hover {
        color: var(--orange-primary);
    }

    /* Badge Styles */
    .badge {
        padding: var(--space-1) var(--space-3);
        border-radius: var(--radius-lg);
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: var(--space-1);
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .badge-success {
        background: rgba(16, 185, 129, 0.2);
        color: var(--accent-green);
        border: 1px solid var(--accent-green);
    }

    .badge-success::before {
        content: '';
        width: 6px;
        height: 6px;
        background: var(--accent-green);
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    .badge-warning {
        background: rgba(245, 158, 11, 0.2);
        color: var(--accent-yellow);
        border: 1px solid var(--accent-yellow);
    }

    .badge-danger {
        background: rgba(239, 68, 68, 0.2);
        color: var(--accent-red);
        border: 1px solid var(--accent-red);
    }

    /* Server Actions */
    .server-actions {
        padding: var(--space-4) var(--space-5);
        background: var(--bg-elevated);
        border-top: 1px solid var(--border);
        display: flex;
        gap: var(--space-2);
        align-items: center;
        justify-content: space-between;
    }

    .server-actions .btn {
        padding: var(--space-2) var(--space-3);
        font-size: 0.75rem;
        min-width: auto;
    }

    .server-actions .btn i {
        font-size: 0.875rem;
    }

    /* Price Display */
    .price-display {
        text-align: center;
        padding: var(--space-3);
        background: rgba(255, 107, 53, 0.05);
        border-radius: var(--radius-lg);
        margin-top: var(--space-3);
    }

    .price-period {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-bottom: var(--space-1);
    }

    .price-amount {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: var(--space-12) var(--space-6);
        background: var(--bg-card);
        border-radius: var(--radius-2xl);
        border: 1px solid var(--border);
    }

    .empty-state-icon {
        font-size: 4rem;
        color: var(--text-muted);
        margin-bottom: var(--space-4);
    }

    .empty-state-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: var(--space-2);
    }

    .empty-state-description {
        color: var(--text-secondary);
        margin-bottom: var(--space-6);
    }

    /* Utility Classes */
    .text-muted { color: var(--text-muted) !important; }
    .text-center { text-align: center; }
    .d-flex { display: flex; }
    .justify-content-between { justify-content: space-between; }
    .justify-content-center { justify-content: center; }
    .align-items-center { align-items: center; }
    .mb-2 { margin-bottom: var(--space-2); }
    .mb-3 { margin-bottom: var(--space-3); }
    .float-left { float: left; }
    .float-right { float: right; }

    /* Responsive Design */
    @media (max-width: 768px) {
        .content-header h1 {
            font-size: 2rem;
        }

        .servers-grid {
            grid-template-columns: 1fr;
            gap: var(--space-4);
        }

        .action-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .server-actions {
            flex-direction: column;
            gap: var(--space-2);
        }

        .server-actions .btn {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 576px) {
        .server-info-row {
            flex-direction: column;
            align-items: flex-start;
            gap: var(--space-1);
        }

        .server-info-value {
            align-self: flex-end;
        }
    }

    /* Animations */
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

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }

    /* Hover effects for tooltips */
    [data-toggle="tooltip"],
    [data-toggle="popover"] {
        cursor: help;
    }
</style>

<!-- CONTENT HEADER -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ __('Servers') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a class="text-muted" href="{{ route('servers.index') }}">{{ __('Servers') }}</a></li>
                </ol>
            </div>
        </div>
    </div>
</section>
<!-- END CONTENT HEADER -->

<!-- MAIN CONTENT -->
<section class="content">
    <div class="container-fluid">
        <!-- Action Bar -->
        <div class="action-bar">
            <a @if (Auth::user()->Servers->count() >= Auth::user()->server_limit) 
                class="btn btn-primary disabled" 
                title="Server limit reached!" 
               @endif
               @cannot("user.server.create") 
                class="btn btn-primary disabled" 
                title="No Permission!" 
               @endcannot
               @if (Auth::user()->Servers->count() < Auth::user()->server_limit && Auth::user()->can("user.server.create"))
                class="btn btn-primary"
               @endif
               href="{{ route('servers.create') }}">
                <i class="fas fa-plus"></i>
                {{ __('Create Server') }}
            </a>
            
            @if (Auth::user()->Servers->count() > 0 && !empty($phpmyadmin_url))
                <a href="{{ $phpmyadmin_url }}" target="_blank" class="btn btn-secondary">
                    <i class="fas fa-database"></i>
                    {{ __('Database') }}
                </a>
            @endif
        </div>

        <!-- Servers Grid -->
        @if($servers->count() > 0)
            <div class="servers-grid">
                @foreach ($servers as $server)
                    @if($server->location && $server->node && $server->nest && $server->egg)
                        <div class="server-card fade-in-up" style="animation-delay: {{ $loop->index * 0.1 }}s">
                            <!-- Card Header -->
                            <div class="server-card-header">
                                <h5 class="server-card-title">
                                    <i class="fas fa-server" style="color: var(--orange-primary);"></i>
                                    {{ $server->name }}
                                </h5>
                            </div>

                            <!-- Card Body -->
                            <div class="server-card-body">
                                <!-- Status -->
                                <div class="server-info-row">
                                    <span class="server-info-label">{{ __('Status') }}</span>
                                    <div class="server-info-value">
                                        @if($server->suspended)
                                            <span class="badge badge-danger">{{ __('Suspended') }}</span>
                                        @elseif($server->canceled)
                                            <span class="badge badge-warning">{{ __('Canceled') }}</span>
                                        @else
                                            <span class="badge badge-success">{{ __('Active') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Location -->
                                <div class="server-info-row">
                                    <span class="server-info-label">{{ __('Location') }}</span>
                                    <div class="server-info-value">
                                        <span>{{ $server->location }}</span>
                                        <i class="fas fa-info-circle server-info-icon" 
                                           data-toggle="popover" 
                                           data-trigger="hover"
                                           data-content="{{ __('Node') }}: {{ $server->node }}"></i>
                                    </div>
                                </div>

                                <!-- Software -->
                                <div class="server-info-row">
                                    <span class="server-info-label">{{ __('Software') }}</span>
                                    <div class="server-info-value">
                                        <span>{{ $server->nest }}</span>
                                    </div>
                                </div>

                                <!-- Specification -->
                                <div class="server-info-row">
                                    <span class="server-info-label">{{ __('Specification') }}</span>
                                    <div class="server-info-value">
                                        <span>{{ $server->egg }}</span>
                                    </div>
                                </div>

                                <!-- Resource Plan -->
                                <div class="server-info-row">
                                    <span class="server-info-label">{{ __('Resource plan') }}</span>
                                    <div class="server-info-value">
                                        <span>{{ $server->product->name }}</span>
                                        <i class="fas fa-info-circle server-info-icon" 
                                           data-toggle="popover" 
                                           data-trigger="hover" 
                                           data-html="true"
                                           data-content="{{ __('CPU') }}: {{ $server->product->cpu / 100 }} {{ __('vCores') }} <br/>{{ __('RAM') }}: {{ $server->product->memory }} MB <br/>{{ __('Disk') }}: {{ $server->product->disk }} MB <br/>{{ __('Backups') }}: {{ $server->product->backups }} <br/> {{ __('MySQL Databases') }}: {{ $server->product->databases }} <br/> {{ __('Allocations') }}: {{ $server->product->allocations }} <br/>{{ __('OOM Killer') }}: {{ $server->product->oom_killer ? __("enabled") : __("disabled") }} <br/> {{ __('Billing Period') }}: {{$server->product->billing_period}}"></i>
                                    </div>
                                </div>

                                <!-- Next Billing Cycle -->
                                <div class="server-info-row">
                                    <span class="server-info-label">{{ __('Next Billing Cycle') }}</span>
                                    <div class="server-info-value">
                                        <span style="font-size: 0.875rem;">
                                            @if ($server->suspended)
                                                -
                                            @else
                                                @switch($server->product->billing_period)
                                                    @case('monthly')
                                                        {{ \Carbon\Carbon::parse($server->last_billed)->addMonth()->format('M j, Y') }}
                                                        @break
                                                    @case('weekly')
                                                        {{ \Carbon\Carbon::parse($server->last_billed)->addWeek()->format('M j, Y') }}
                                                        @break
                                                    @case('daily')
                                                        {{ \Carbon\Carbon::parse($server->last_billed)->addDay()->format('M j, Y') }}
                                                        @break
                                                    @case('hourly')
                                                        {{ \Carbon\Carbon::parse($server->last_billed)->addHour()->format('M j, H:i') }}
                                                        @break
                                                    @case('quarterly')
                                                        {{ \Carbon\Carbon::parse($server->last_billed)->addMonths(3)->format('M j, Y') }}
                                                        @break
                                                    @case('half-annually')
                                                        {{ \Carbon\Carbon::parse($server->last_billed)->addMonths(6)->format('M j, Y') }}
                                                        @break
                                                    @case('annually')
                                                        {{ \Carbon\Carbon::parse($server->last_billed)->addYear()->format('M j, Y') }}
                                                        @break
                                                    @default
                                                        {{ __('Unknown') }}
                                                @endswitch
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <!-- Pricing -->
                                <div class="price-display">
                                    <div class="price-period">
                                        @if($server->product->billing_period == 'monthly')
                                            {{ __('per Month') }}
                                        @elseif($server->product->billing_period == 'half-annually')
                                            {{ __('per 6 Months') }}
                                        @elseif($server->product->billing_period == 'quarterly')
                                            {{ __('per 3 Months') }}
                                        @elseif($server->product->billing_period == 'annually')
                                            {{ __('per Year') }}
                                        @elseif($server->product->billing_period == 'weekly')
                                            {{ __('per Week') }}
                                        @elseif($server->product->billing_period == 'daily')
                                            {{ __('per Day') }}
                                        @elseif($server->product->billing_period == 'hourly')
                                            {{ __('per Hour') }}
                                        @endif
                                        <i class="fas fa-info-circle server-info-icon" 
                                           data-toggle="popover" 
                                           data-trigger="hover"
                                           data-content="{{ __('Your') ." " . $credits_display_name . " ". __('are reduced') ." ". $server->product->billing_period . ". " . __("This however calculates to ") . number_format($server->product->getMonthlyPrice(),2,",",".") . " ". $credits_display_name . " ". __('per Month')}}"></i>
                                    </div>
                                    <div class="price-amount">
                                        {{ $server->product->price == round($server->product->price) ? round($server->product->price) : $server->product->price }}
                                        {{ $credits_display_name }}
                                    </div>
                                </div>
                            </div>

                            <!-- Card Actions -->
                            <div class="server-actions">
                                <div style="display: flex; gap: var(--space-2);">
                                    <a href="{{ $phoenixpanel_url }}/server/{{ $server->identifier }}"
                                       target="_blank"
                                       class="btn btn-info"
                                       data-toggle="tooltip" 
                                       data-placement="top" 
                                       title="{{ __('Manage Server') }}">
                                        <i class="fas fa-tools"></i>
                                    </a>
                                    
                                    <a href="{{ route('servers.show', ['server' => $server->id]) }}"
                                       class="btn btn-info"
                                       data-toggle="tooltip" 
                                       data-placement="top" 
                                       title="{{ __('Server Settings') }}">
                                        <i class="fas fa-cog"></i>
                                    </a>
                                </div>
                                
                                <div style="display: flex; gap: var(--space-2);">
                                    <button onclick="handleServerCancel('{{ $server->id }}');"
                                            class="btn btn-warning"
                                            {{ $server->suspended || $server->canceled ? "disabled" : "" }}
                                            data-toggle="tooltip" 
                                            data-placement="top" 
                                            title="{{ __('Cancel Server') }}">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                    
                                    <button onclick="handleServerDelete('{{ $server->id }}');"
                                            class="btn btn-danger"
                                            data-toggle="tooltip" 
                                            data-placement="top" 
                                            title="{{ __('Delete Server') }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="empty-state fade-in-up">
                <div class="empty-state-icon">
                    <i class="fas fa-server"></i>
                </div>
                <h3 class="empty-state-title">{{ __('No Servers Yet') }}</h3>
                <p class="empty-state-description">
                    {{ __('You haven\'t created any servers yet. Click the button below to create your first server.') }}
                </p>
                @if (Auth::user()->Servers->count() < Auth::user()->server_limit && Auth::user()->can("user.server.create"))
                    <a href="{{ route('servers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        {{ __('Create Your First Server') }}
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>
<!-- END CONTENT -->

<script>
    const handleServerCancel = (serverId) => {
        Swal.fire({
            title: "{{ __('Cancel Server?') }}",
            text: "{{ __('This will cancel your current server to the next billing period. It will get suspended when the current period runs out.') }}",
            icon: 'warning',
            background: 'var(--bg-card)',
            color: 'var(--text-primary)',
            confirmButtonColor: 'var(--accent-yellow)',
            cancelButtonColor: 'var(--bg-elevated)',
            showCancelButton: true,
            confirmButtonText: "{{ __('Yes, cancel it!') }}",
            cancelButtonText: "{{ __('No, abort!') }}",
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                fetch("{{ route('servers.cancel', '') }}" + '/' + serverId, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(() => {
                    window.location.reload();
                }).catch((error) => {
                    Swal.fire({
                        title: "{{ __('Error') }}",
                        text: "{{ __('Something went wrong, please try again later.') }}",
                        icon: 'error',
                        background: 'var(--bg-card)',
                        color: 'var(--text-primary)',
                        confirmButtonColor: 'var(--accent-red)',
                    })
                })
            }
        })
    }

    const handleServerDelete = (serverId) => {
        Swal.fire({
            title: "{{ __('Delete Server?') }}",
            html: "{!! __('This is an irreversible action, all files of this server will be removed. <strong>No funds will get refunded</strong>. We recommend deleting the server when server is suspended.') !!}",
            icon: 'warning',
            background: 'var(--bg-card)',
            color: 'var(--text-primary)',
            confirmButtonColor: 'var(--accent-red)',
            cancelButtonColor: 'var(--bg-elevated)',
            showCancelButton: true,
            confirmButtonText: "{{ __('Yes, delete it!') }}",
            cancelButtonText: "{{ __('No, abort!') }}",
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                fetch("{{ route('servers.destroy', '') }}" + '/' + serverId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(() => {
                    window.location.reload();
                }).catch((error) => {
                    Swal.fire({
                        title: "{{ __('Error') }}",
                        text: "{{ __('Something went wrong, please try again later.') }}",
                        icon: 'error',
                        background: 'var(--bg-card)',
                        color: 'var(--text-primary)',
                        confirmButtonColor: 'var(--accent-red)',
                    })
                })
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Initialize tooltips and popovers
        $('[data-toggle="popover"]').popover({
            container: 'body',
            html: true
        });
        
        $('[data-toggle="tooltip"]').tooltip({
            container: 'body'
        });

        // Add staggered animations
        const serverCards = document.querySelectorAll('.server-card');
        serverCards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });

        // Enhanced hover effects
        const cards = document.querySelectorAll('.server-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-4px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>
@endsection