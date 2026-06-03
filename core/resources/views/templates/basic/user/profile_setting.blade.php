@extends($activeTemplate . 'layouts.auth')
@section('auth')
    <div class="driver-dashboard-content">
        <div class="mb-3">
            <h6 class="title mb-1">
                @lang('Profile Settings')
            </h6>

            <p class="subtitle">
                @lang('View and update your personal account information.')
            </p>
        </div>
        <form action="" method="POST" class="driver-dashboard__profile-form driver-profile-form"
            enctype="multipart/form-data">
            @csrf
            <div class="driver-dashboard__profile-thumb">
                <img class="fit-image" id="previewImage"
                    src="{{ getImage(getFilePath('user') . '/' . $user->image, getFileSize('user'), true) }}"
                    alt="img">
                <label for="profileUpload" class="upload-overlay"></label>
                <label for="profileUpload" class="upload-icon">
                    <span class="upload-btn"><i class="las la-camera"></i></span>
                </label>
                <input type="file" id="profileUpload" accept="image/*" name="image" hidden>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="input-group-custom mb-3">
                        <div class="input-group-custom__wrapper">
                            <label class="custom-form-label" for="firstname">@lang('First Name')</label>
                            <input class="input-group-custom__input form--control" type="text"
                                placeholder="@lang('Enter first name')" name="firstname" value="{{ $user->firstname }}" required
                                id="firstname">
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="input-group-custom mb-3">
                        <div class="input-group-custom__wrapper">
                            <label class="custom-form-label" for="lastname">@lang('Last Name')</label>
                            <input class="input-group-custom__input form--control" type="text"
                                placeholder="@lang('Enter last name')" name="lastname" value="{{ $user->lastname }}" required
                                id="lastname">
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="input-group-custom mb-3">
                        <div class="input-group-custom__wrapper">
                            <label class="custom-form-label" for="email">@lang('Email Address')</label>
                            <input class="input-group-custom__input form--control" value="{{ $user->email }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="input-group-custom mb-3">
                        <div class="input-group-custom__wrapper">
                            <label class="custom-form-label" for="phone">@lang('Mobile')</label>
                            <input class="input-group-custom__input form--control" value="{{ $user->mobile }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="input-group-custom mb-3">
                        <div class="input-group-custom__wrapper">
                            <label class="custom-form-label" for="state">@lang('State')</label>
                            <input class="input-group-custom__input form--control" type="text"
                                placeholder="@lang('Enter State')" name="state" value="{{ $user->state ?? '' }}"
                                id="state">
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="input-group-custom mb-3">
                        <div class="input-group-custom__wrapper">
                            <label class="custom-form-label" for="zip">@lang('Zip Code')</label>
                            <input class="input-group-custom__input form--control" type="text"
                                placeholder="@lang('Enter zip code')" name="zip" value="{{ $user->zip ?? '' }}"
                                id="zip">
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="input-group-custom mb-3">
                        <div class="input-group-custom__wrapper">
                            <label class="custom-form-label" for="city">@lang('City')</label>
                            <input class="input-group-custom__input form--control" type="text"
                                placeholder="@lang('Enter city')" name="city" value="{{ $user->city ?? '' }}"
                                id="city">
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="input-group-custom mb-3">
                        <div class="input-group-custom__wrapper">
                            <label class="custom-form-label" for="country">@lang('Country')</label>
                            <input class="input-group-custom__input form--control" value="{{ $user->country_name ?? '' }}"
                                disabled id="country">
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="input-group-custom mb-3">
                        <div class="input-group-custom__wrapper">
                            <label class="custom-form-label" for="address">@lang('Address')</label>
                            <input class="input-group-custom__input form--control" type="text"
                                placeholder="@lang('Enter address')" name="address" value="{{ $user->address ?? '' }}"
                                id="address">
                        </div>
                    </div>
                </div>
            </div>


            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
        </form>
    </div>
    </div>
@endsection


@push('style')
    <style>
        .driver-dashboard__profile-thumb {
            position: relative;
            width: 120px;
            height: 120px;
        }

        .fit-image {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        /* FULL clickable transparent layer */
        .upload-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            border-radius: 50%;
        }

        /* Camera icon */
        .upload-icon {
            position: absolute;
            bottom: 5px;
            right: 5px;
            border: 1px solid hsl(var(--border-color));
            font-size: 14px;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ddd;
            cursor: pointer;
            z-index: 5;
            transition: 0.3s;
            line-height: 1;
        }

        .upload-btn i {
            font-size: 16px;
        }

        .driver-profile-form {

            max-width: 700px;
        }
    </style>
@endpush


@push('script')
    <script>
        document.getElementById('profileUpload').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('previewImage');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
