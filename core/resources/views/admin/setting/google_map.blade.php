@extends('admin.layouts.app')
@section('panel')
    <div class="row responsive-row">
        <div class="col-12">
            <div class="alert alert--info d-flex" role="alert">
                <div class="alert__icon">
                    <i class="las la-book"></i>
                </div>
                <div class="alert__content">
                    <p>
                        @lang('To properly configure Google Maps, you need to create two separate API keys: one for server-side (backend) usage and another for client-side (web & mobile apps). Please follow our step-by-step setup guide in the documentation to ensure correct configuration and security best practices.')
                        <br><br>
                        <a href="https://preview.ovosolution.com/ovoride/documentation/#google-map" target="_blank"
                            class="text--primary">
                            @lang('Open Documentation Guide') <i class="fa fa-external-link ms-2"></i>
                        </a>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="alert alert--danger d-flex h-100" role="alert">
                <div class="alert__icon">
                    <i class="las la-server"></i>
                </div>
                <div class="alert__content">
                    <p>
                        @lang('This Google Maps Server API Key is used for backend operations such as geocoding, route calculation, distance matrix, and other server-side requests. It must be restricted by IP address for security and should never be exposed in the frontend.')
                    </p>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="alert alert--danger d-flex h-100" role="alert">
                <div class="alert__icon">
                    <i class="las la-globe"></i>
                </div>
                <div class="alert__content">
                    <p>
                        @lang('The Client API Key is used for browser-based map features such as loading Google Maps, displaying locations, generating routes, and other frontend map functionalities. Since this key is exposed in the browser, applying proper API and domain restrictions is strongly recommended for security purposes.')
                    </p>
                </div>
            </div>
        </div>

        <div class="col-12">
            <form method="POST" enctype="multipart/form-data">
                <x-admin.ui.card>
                    <x-admin.ui.card.body>
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>@lang('Google Maps Server API Key')</label>
                                    <input class="form-control" name="google_maps_api" type="text"
                                        value="{{ gs('google_maps_api') }}" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>@lang('Google Maps Client API Key (Web & Mobile JavaScript)')</label>
                                    <input class="form-control" name="google_maps_client_api_key" type="text"
                                        value="{{ gs('google_maps_client_api_key') }}" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <x-admin.ui.btn.submit />
                            </div>
                        </div>
                    </x-admin.ui.card.body>
                </x-admin.ui.card>
            </form>
        </div>

    </div>
@endsection
