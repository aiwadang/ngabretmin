@extends($activeTemplate . 'layouts.auth')
@section('auth')
    <div class="container">
        <div class="row justify-content-center gy-4">

            @if (!auth()->user()->ts)
                <div class="col-md-6">
                    <div class="card ">
                        <div class="card-header">
                            <h5 class="card-title mb-0">@lang('Add Your Account')</h5>
                        </div>

                        <div class="card-body">
                            <h6 class="mb-3">
                                @lang('Use the QR code or setup key on your Google Authenticator app to add your account.')
                            </h6>

                            <div class="form-group mx-auto text-center">
                                <img class="mx-auto" src="{{ $qrCodeUrl }}" alt="QR">
                            </div>

                            <div class="form-group">
                                <label class="form-label">@lang('Setup Key')</label>
                                <div class="input-group">
                                    <input type="text" name="key" value="{{ $secret }}"
                                        class="form-control form--control referralURL" readonly>
                                    <button type="button" class="input-group-text copytext" id="copyBoard"> <svg
                                            xmlns="http://www.w3.org/2000/svg" version="1.1"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" x="0"
                                            y="0" viewBox="0 0 24 24" style="enable-background:new 0 0 512 512"
                                            xml:space="preserve" class="">
                                            <g>
                                                <path
                                                    d="M5.452 22h9.096c1.748 0 3.182-1.312 3.406-3h.594A3.456 3.456 0 0 0 22 15.548V5.452A3.456 3.456 0 0 0 18.548 2H9.452A3.456 3.456 0 0 0 6 5.452V6h-.548A3.456 3.456 0 0 0 2 9.452v9.096A3.456 3.456 0 0 0 5.452 22zM8 5.452C8 4.652 8.651 4 9.452 4h9.096c.8 0 1.452.651 1.452 1.452v10.096c0 .8-.651 1.452-1.452 1.452H18V9.452A3.456 3.456 0 0 0 14.548 6H8zm-4 4C4 8.652 4.651 8 5.452 8h9.096c.8 0 1.452.651 1.452 1.452v9.096c0 .8-.651 1.452-1.452 1.452H5.452C4.652 20 4 19.349 4 18.548z"
                                                    fill="CurrentColor" opacity="1" data-original="CurrentColor" class="">
                                                </path>
                                            </g>
                                        </svg> </button>
                                </div>
                            </div>

                            <label><i class="fas fa-info-circle"></i> @lang('Help')</label>
                            <p class="fs-14">@lang('Google Authenticator is a multifactor app for mobile devices. It generates timed codes used during the 2-step verification process. To use Google Authenticator, install the Google Authenticator application on your mobile device.') <a class="text--base"
                                    href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en"
                                    target="_blank">@lang('Download')</a></p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-md-6">

                @if (auth()->user()->ts)
                    <div class="card ">
                        <div class="card-header">
                            <h5 class="card-title mb-0">@lang('Disable 2FA Security')</h5>
                        </div>
                        <form action="{{ route('user.twofactor.disable') }}" method="POST">
                            <div class="card-body">
                                @csrf
                                <input type="hidden" name="key" value="{{ $secret }}">
                                <div class="form-group">
                                    <label class="form-label">@lang('Google Authenticator OTP')</label>
                                    <input type="text" class="form--control" name="code" required>
                                </div>
                                <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="card ">
                        <div class="card-header">
                            <h5 class="card-title mb-0">@lang('Enable 2FA Security')</h5>
                        </div>
                        <form action="{{ route('user.twofactor.enable') }}" method="POST">
                            <div class="card-body">
                                @csrf
                                <input type="hidden" name="key" value="{{ $secret }}">
                                <div class="form-group">
                                    <label class="form-label">@lang('Google Authenticator OTP')</label>
                                    <input type="text" class="form--control" name="code" required>
                                </div>
                                <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .copied::after {
            background-color: #{{ gs('base_color') }};
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('#copyBoard').on('click', function() {
                var copyText = document.getElementsByClassName("referralURL");
                copyText = copyText[0];
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                /*For mobile devices*/
                document.execCommand("copy");
                copyText.blur();
                this.classList.add('copied');
                setTimeout(() => this.classList.remove('copied'), 1500);
            });
        })(jQuery);
    </script>
@endpush
