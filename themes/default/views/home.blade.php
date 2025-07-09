@extends('layouts.main')

@section('content')
<style>
    :root {
        --primary-dark: #1a1a1a;
        --secondary-dark: #2d2d2d;
        --card-dark: #363636;
        --orange-primary: #ff6b35;
        --orange-secondary: #ff8c42;
        --orange-light: #ffb366;
        --text-primary: #ffffff;
        --text-secondary: #b0b0b0;
        --text-muted: #808080;
        --success: #28a745;
        --warning: #ffc107;
        --danger: #dc3545;
        --info: #17a2b8;
        --border-color: #4a4a4a;
        --shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        --shadow-hover: 0 8px 15px rgba(0, 0, 0, 0.4);
    }

    body {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%);
        color: var(--text-primary);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .content-header {
        padding: 30px 0;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 30px;
    }

    .content-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0;
    }

    .breadcrumb {
        background: none;
        padding: 0;
        margin: 0;
        font-size: 0.9rem;
    }

    .breadcrumb-item {
        color: var(--text-muted);
    }

    .breadcrumb-item + .breadcrumb-item::before {
        content: ">";
        color: var(--orange-primary);
        margin: 0 8px;
    }

    .breadcrumb-item a {
        color: var(--text-muted);
        text-decoration: none;
    }

    .breadcrumb-item a:hover {
        color: var(--orange-primary);
    }

    .callout {
        background: rgba(220, 53, 69, 0.1);
        border: 1px solid var(--danger);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        animation: slideInDown 0.5s ease-out;
    }

    .callout h4 {
        color: var(--danger);
        margin-bottom: 10px;
    }

    .callout p {
        color: var(--text-secondary);
        margin-bottom: 15px;
    }

    .alert {
        background: rgba(255, 107, 53, 0.1);
        border: 1px solid var(--orange-primary);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        color: var(--text-primary);
    }

    .alert-success {
        background: rgba(40, 167, 69, 0.1);
        border-color: var(--success);
    }

    .alert-warning {
        background: rgba(255, 193, 7, 0.1);
        border-color: var(--warning);
    }

    .alert-danger {
        background: rgba(220, 53, 69, 0.1);
        border-color: var(--danger);
    }

    .alert-info {
        background: rgba(23, 162, 184, 0.1);
        border-color: var(--info);
    }

    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-outline-danger {
        background: transparent;
        border: 2px solid var(--danger);
        color: var(--danger);
    }

    .btn-outline-danger:hover {
        background: var(--danger);
        color: white;
        transform: translateY(-2px);
    }

    .info-box {
        background: var(--card-dark);
        border-radius: 16px;
        padding: 25px;
        box-shadow: var(--shadow);
        transition: all 0.3s ease;
        border: 1px solid var(--border-color);
        position: relative;
        overflow: hidden;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 20px;
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
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
    }

    .info-box-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        flex-shrink: 0;
    }

    .info-box-icon.bg-info { 
        background: linear-gradient(135deg, var(--info), #20c3dc); 
    }
    
    .info-box-icon.bg-secondary { 
        background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary)); 
    }
    
    .info-box-icon.bg-warning { 
        background: linear-gradient(135deg, var(--warning), #ffcd39); 
    }
    
    .info-box-icon.bg-success { 
        background: linear-gradient(135deg, var(--success), #34ce57); 
    }
    
    .info-box-icon.bg-danger { 
        background: linear-gradient(135deg, var(--danger), #e55473); 
    }

    .info-box-content {
        flex: 1;
    }

    .info-box-text {
        font-size: 0.9rem;
        color: var(--text-secondary);
        margin-bottom: 5px;
        font-weight: 500;
        display: block;
    }

    .info-box-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: baseline;
        gap: 5px;
    }

    .info-box-number sup {
        font-size: 0.7rem;
        color: var(--text-muted);
        font-weight: 400;
    }

    .card {
        background: var(--card-dark);
        border-radius: 16px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
        overflow: hidden;
        transition: all 0.3s ease;
        margin-bottom: 30px;
    }

    .card:hover {
        box-shadow: var(--shadow-hover);
    }

    .card-header {
        background: linear-gradient(135deg, var(--secondary-dark), var(--card-dark));
        padding: 20px 25px;
        border-bottom: 1px solid var(--border-color);
    }

    .card-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
    }

    .card-title i {
        color: var(--orange-primary);
    }

    .card-body {
        padding: 25px;
        background: var(--card-dark);
        color: var(--text-primary);
    }

    .badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .badge-success {
        background: rgba(40, 167, 69, 0.2);
        color: var(--success);
        border: 1px solid var(--success);
    }

    .badge-info {
        background: rgba(23, 162, 184, 0.2);
        color: var(--info);
        border: 1px solid var(--info);
    }

    .badge-warning {
        background: rgba(255, 193, 7, 0.2);
        color: var(--warning);
        border: 1px solid var(--warning);
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin: 15px 0;
        color: var(--text-primary);
    }

    .table th,
    .table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
    }

    .table th {
        background: var(--secondary-dark);
        color: var(--text-primary);
        font-weight: 600;
        font-size: 0.9rem;
    }

    .table td {
        color: var(--text-secondary);
    }

    .list-group {
        background: transparent;
    }

    .list-group-item {
        background: transparent;
        border: none;
        border-bottom: 1px solid var(--border-color);
        padding: 15px 0;
        color: var(--text-secondary);
        transition: all 0.3s ease;
    }

    .list-group-item:hover {
        background: rgba(255, 107, 53, 0.05);
        margin: 0 -15px;
        padding-left: 15px;
        padding-right: 15px;
        border-radius: 8px;
    }

    .list-group-item:last-child {
        border-bottom: none;
    }

    .list-group-item ul {
        list-style: none;
        padding-left: 15px;
        margin: 8px 0;
    }

    .list-group-item ul li {
        color: var(--text-muted);
        font-size: 0.85rem;
        margin-bottom: 4px;
    }

    .list-group-item ul li strong {
        color: var(--text-secondary);
    }

    .text-success {
        color: var(--success) !important;
    }

    .text-danger {
        color: var(--danger) !important;
    }

    .text-info {
        color: var(--info) !important;
    }

    .text-muted {
        color: var(--text-muted) !important;
    }

    .alert-link {
        color: var(--orange-primary);
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
    }

    .alert-link:hover {
        color: var(--orange-secondary);
    }

    .alert-dismissible {
        background: rgba(255, 107, 53, 0.1);
        border: 1px solid var(--orange-primary);
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 15px;
        position: relative;
        transition: all 0.3s ease;
    }

    .alert-dismissible:hover {
        background: rgba(255, 107, 53, 0.15);
        transform: translateX(5px);
    }

    .alert-dismissible .close {
        position: absolute;
        top: 10px;
        right: 15px;
        background: none;
        border: none;
        color: var(--text-muted);
        font-size: 1.2rem;
        cursor: pointer;
        opacity: 0.7;
        transition: opacity 0.3s ease;
    }

    .alert-dismissible .close:hover {
        opacity: 1;
        color: var(--orange-primary);
    }

    .copy-link {
        cursor: pointer;
        transition: all 0.3s ease;
        padding: 4px 8px;
        border-radius: 6px;
    }

    .copy-link:hover {
        background: rgba(255, 107, 53, 0.2);
        color: var(--orange-light);
    }

    hr {
        border: none;
        height: 1px;
        background: var(--border-color);
        margin: 15px 0;
    }

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

    .fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }

    .elevation-1 {
        box-shadow: var(--shadow);
    }

    .w-100 {
        width: 100%;
    }

    .d-flex {
        display: flex;
    }

    .justify-content-between {
        justify-content: space-between;
    }

    .align-items-center {
        align-items: center;
    }

    .mb-2 {
        margin-bottom: 0.5rem;
    }

    .mb-3 {
        margin-bottom: 1rem;
    }

    .mt-3 {
        margin-top: 1rem;
    }

    .mt-4 {
        margin-top: 1.5rem;
    }

    .mr-2 {
        margin-right: 0.5rem;
    }

    .ml-3 {
        margin-left: 1rem;
    }

    .py-0 {
        padding-top: 0;
        padding-bottom: 0;
    }

    .pb-2 {
        padding-bottom: 0.5rem;
    }

    .pl-0 {
        padding-left: 0;
    }

    @media (max-width: 768px) {
        .content-header h1 {
            font-size: 2rem;
        }
        
        .info-box {
            flex-direction: column;
            text-align: center;
            gap: 15px;
        }

        .info-box-icon {
            margin: 0 auto;
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
        <a href="/install?step=7"><button class="btn btn-outline-danger">{{ __('or click here') }}</button></a>
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
        <div class="row">
            <div class="col-12 col-sm-6 col-md">
                <div class="info-box fade-in-up">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-server"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ __('Servers') }}</span>
                        <span class="info-box-number">{{ Auth::user()->servers()->count() }}</span>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-sm-6 col-md">
                <div class="info-box fade-in-up">
                    <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-coins"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ $general_settings->credits_display_name }}</span>
                        <span class="info-box-number">{{ Auth::user()->Credits() }}</span>
                    </div>
                </div>
            </div>

            <!-- fix for small devices only -->
            <div class="clearfix hidden-md-up"></div>

            <div class="col-12 col-sm-6 col-md">
                <div class="info-box fade-in-up">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-chart-line"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ $general_settings->credits_display_name }} {{ __('Usage') }}</span>
                        <span class="info-box-number">{{ number_format($usage, 2, '.', '') }}<sup>{{ __('per month') }}</sup></span>
                    </div>
                </div>
            </div>

            @if ($credits > 0.01 && $usage > 0)
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box fade-in-up">
                        <span class="info-box-icon {{ $bg }} elevation-1">
                            <i class="fas fa-hourglass-half"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ __('Out of Credits in', ['credits' => $general_settings->credits_display_name]) }}</span>
                            <span class="info-box-number">{{ $boxText }}<sup>{{ $unit }}</sup></span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <div class="row">
            <div class="col-md-6">
                @if ($website_settings->motd_enabled)
                    <div class="card card-default fade-in-up">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="mr-2 fas fa-home"></i>
                                {{ config('app.name', 'MOTD') }} - MOTD
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
                                <i class="mr-2 fas fa-link"></i>
                                {{ __('Useful Links') }}
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($useful_links_dashboard->count())
                                @foreach ($useful_links_dashboard as $useful_link)
                                    <div class="alert alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5>
                                            <a class="alert-link text-decoration-none" target="__blank" href="{{ $useful_link->link }}">
                                                <i class="{{ $useful_link->icon }} mr-2"></i>{{ $useful_link->title }}
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

            <div class="col-md-6">
                @if ($referral_settings->enabled)
                    <div class="card card-default fade-in-up">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="mr-2 fas fa-handshake"></i>
                                {{ __('Partner program') }}
                            </h3>
                        </div>
                        <div class="py-0 pb-2 card-body">
                            @if (Auth::user()->can("user.referral"))
                                <div class="row justify-content-between">
                                    <div class="mt-3 col-12 col-md">
                                        <span class="badge badge-success w-100" style="font-size: 14px">
                                            <i class="mr-2 fa fa-user-check"></i>
                                            {{ __('Your referral URL') }}:
                                            <span onmouseover="hoverIn()" onmouseout="hoverOut()" onclick="onClickCopy()"
                                                id="RefLink" class="copy-link">
                                                {{ __('Click to copy') }}
                                            </span>
                                        </span>
                                    </div>
                                    <div class="mt-3 col-12 col-md">
                                        <span class="badge badge-info w-100" style="font-size: 14px">
                                            {{ __('Number of referred users:') }} {{ $numberOfReferrals }}
                                        </span>
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
                                <span class="badge badge-warning">
                                    <i class="mr-2 fa fa-user-check"></i>
                                    {{ __('Make a purchase to reveal your referral-URL') }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
                
                <div class="card card-default fade-in-up">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="mr-2 fas fa-history"></i>
                            {{ __('Activity Logs') }}
                        </h3>
                    </div>
                    <div class="py-0 pb-2 card-body">
                        <ul class="list-group list-group-flush">
                            @if(Auth::user()->actions()->count())
                                @foreach (Auth::user()->actions()->take(8)->orderBy('created_at', 'desc')->get() as $log)
                                    <li class="list-group-item d-flex justify-content-between text-muted">
                                        <span>
                                            @if (str_starts_with($log->description, 'created'))
                                                <small><i class="mr-2 fas text-success fa-plus"></i></small>
                                            @elseif(str_starts_with($log->description, 'redeemed'))
                                                <small><i class="mr-2 fas text-success fa-money-check-alt"></i></small>
                                            @elseif(str_starts_with($log->description, 'deleted'))
                                                <small><i class="mr-2 fas text-danger fa-times"></i></small>
                                            @elseif(str_starts_with($log->description, 'gained'))
                                                <small><i class="mr-2 fas text-success fa-money-bill"></i></small>
                                            @elseif(str_starts_with($log->description, 'updated'))
                                                <small><i class="mr-2 fas text-info fa-pen"></i></small>
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
                                        <small>
                                            {{ $log->created_at->diffForHumans() }}
                                        </small>
                                    </li>
                                @endforeach
                            @else
                                <li class="pl-0 list-group-item text-muted">
                                    <span>
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
        // Add fade-in animations with staggered delays
        const elements = document.querySelectorAll('.fade-in-up');
        elements.forEach((el, index) => {
            el.style.animationDelay = `${index * 0.1}s`;
        });

        // Enhanced referral link functionality
        const refLink = document.getElementById('RefLink');
        if (refLink) {
            var originalText = refLink.innerText;
            var link = "<?php echo route('register') . '?ref=' . Auth::user()->referral_code; ?>";
            var timeoutID;

            window.hoverIn = function() {
                refLink.innerText = link;
                timeoutID = setTimeout(function() {
                    refLink.innerText = originalText;
                }, 3000);
            }

            window.hoverOut = function() {
                refLink.innerText = originalText;
                clearTimeout(timeoutID);
            }

            window.onClickCopy = function() {
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(link).then(() => {
                        // Show success feedback with modern styling
                        const originalColor = refLink.style.color;
                        const originalText = refLink.textContent;
                        
                        refLink.textContent = '✓ Copied!';
                        refLink.style.color = '#28a745';
                        
                        setTimeout(() => {
                            refLink.textContent = originalText;
                            refLink.style.color = originalColor;
                        }, 2000);

                        // Use SweetAlert if available, otherwise simple notification
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: '{{ __('URL copied to clipboard') }}',
                                position: 'top-end',
                                showConfirmButton: false,
                                background: '#363636',
                                color: '#ffffff',
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
                        console.log('Clipboard API not available');
                        // Fallback for older browsers
                        const textArea = document.createElement('textarea');
                        textArea.value = link;
                        document.body.appendChild(textArea);
                        textArea.select();
                        try {
                            document.execCommand('copy');
                            refLink.textContent = '✓ Copied!';
                            refLink.style.color = '#28a745';
                            setTimeout(() => {
                                refLink.textContent = originalText;
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

        // Enhanced hover effects for cards
        const cards = document.querySelectorAll('.card, .info-box');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            
            card.addEventListener('mouseleave', function() {
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
    });
</script>
@endsection