@extends('layouts.admin')
@section('admin')
    <style>
        .card.card-large-icons .card-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 150px;
            border-radius: 3px 0 0 3px;
        }

        .card.card-large-icons {
            display: flex;
            flex-direction: row;
        }

        icon .ion,
        .card.card-large-icons .card-icon .fas,
        .card.card-large-icons .card-icon .far,
        .card.card-large-icons .card-icon .fab,
        .card.card-large-icons .card-icon .fal {
            font-size: 60px;
        }
    </style>
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ $pageTitle ?? 'N/A' }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <div class="card-title">{{ $pageTitle ?? '' }}</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 mb-4">
                                <div class="card card-large-icons shadow border-0">
                                    <div class="card-icon bg-primary text-white">
                                        <i class="fas fa-cog"></i>
                                    </div>
                                    <div class="card-body">
                                        <h4>General</h4>
                                        <p>Change website title, logo, language, RTL, social accounts, design styles,
                                            preloading.</p>
                                        <a href="#" class="card-cta">Change Settings<i
                                                class="fas fa-chevron-right"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-4">
                                <div class="card card-large-icons shadow border-0">
                                    <div class="card-icon bg-primary text-white">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                    <div class="card-body">
                                        <h4>Financial</h4>
                                        <p>Define comission rates, tax, payout, currency, payment gateways, offline payment
                                        </p>
                                        <a href="/admin/settings/financial" class="card-cta">Change Settings<i
                                                class="fas fa-chevron-right"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-4">
                                <div class="card card-large-icons shadow border-0">
                                    <div class="card-icon bg-primary text-white">
                                        <i class="fas fa-wrench"></i>
                                    </div>
                                    <div class="card-body">
                                        <h4>Personalization</h4>
                                        <p>Change page backgrounds, home sections, hero styles, images &amp; texts.</p>
                                        <a href="/admin/settings/personalization/page_background" class="card-cta">Change
                                            Settings<i class="fas fa-chevron-right"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-4">
                                <div class="card card-large-icons shadow border-0">
                                    <div class="card-icon bg-primary text-white">
                                        <i class="fas fa-bell"></i>
                                    </div>
                                    <div class="card-body">
                                        <h4>Notifications</h4>
                                        <p>Assign notification templates to processes.</p>
                                        <a href="/admin/settings/notifications" class="card-cta">Change Settings<i
                                                class="fas fa-chevron-right"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-4">
                                <div class="card card-large-icons shadow border-0">
                                    <div class="card-icon bg-primary text-white">
                                        <i class="fas fa-globe"></i>
                                    </div>
                                    <div class="card-body">
                                        <h4>SEO</h4>
                                        <p>Define SEO title, meta description, and search engine crawl access for each page.
                                        </p>
                                        <a href="/admin/settings/seo" class="card-cta">Change Settings<i
                                                class="fas fa-chevron-right"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-4">
                                <div class="card card-large-icons shadow border-0">
                                    <div class="card-icon bg-primary text-white">
                                        <i class="fas fa-list-alt"></i>
                                    </div>
                                    <div class="card-body">
                                        <h4>Customization</h4>
                                        <p>Define additional CSS &amp; JS codes.</p>
                                        <a href="/admin/settings/customization" class="card-cta text-primary">Change
                                            Settings<i class="fas fa-chevron-right"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-4">
                                <div class="card card-large-icons shadow border-0">
                                    <div class="card-icon bg-primary text-white">
                                        <i class="fas fa-upload"></i>
                                    </div>
                                    <div class="card-body">
                                        <h4>Update App</h4>
                                        <p>Update your platform to the latest version easily</p>
                                        <a href="/admin/settings/update-app" class="card-cta text-primary">Change Settings<i
                                                class="fas fa-chevron-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
