@extends($activeTemplate . 'layouts.auth')
@section('auth')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="show-filter mb-3 text-end">
                    <button type="button" class="btn btn--base showFilterBtn "><i class="las la-filter"></i>
                        @lang('Filter')</button>
                </div>
                <div class="card custom--card responsive-filter-card mb-4">
                    <div class="card-body">
                        <form>
                            <div class="d-flex flex-wrap gap-4">
                                <div class="flex-grow-1">
                                    <label class="form-label">@lang('Transaction Number')</label>
                                    <input type="search" name="search" value="{{ request()->search }}"
                                        class="form--control">
                                </div>
                                <div class="flex-grow-1 select2-parent">
                                    <label class="form-label d-block">@lang('Type')</label>
                                    <select name="trx_type" class="form-select form--control select2"
                                        data-minimum-results-for-search="-1">
                                        <option value="">@lang('All')</option>
                                        <option value="+" @selected(request()->trx_type == '+')>@lang('Plus')</option>
                                        <option value="-" @selected(request()->trx_type == '-')>@lang('Minus')</option>
                                    </select>
                                </div>
                                <div class="flex-grow-1 select2-parent">
                                    <label class="form-label d-block">@lang('Remark')</label>
                                    <select class="form-select form--control select2-basic"
                                        data-minimum-results-for-search="-1" name="remark">
                                        <option value="">@lang('All')</option>
                                        @foreach ($remarks as $remark)
                                            <option value="{{ $remark->remark }}" @selected(request()->remark == $remark->remark)>
                                                {{ __(keyToTitle($remark->remark)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex-grow-1 align-self-end">
                                    <button class="btn btn--base w-100"><i class="las la-filter"></i>
                                        @lang('Filter')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card  custom--card">
                    <div class="card-body p-0">
                        <div class="table-responsive table--responsive--xl">
                            <table class="table ">
                                <thead>
                                    <tr>
                                        <th>@lang('Trx')</th>
                                        <th>@lang('Transacted')</th>
                                        <th>@lang('Amount')</th>
                                        <th>@lang('Charge')</th>
                                        <th>@lang('Post Balance')</th>
                                        <th>@lang('Detail')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $trx)
                                        <tr>
                                            <td>
                                                <strong>{{ $trx->trx }}</strong>
                                            </td>
                                            <td>
                                                <div class="text-end text-md-center">
                                                    {{ showDateTime($trx->created_at) }}<br>{{ diffForHumans($trx->created_at) }}
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <span
                                                        class="fw-bold @if ($trx->trx_type == '+') text--success @else text--danger @endif">
                                                        {{ $trx->trx_type }} {{ showAmount($trx->amount) }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                {{ showAmount($trx->charge) }}
                                            </td>
                                            <td>
                                                {{ showAmount($trx->post_balance) }}
                                            </td>
                                            <td>{{ __($trx->details) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center empty-message-row" colspan="100%">
                                                <div class="text--muted text-center">
                                                    <div class="p-4">
                                                        <img src="{{ asset('assets/images/empty_box.png') }}"
                                                            class="empty-message">
                                                        <h6 class="d-block mb-2 text--muted">@lang('Data Not Found')</h6>
                                                        <span class="d-block fs-14">@lang('There are no available data to display on this table at the moment.')</span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if ($transactions->hasPages())
                        <div class="card-footer">
                            {{ paginateLinks($transactions) }}
                        </div>
                    @endif
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
        .responsive-filter-card .select2-container:has(.select2-selection--single) {
            width: 100% !important;
            top: 0;
        }

        .responsive-filter-card .select2-container .selection {
            width: 100%;
        }

        .responsive-filter-card .select2-container--default .select2-selection--single {
            background-color: hsl(var(--white));
            border: 1px solid hsl(var(--border-color));
            border-radius: 12px !important;
            height: auto !important;
            min-height: 49px;
            padding: 14px 15px !important;
            display: flex;
            align-items: center;
        }

        .responsive-filter-card .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: hsl(var(--heading-color));
            line-height: 1.2 !important;
            padding-left: 0 !important;
            padding-right: 24px !important;
        }

        .responsive-filter-card .select2-container--default.select2-container--open .select2-selection--single,
        .responsive-filter-card .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: hsl(var(--base)) !important;
            box-shadow: none !important;
        }

        .responsive-filter-card .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 50% !important;
            right: 12px !important;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
        }

        .responsive-filter-card .select2-container--default .select2-selection--single .select2-selection__arrow::after {
            top: 50% !important;
            right: 0 !important;
            transform: translateY(-50%) !important;
            margin: 0 !important;
            line-height: 1 !important;
        }
    </style>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            'use strict';

            $('.select2, .select2-basic').each(function() {
                const $select = $(this);
                const $dropdownParent = $select.closest('.select2-parent');

                $select.select2({
                    dropdownParent: $dropdownParent.length ? $dropdownParent : $(document.body),
                    minimumResultsForSearch: $select.data('minimum-results-for-search') ?? 0
                });
            });

            $('.showFilterBtn').on('click', () => $('.responsive-filter-card').toggleClass('show-filter'));

        })(jQuery);
    </script>
@endpush
