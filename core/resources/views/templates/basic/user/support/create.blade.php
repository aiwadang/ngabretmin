@extends($activeTemplate . 'layouts.auth')
@section('auth')
    <div class="container">
        <div class="row justify-content-center gy-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-content-center gap-2 flex-wrap">
                    <div>
                        <h6 class="title mb-1">
                            @lang('Create Support Ticket')
                        </h6>

                        <p class="subtitle">
                            @lang('Submit a ticket and describe your issue to get assistance quickly.')
                        </p>
                    </div>
                    <div class="text-end">
                        <a href="{{ route('ticket.index') }}" class="btn  btn--base mb-3">@lang('My Support Ticket')</a>
                    </div>
                </div>


            </div>
            <div class="col-12">
                <div class="card ">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __($pageTitle) }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('ticket.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="form-label">@lang('Subject')</label>
                                    <input type="text" name="subject" value="{{ old('subject') }}" class="form--control"
                                        required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-label">@lang('Priority')</label>
                                    <select name="priority"
                                        class="form-select form--control select2 support-priority-select"
                                        data-minimum-results-for-search="-1" required>
                                        <option value="3">@lang('High')</option>
                                        <option value="2">@lang('Medium')</option>
                                        <option value="1">@lang('Low')</option>
                                    </select>
                                </div>
                                <div class="col-12 form-group">
                                    <label class="form-label">@lang('Message')</label>
                                    <textarea name="message" id="inputMessage" rows="6" class="form--control" required>{{ old('message') }}</textarea>
                                </div>
                                <div class="col-xl-12">
                                    <button type="button" class="btn btn-dark  addAttachment my-2"> <i
                                            class="fas fa-plus"></i> @lang('Add Attachment') </button>
                                    <p class="mb-2 fs-14"><span class="text--info">@lang('Max 5 files can be uploaded | Maximum upload size is ' . convertToReadableSize(ini_get('upload_max_filesize')) . ' | Allowed File Extensions: .jpg, .jpeg, .png, .pdf, .doc, .docx')</span></p>
                                    <div class="row fileUploadsContainer">
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <button class="btn btn--base w-100 my-2" type="submit"><i
                                            class="fa-regular fa-paper-plane"></i> @lang('Submit')
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('style')
    <style>
        .input-group-text:focus {
            box-shadow: none !important;
        }

        .support-priority-select+.select2-container {
            width: 100% !important;
            top: 0;
        }

        .support-priority-select+.select2-container .selection {
            width: 100%;
        }

        .support-priority-select+.select2-container .select2-selection--single {
            height: auto !important;
            min-height: 50px;
            padding: 14px 16px !important;
            border: 1px solid hsl(var(--border-color)) !important;
            border-radius: 14px !important;
            display: flex !important;
            align-items: center;
            transition: 0.2s ease;
        }

        .support-priority-select+.select2-container.select2-container--open .select2-selection--single,
        .support-priority-select+.select2-container.select2-container--focus .select2-selection--single {
            border-color: hsl(var(--base)) !important;
            box-shadow: none !important;
        }

        .support-priority-select+.select2-container .select2-selection__rendered {
            padding-left: 0 !important;
            padding-right: 28px !important;
            color: hsl(var(--heading-color)) !important;
            font-weight: 600;
            line-height: 1.2 !important;
        }

        .support-priority-select+.select2-container .select2-selection__arrow {
            right: 14px !important;
            top: 50% !important;
            transform: translateY(-50%);
            width: 16px !important;
            height: 16px !important;
        }

        .support-priority-select+.select2-container .select2-selection__arrow::after {
            top: 50% !important;
            right: 0 !important;
            transform: translateY(-50%) !important;
            margin: 0 !important;
            line-height: 1 !important;
        }

        .support-priority-select+.select2-container.select2-container--open .select2-selection__arrow {
            right: 14px !important;
            top: 50% !important;
            transform: translateY(-50%);
        }

        .support-priority-select+.select2-container.select2-container--open .select2-selection__arrow::after {
            top: 50% !important;
            right: 0 !important;
            margin: 0 !important;
            line-height: 1 !important;
            transform: translateY(-50%) rotate(180deg) !important;
        }

        .select2-dropdown {
            border: 1px solid hsl(var(--border-color)) !important;
            border-radius: 14px !important;
            overflow: hidden;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
        }

        .select2-results__option {
            padding: 12px 14px;
            font-weight: 500;
        }

        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable,
        .select2-results__option.select2-results__option--selected {
            background: hsl(var(--base) / 0.1) !important;
            color: hsl(var(--heading-color)) !important;
        }
    </style>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.support-priority-select').select2({
                minimumResultsForSearch: -1,
                width: '100%'
            });

            var fileAdded = 0;
            $('.addAttachment').on('click', function() {
                fileAdded++;
                if (fileAdded == 5) {
                    $(this).attr('disabled', true)
                }
                $(".fileUploadsContainer").append(`
                    <div class="col-md-4  removeFileInput">
                        <div class="form-group">
                            <div class="input-group">
                                <input class="form--control form-control" type="file" name="attachments[]" class=" accept=".jpeg,.jpg,.png,.pdf,.doc,.docx" required>
                                <button type="button" class="input-group-text removeFile bg--danger border--danger h-49"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                `)
            });
            $(document).on('click', '.removeFile', function() {
                $('.addAttachment').removeAttr('disabled', true)
                fileAdded--;
                $(this).closest('.removeFileInput').remove();
            });
        })(jQuery);
    </script>
@endpush
