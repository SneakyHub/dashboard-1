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

    /* Alert Styles */
    .alert {
        background: rgba(255, 107, 53, 0.1);
        border: 1px solid var(--orange-primary);
        border-radius: var(--radius-xl);
        padding: var(--space-6);
        margin: var(--space-4);
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

    .alert h5 {
        color: var(--accent-red);
        margin-bottom: var(--space-3);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: var(--space-2);
    }

    .alert ul {
        margin-bottom: 0;
        padding-left: var(--space-6);
    }

    .alert li {
        margin-bottom: var(--space-1);
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
        position: relative;
    }

    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--orange-primary), var(--orange-secondary));
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

    /* Form Styles */
    .form-group {
        margin-bottom: var(--space-6);
    }

    .form-group label {
        display: block;
        margin-bottom: var(--space-2);
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .form-control,
    .custom-select {
        width: 100%;
        padding: var(--space-4);
        background: var(--bg-elevated);
        border: 2px solid var(--border);
        border-radius: var(--radius-lg);
        color: var(--text-primary);
        font-size: 0.875rem;
        transition: all var(--transition-normal);
    }

    .form-control:focus,
    .custom-select:focus {
        outline: none;
        border-color: var(--orange-primary);
        box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
        background: var(--bg-card-hover);
    }

    .form-control:disabled,
    .custom-select:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .invalid-feedback {
        color: var(--accent-red);
        font-size: 0.75rem;
        margin-top: var(--space-1);
        display: block;
    }

    .is-invalid {
        border-color: var(--accent-red);
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

    .btn-warning {
        background: linear-gradient(135deg, var(--accent-yellow), #d97706);
        color: white;
    }

    .btn-warning:hover {
        background: linear-gradient(135deg, #d97706, var(--accent-yellow));
        transform: translateY(-2px);
    }

    .btn-block {
        width: 100%;
        justify-content: center;
    }

    .btn.disabled,
    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }

    /* Product Grid */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: var(--space-6);
        margin-top: var(--space-8);
    }

    /* Product Card */
    .product-card {
        background: var(--bg-card);
        border-radius: var(--radius-2xl);
        border: 1px solid var(--border);
        padding: var(--space-6);
        transition: all var(--transition-normal);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        animation: fadeInUp 0.6s ease-out;
    }

    .product-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--orange-primary), var(--orange-secondary));
    }

    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-xl);
        border-color: var(--border-light);
    }

    .product-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-6);
    }

    .product-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .product-limit {
        color: var(--text-muted);
        font-size: 0.875rem;
        background: var(--bg-elevated);
        padding: var(--space-1) var(--space-3);
        border-radius: var(--radius-lg);
    }

    .product-specs {
        margin-bottom: var(--space-6);
    }

    .product-specs h6 {
        color: var(--text-secondary);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: var(--space-3);
        font-weight: 600;
    }

    .spec-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .spec-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: var(--space-2) 0;
        border-bottom: 1px solid var(--border);
        transition: all var(--transition-fast);
    }

    .spec-item:hover {
        background: rgba(255, 107, 53, 0.05);
        margin: 0 calc(-1 * var(--space-2));
        padding-left: var(--space-2);
        padding-right: var(--space-2);
        border-radius: var(--radius-md);
    }

    .spec-item:last-child {
        border-bottom: none;
    }

    .spec-label {
        color: var(--text-secondary);
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: var(--space-2);
    }

    .spec-label i {
        color: var(--orange-primary);
        width: 16px;
        text-align: center;
    }

    .spec-value {
        color: var(--text-primary);
        font-weight: 600;
        font-size: 0.875rem;
    }

    .product-description {
        margin-bottom: var(--space-6);
    }

    .product-description h6 {
        color: var(--text-secondary);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: var(--space-2);
        font-weight: 600;
    }

    .product-description p {
        color: var(--text-secondary);
        font-size: 0.875rem;
        line-height: 1.5;
        margin: 0;
    }

    .product-pricing {
        background: rgba(255, 107, 53, 0.05);
        border: 1px solid var(--orange-primary);
        border-radius: var(--radius-lg);
        padding: var(--space-4);
        margin-bottom: var(--space-6);
        text-align: center;
    }

    .pricing-label {
        color: var(--text-muted);
        font-size: 0.75rem;
        margin-bottom: var(--space-1);
    }

    .pricing-amount {
        color: var(--text-primary);
        font-size: 1.125rem;
        font-weight: 700;
    }

    .product-actions {
        margin-top: auto;
        display: flex;
        flex-direction: column;
        gap: var(--space-3);
    }

    /* Loading Overlay */
    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        border-radius: var(--radius-2xl);
    }

    .overlay i {
        color: var(--orange-primary);
        animation: spin 1s linear infinite;
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
    .mt-2 { margin-top: var(--space-2); }
    .mt-4 { margin-top: var(--space-4); }
    .w-100 { width: 100%; }

    /* Responsive Design */
    @media (max-width: 768px) {
        .content-header h1 {
            font-size: 2rem;
        }

        .products-grid {
            grid-template-columns: 1fr;
            gap: var(--space-4);
        }

        .card-body {
            padding: var(--space-4);
        }

        .card-header {
            padding: var(--space-4);
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

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }
</style>

<!-- CONTENT HEADER -->
<section class="content-header">
    <div class="container-fluid">
        <div class="mb-2 row">
            <div class="col-sm-6">
                <h1>{{ __('Servers') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('servers.index') }}">{{ __('Servers') }}</a></li>
                    <li class="breadcrumb-item"><a class="text-muted" href="{{ route('servers.create') }}">{{ __('Create') }}</a></li>
                </ol>
            </div>
        </div>
    </div>
</section>
<!-- END CONTENT HEADER -->

<!-- MAIN CONTENT -->
<section x-data="serverApp()" class="content">
    <div class="container-xxl">
        <!-- FORM -->
        <form action="{{ route('servers.store') }}" x-on:submit="submitClicked = true" method="post"
            class="row justify-content-center"
            id="serverForm">
            @csrf
            <div class="col-xl-6 col-lg-8 col-md-8 col-sm-10">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fas fa-cogs"></i>
                            {{ __('Server configuration') }}
                        </div>
                    </div>
                    
                    @if (!$server_creation_enabled)
                        <div class="alert alert-warning">
                            {{ __('The creation of new servers has been disabled for regular users, enable it again') }}
                            <a href="{{ route('admin.settings.index', "#Server") }}" style="color: var(--accent-yellow); text-decoration: underline;">{{ __('here') }}</a>.
                        </div>
                    @endif
                    
                    @if ($productCount === 0 || $nodeCount === 0 || count($nests) === 0 || count($eggs) === 0)
                        <div class="alert alert-danger">
                            <h5>
                                <i class="fas fa-exclamation-circle"></i>
                                {{ __('Error!') }}
                            </h5>
                            @if (Auth::user()->hasRole("Admin"))
                                <p>
                                    {{ __('Make sure to link your products to nodes and eggs.') }}<br>
                                    {{ __('There has to be at least 1 valid product for server creation') }}
                                    <a href="{{ route('admin.overview.sync') }}" style="color: var(--accent-red); text-decoration: underline;">{{ __('Sync now') }}</a>
                                </p>
                            @endif
                            <ul>
                                @if ($productCount === 0)
                                    <li>{{ __('No products available!') }}</li>
                                @endif
                                @if ($nodeCount === 0)
                                    <li>{{ __('No nodes have been linked!') }}</li>
                                @endif
                                @if (count($nests) === 0)
                                    <li>{{ __('No nests available!') }}</li>
                                @endif
                                @if (count($eggs) === 0)
                                    <li>{{ __('No eggs have been linked!') }}</li>
                                @endif
                            </ul>
                        </div>
                    @endif

                    <div x-show="loading" class="overlay dark">
                        <i class="fas fa-2x fa-sync-alt"></i>
                    </div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul style="list-style: none; padding-left: 0; margin: 0;">
                                    @foreach ($errors->all() as $error)
                                        <li style="margin-bottom: var(--space-1);"><i class="fas fa-exclamation-circle" style="margin-right: var(--space-2);"></i>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="name">{{ __('Name') }}</label>
                            <input x-model="name" id="name" name="name" type="text" required="required"
                                class="form-control @error('name') is-invalid @enderror" 
                                placeholder="{{ __('Enter server name...') }}">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nest">{{ __('Software / Games') }}</label>
                                    <select class="custom-select" required name="nest" id="nest"
                                        x-model="selectedNest" @change="setEggs();">
                                        <option selected disabled hidden value="null">
                                            {{ count($nests) > 0 ? __('Please select software ...') : __('---') }}
                                        </option>
                                        @foreach ($nests as $nest)
                                            <option value="{{ $nest->id }}">{{ $nest->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="egg">{{ __('Specification') }}</label>
                                    <select id="egg" required name="egg" :disabled="eggs.length == 0"
                                        x-model="selectedEgg" @change="fetchLocations();" required="required"
                                        class="custom-select">
                                        <option x-text="getEggInputText()" selected disabled hidden value="null">
                                        </option>
                                        <template x-for="egg in eggs" :key="egg.id">
                                            <option x-text="egg.name" :value="egg.id"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="location">
                                {{ __('Location') }}
                                @if($location_description_enabled)
                                    <i x-show="locationDescription != null" data-toggle="popover" data-trigger="click"
                                       x-bind:data-content="locationDescription"
                                       class="fas fa-info-circle" style="color: var(--orange-primary); cursor: help; margin-left: var(--space-2);"></i>
                                @endif
                            </label>
                            <select name="location" required id="location" x-model="selectedLocation" :disabled="!fetchedLocations"
                                    @change="fetchProducts();" class="custom-select">
                                <option x-text="getLocationInputText()" disabled selected hidden value="null">
                                </option>
                                <template x-for="location in locations" :key="location.id">
                                    <option x-text="location.name" :value="location.id">
                                    </option>
                                </template>
                            </select>
                        </div>
                        
                        <template x-if="selectedProduct != null && selectedProduct != '' && locations.length == 0 && !loading">
                            <div class="alert alert-danger">
                                {{ __('There seem to be no nodes available for this specification. Admins have been notified. Please try again later of contact us.') }}
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="w-100"></div>
            
            <div class="col" x-show="selectedLocation != null" x-data="{
                billingPeriodTranslations: {
                    'monthly': '{{ __('per Month') }}',
                    'half-annually': '{{ __('per 6 Months') }}',
                    'quarterly': '{{ __('per 3 Months') }}',
                    'annually': '{{ __('per Year') }}',
                    'weekly': '{{ __('per Week') }}',
                    'daily': '{{ __('per Day') }}',
                    'hourly': '{{ __('per Hour') }}'
                }
            }">
                <div class="products-grid">
                    <template x-for="(product, index) in products" :key="product.id">
                        <div class="product-card" :style="'animation-delay: ' + (index * 0.1) + 's'">
                            <div class="product-header">
                                <h4 class="product-name" x-text="product.name"></h4>
                                <span class="product-limit"
                                      x-text="product.serverlimit > 0
                                          ? product.servers_count + ' / ' + product.serverlimit
                                          : '{{ __('No limit') }}'">
                                </span>
                            </div>

                            <div class="product-specs">
                                <h6>{{ __('Resource Data') }}</h6>
                                <ul class="spec-list">
                                    <li class="spec-item">
                                        <span class="spec-label">
                                            <i class="fas fa-microchip"></i>
                                            {{ __('CPU') }}
                                        </span>
                                        <span class="spec-value" x-text="product.cpu + ' {{ __('vCores') }}'"></span>
                                    </li>
                                    <li class="spec-item">
                                        <span class="spec-label">
                                            <i class="fas fa-memory"></i>
                                            {{ __('Memory') }}
                                        </span>
                                        <span class="spec-value" x-text="product.memory + ' {{ __('MB') }}'"></span>
                                    </li>
                                    <li class="spec-item">
                                        <span class="spec-label">
                                            <i class="fas fa-hdd"></i>
                                            {{ __('Disk') }}
                                        </span>
                                        <span class="spec-value" x-text="product.disk + ' {{ __('MB') }}'"></span>
                                    </li>
                                    <li class="spec-item">
                                        <span class="spec-label">
                                            <i class="fas fa-save"></i>
                                            {{ __('Backups') }}
                                        </span>
                                        <span class="spec-value" x-text="product.backups"></span>
                                    </li>
                                    <li class="spec-item">
                                        <span class="spec-label">
                                            <i class="fas fa-database"></i>
                                            {{ __('MySQL Databases') }}
                                        </span>
                                        <span class="spec-value" x-text="product.databases"></span>
                                    </li>
                                    <li class="spec-item">
                                        <span class="spec-label">
                                            <i class="fas fa-network-wired"></i>
                                            {{ __('Allocations') }}
                                        </span>
                                        <span class="spec-value" x-text="product.allocations"></span>
                                    </li>
                                    <li class="spec-item">
                                        <span class="spec-label">
                                            <i class="fas fa-clock"></i>
                                            {{ __('Billing Period') }}
                                        </span>
                                        <span class="spec-value" x-text="billingPeriodTranslations[product.billing_period]"></span>
                                    </li>
                                    <li class="spec-item">
                                        <span class="spec-label">
                                            <i class="fas fa-coins"></i>
                                            {{ __('Minimum') }} {{ $credits_display_name }}
                                        </span>
                                        <span class="spec-value"
                                            x-text="product.minimum_credits == -1 ? {{ $min_credits_to_make_server }} : product.minimum_credits"></span>
                                    </li>
                                </ul>
                            </div>

                            <div class="product-description" x-show="product.description">
                                <h6>{{ __('Description') }}</h6>
                                <p x-text="product.description"></p>
                            </div>

                            <div class="product-pricing">
                                <div class="pricing-label" x-text="'{{ __('Price') }}' + ' (' + billingPeriodTranslations[product.billing_period] + ')'"></div>
                                <div class="pricing-amount" x-text="product.price + ' {{ $credits_display_name }}'"></div>
                            </div>

                            <div class="product-actions">
                                <button type="button"
                                    :disabled="(product.minimum_credits > user.credits && product.price > user.credits) ||
                                        product.doesNotFit == true ||
                                        product.servers_count >= product.serverlimit && product.serverlimit != 0 ||
                                        submitClicked"
                                    :class="(product.minimum_credits > user.credits && product.price > user.credits) ||
                                        product.doesNotFit == true ||
                                        submitClicked ? 'disabled' : ''"
                                    class="btn btn-primary btn-block" @click="setProduct(product.id);"
                                    x-text="product.doesNotFit == true
                                        ? '{{ __('Server cant fit on this Location') }}'
                                        : (product.servers_count >= product.serverlimit && product.serverlimit != 0
                                            ? '{{ __('Max. Servers with configuration reached') }}'
                                            : (product.minimum_credits > user.credits && product.price > user.credits
                                                ? '{{ __('Not enough') }} {{ $credits_display_name }}!'
                                                : '{{ __('Create server') }}'))">
                                </button>
                                
                                @if (env('APP_ENV') == 'local' || $store_enabled)
                                <template x-if="product.price > user.credits || product.minimum_credits > user.credits">
                                    <a href="{{ route('store.index') }}">
                                        <button type="button" class="btn btn-warning btn-block">
                                            <i class="fas fa-shopping-cart"></i>
                                            {{ __('Buy more') }} {{ $credits_display_name }}
                                        </button>
                                    </a>
                                </template>
                                @endif
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="product" id="product" x-model="selectedProduct">
            <input type="hidden" name="egg_variables" id="egg_variables">
        </form>
        <!-- END FORM -->
    </div>
</section>
<!-- END CONTENT -->

<script>
    function serverApp() {
        return {
            //loading
            loading: false,
            fetchedLocations: false,
            fetchedProducts: false,

            //input fields
            name: null,
            selectedNest: null,
            selectedEgg: null,
            selectedLocation: null,
            selectedProduct: null,
            locationDescription: null,

            //selected objects based on input
            selectedNestObject: {},
            selectedEggObject: {},
            selectedLocationObject: {},
            selectedProductObject: {},

            //values
            user: {!! $user !!},
            nests: {!! $nests !!},
            eggsSave: {!! $eggs !!}, //store back-end eggs
            eggs: [],
            locations: [],
            products: [],

            submitClicked: false,

            /**
             * @description set available eggs based on the selected nest
             * @note called whenever a nest is selected
             * @see selectedNest
             */
            async setEggs() {
                this.fetchedLocations = false;
                this.fetchedProducts = false;
                this.locations = [];
                this.products = [];
                this.selectedEgg = 'null';
                this.selectedLocation = 'null';
                this.selectedProduct = null;
                this.locationDescription = 'null';

                this.eggs = this.eggsSave.filter(egg => egg.nest_id == this.selectedNest)

                //automatically select the first entry if there is only 1
                if (this.eggs.length === 1) {
                    this.selectedEgg = this.eggs[0].id;
                    await this.fetchLocations();
                    return;
                }

                this.updateSelectedObjects()
            },

            setProduct(productId) {
                if (!productId) return

                this.selectedProduct = productId;
                this.updateSelectedObjects();

                let hasEmptyRequiredVariables = this.hasEmptyRequiredVariables(this.selectedEggObject.environment);

                if(hasEmptyRequiredVariables.length > 0) {
                  this.dispatchModal(hasEmptyRequiredVariables);
                } else {
                  document.getElementById('product').value = productId;
                  document.getElementById('serverForm').submit();
                }
            },

            /**
             * @description fetch all available locations based on the selected egg
             * @note called whenever a server configuration is selected
             * @see selectedEg
             */
            async fetchLocations() {
                this.loading = true;
                this.fetchedLocations = false;
                this.fetchedProducts = false;
                this.locations = [];
                this.products = [];
                this.selectedLocation = 'null';
                this.selectedProduct = 'null';
                this.locationDescription = null;

                let response = await axios.get(`{{ route('products.locations.egg') }}/${this.selectedEgg}`)
                    .catch(console.error)

                this.fetchedLocations = true;
                this.locations = response.data

                //automatically select the first entry if there is only 1
                if (this.locations.length === 1 && this.locations[0]?.nodes?.length === 1) {
                    this.selectedLocation = this.locations[0]?.id;

                    await this.fetchProducts();
                    return;
                }

                this.loading = false;
                this.updateSelectedObjects()
            },

            /**
             * @description fetch all available products based on the selected node
             * @note called whenever a node is selected
             * @see selectedLocation
             */
            async fetchProducts() {
                this.loading = true;
                this.fetchedProducts = false;
                this.products = [];
                this.selectedProduct = null;

                let response = await axios.get(
                        `{{ route('products.products.location') }}/${this.selectedEgg}/${this.selectedLocation}`)
                    .catch(console.error)

                this.fetchedProducts = true;

                // TODO: Sortable by user chosen property (cpu, ram, disk...)
                this.products = response.data.sort((p1, p2) => parseInt(p1.price, 10) > parseInt(p2.price, 10) &&
                    1 || -1)

                //divide cpu by 100 for each product
                this.products.forEach(product => {
                    product.cpu = product.cpu / 100;
                })

                //format price to have no decimals if it is a whole number
                this.products.forEach(product => {
                    if (product.price % 1 === 0) {
                        product.price = Math.round(product.price);
                    }
                })

                this.locationDescription = this.locations.find(location => location.id == this.selectedLocation).description ?? null;
                this.loading = false;
                this.updateSelectedObjects()
            },

            /**
             * @description map selected id's to selected objects
             * @note being used in the server info box
             */
            updateSelectedObjects() {
                this.selectedNestObject = this.nests.find(nest => nest.id == this.selectedNest) ?? {}
                this.selectedEggObject = this.eggs.find(egg => egg.id == this.selectedEgg) ?? {}

                this.selectedLocationObject = {};
                this.locations.forEach(location => {
                    if (!this.selectedLocationObject?.id) {
                        this.selectedLocationObject = location.nodes.find(node => node.id == this.selectedLocation) ??
                            {};
                    }
                })

                this.selectedProductObject = this.products.find(product => product.id == this.selectedProduct) ?? {}
            },

            /**
             * @description check if all options are selected
             * @return {boolean}
             */
            isFormValid() {
                if (Object.keys(this.selectedNestObject).length === 0) return false;
                if (Object.keys(this.selectedEggObject).length === 0) return false;
                if (Object.keys(this.selectedLocationObject).length === 0) return false;
                if (Object.keys(this.selectedProductObject).length === 0) return false;
                return !!this.name;
            },

            hasEmptyRequiredVariables(environment) {
                if (!environment) return [];

                return environment.filter((variable) => {
                  const hasRequiredRule = variable.rules?.includes("required");
                  const isDefaultNull = !variable.default_value;

                  return hasRequiredRule && isDefaultNull;
                });
            },

            getLocationInputText() {
                if (this.fetchedLocations) {
                    if (this.locations.length > 0) {
                        return '{{ __('Please select a location ...') }}';
                    }
                    return '{{ __('No location found matching current configuration') }}'
                }
                return '{{ __('---') }}';
            },

            getProductInputText() {
                if (this.fetchedProducts) {
                    if (this.products.length > 0) {
                        return '{{ __('Please select a resource ...') }}';
                    }
                    return '{{ __('No resources found matching current configuration') }}'
                }
                return '{{ __('---') }}';
            },

            getEggInputText() {
                if (this.selectedNest) {
                    return '{{ __('Please select a configuration ...') }}';
                }
                return '{{ __('---') }}';
            },

            getProductOptionText(product) {
                let text = product.name + ' (' + product.description + ')';

                if (product.minimum_credits > this.user.credits) {
                    return '{{ __('Not enough credits!') }} | ' + text;
                }

                return text;
            },

            dispatchModal(variables) {
              Swal.fire({
                title: '{{ __('Required Variables') }}',
                background: 'var(--bg-card)',
                color: 'var(--text-primary)',
                html: `
                  ${variables.map(variable => `
                    <div class="text-left form-group">
                      <div class="d-flex justify-content-between">
                        <label for="${variable.env_variable}">${variable.name}</label>
                        ${variable.description
                          ? `
                            <span>
                              <i data-toggle="tooltip" data-placement="top" title="${variable.description}" class="fas fa-info-circle"></i>
                            </span>
                          `
                          : ''
                        }
                      </div>
                      ${
                        variable.rules.includes("in:")
                          ? (() => {
                            const inValues = variable.rules
                              .match(/in:([^|]+)/)[1]
                              .split(',');
                            return `
                              <select name="${variable.env_variable}" id="${variable.env_variable}" required="required" class="custom-select">
                                  ${inValues.map(value => `
                                      <option value="${value}">${value}</option>
                                  `).join('')}
                              </select>
                            `;
                          })()
                          : `<input id="${variable.env_variable}" name="${variable.env_variable}" type="text" required="required" class="form-control">`
                      }
                      <div id="${variable.env_variable}-error" class="mt-1"></div>
                    </div>
                  `).join('')
                  }
                `,
                confirmButtonText: '{{ __('Submit') }}',
                confirmButtonColor: 'var(--orange-primary)',
                showCancelButton: true,
                cancelButtonText: '{{ __('Cancel') }}',
                cancelButtonColor: 'var(--bg-elevated)',
                showLoaderOnConfirm: true,
                preConfirm: async () => {
                  const filledVariables = variables.map(variable => {
                    const value = document.getElementById(variable.env_variable).value;
                    return {
                        ...variable,
                        filled_value: value
                    };
                  });

                  const response = await fetch('{{ route("servers.validateDeploymentVariables") }}', {
                    method: 'POST',
                    headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                      variables: filledVariables
                    })
                  })

                  if (!response.ok) {
                    const errorData = await response.json();

                    variables.forEach(variable => {
                        const errorContainer = document.getElementById(`${variable.env_variable}-error`);
                        if (errorContainer) {
                            errorContainer.innerHTML = '';
                        }
                    });

                    if (errorData.errors) {
                        Object.entries(errorData.errors).forEach(([key, messages]) => {
                            const errorContainer = document.getElementById(`${key}-error`);
                            if (errorContainer) {
                                errorContainer.innerHTML = messages.map(message => `
                                    <small class="text-danger">${message}</small>
                                `).join('');
                            }
                        });
                    }

                    return false;
                  }

                  return response.json();
                },
                didOpen: () => {
                  $('[data-toggle="tooltip"]').tooltip();
                },
              }).then((result) => {
                if (result.isConfirmed && result.value.success) {
                  document.getElementById('egg_variables').value = JSON.stringify(result.value.variables);
                  document.getElementById('serverForm').submit();
                }
              });
            }
        }
    }

    // Initialize tooltips and popovers
    document.addEventListener('DOMContentLoaded', function() {
        $('[data-toggle="popover"]').popover({
            container: 'body',
            html: true
        });
        
        $('[data-toggle="tooltip"]').tooltip({
            container: 'body'
        });
    });
</script>
@endsection