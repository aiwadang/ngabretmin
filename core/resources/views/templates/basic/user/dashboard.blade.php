@extends($activeTemplate . 'layouts.auth')
@section('auth')
    <div class="panel">
        <div class="grid">
            <a href="{{ route('user.activity') }}" class="widget widget--blue">
                <span class="widget__label">@lang('Total Rides')</span>
                <span class="widget__value">{{ $widget['totalRides'] }}</span>
            </a>
            <a href="{{ route('user.activity') }}?status={{ Status::RIDE_ACTIVE }}" class="widget widget--purple">
                <span class="widget__label">@lang('Active Rides')</span>
                <span class="widget__value">{{ $widget['activeRides'] }}</span>
            </a>
            <a href="{{ route('user.activity') }}?status={{ Status::RIDE_COMPLETED }}" class="widget widget--green">
                <span class="widget__label">@lang('Completed Rides')</span>
                <span class="widget__value">{{ $widget['completedRides'] }}</span>
            </a>
            <a href="{{ route('user.activity') }}?status={{ Status::RIDE_CANCELED }}" class="widget widget--red">
                <span class="widget__label">@lang('Cancelled Rides')</span>
                <span class="widget__value">{{ $widget['canceledRides'] }}</span>
            </a>
        </div>

        <div class="grid">
            <a class="widget widget--teal" href="{{ route('user.deposit.history') }}">
                <span class="widget__label">@lang('Total Paid')</span>
                <span
                    class="widget__value">{{ gs('cur_sym') }}{{ showAmount($widget['totalAmount'], currencyFormat: false) }}</span>
            </a>
            <a class="widget widget--sunset" href="{{ route('user.activity') }}">
                <span class="widget__label">@lang('Total Distance')</span>
                <span class="widget__value">
                    {{ getAmount($widget['totalDistance']) }}
                    @if (gs('distance_unit') == Status::MILE_UNIT)
                        <span title="@lang('Miles')">@lang('MI')</span>
                    @else
                        <span title="@lang('Kilometer')">@lang('KM')</span>
                    @endif
                </span>
            </a>
            <a class="widget widget--dark" href="{{ route('user.activity') }}">
                <span class="widget__label">@lang('Avg. Ride Time')</span>
                <span class="widget__value">{{ getAmount($widget['avgRideTime'] ?? 0) }} @lang('MIN')</span>
            </a>
            <a class="widget widget--orange" href="{{ route('user.transactions') }}">
                <span class="widget__label">@lang('Total Transaction')</span>
                <span class="widget__value">{{ $widget['totalTransaction'] }}</span>
            </a>
        </div>

        <div class="row-split">
            <div class="feedback-card">
                <h2 class="feedback-card__title">@lang('User Feedback Analysis')</h2>
                <div class="chart">
                    <div class="chart__item">
                        <div class="chart__header">
                            <span>@lang('Positive Feedback')</span>
                            <span>{{ getAmount($cardData['positiveReviewPercentage'], 0) }}%</span>
                        </div>
                        <div class="chart__bar-container">
                            <div class="chart__bar chart__bar--positive"></div>
                        </div>
                    </div>
                    <div class="chart__item mt-3">
                        <div class="chart__header">
                            <span>@lang('Negative Feedback')</span>
                            <span>{{ getAmount($cardData['negativeReviewPercentage'], 0) }}%</span>
                        </div>
                        <div class="chart__bar-container">
                            <div class="chart__bar chart__bar--negative"></div>
                        </div>
                    </div>
                </div>
                <div class="analysis-show">
                    <div>
                        <p class="analysis-show-rate">
                            {{ $cardData['avgRating'] }}</p>
                        <p class="analysis-show-title">
                            @lang('Avg Rating')
                        </p>
                    </div>
                    <div class="analysis-show-content">
                        <p class="label">
                            {{ $cardData['totalReviews'] }}
                            @lang('Reviews')</p>
                        <p class="value">
                            +{{ $cardData['totalCurrentMonthReview'] }}% @lang('this month')</p>
                    </div>
                </div>
            </div>

            <div class="table-widget">
                <h2 class="feedback-card__title">@lang('Recent Transactions')</h2>
                <table class="table  table--responsive--sm">
                    <thead>
                        <tr>
                            <th>@lang('Trx')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Charge')</th>
                            <th>@lang('Post Balance')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cardData['recentTransactions'] as $recentTransaction)
                            <tr>
                                <td class="fw-semibold">{{ $recentTransaction->trx }}</td>
                                <td class="fw-bold">
                                    <div>
                                        <span
                                            class="fw-bold @if ($recentTransaction->trx_type == '+') text--success @else text--danger @endif">
                                            {{ $recentTransaction->trx_type }}
                                            {{ showAmount($recentTransaction->amount) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="payment-pill">{{ showAmount($recentTransaction->charge) }}</td>
                                <td>{{ showAmount($recentTransaction->post_balance) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center empty-message-row" colspan="100%">
                                    <div class="text--muted text-center">
                                        <div class="p-4">
                                            <img src="{{ asset('assets/images/empty_box.png') }}" class="empty-message">
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
    </div>
@endsection

@push('style')
    <style>
        :root {
            --bg-gradient: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            --panel-bg: rgba(255, 255, 255, 0.95);
            --text-dark: #2d3436;
            --text-muted: #636e72;
            --grad-blue: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            --grad-green: linear-gradient(135deg, #0ba360 0%, #3cba92 100%);
            --grad-red: linear-gradient(135deg, #ff0844 0%, #ffb199 100%);
            --grad-purple: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --grad-teal: linear-gradient(135deg, #13f1fc 0%, #0470dc 100%);
            --grad-orange: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --grad-dark: linear-gradient(135deg, #2c3e50 0%, #000000 100%);
            --grad-pink: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%, #fecfef 100%);
            --grad-sunset: linear-gradient(135deg, #fa709a 0%, #fee140 100%);

            --shadow-soft: 0 10px 30px rgba(0, 0, 0, 0.08);
            --radius: 24px;
        }

        .analysis-show {
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .analysis-show-rate {
            font-size: 2.2rem;
            font-weight: 800;
            color: #0ca678;
        }

        .analysis-show-title {
            font-size: 0.8rem;
            color: #adb5bd;
            text-transform: uppercase;
            font-weight: 700;
        }

        .analysis-show-content {
            flex: 1 text-align: right;
        }

        .analysis-show-content .label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #495057;
        }

        .analysis-show-content .value {
            font-size: 0.75rem;
            color: #0ca678;
            font-weight: 700;
        }

        .panel {
            width: 100%;
            max-width: 1100px;
            background: var(--panel-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow-soft);
            padding: 24px;
            overflow: hidden;
        }


        /* Widgets Grid */
        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        @media (max-width: 1399px) {
            .grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }

        /* Gradient Widget Component */
        .widget {
            padding: 25px;
            border-radius: 20px;
            color: white;
            transition: transform 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .widget:hover {
            transform: translateY(-5px);

            * {
                color: hsl(var(--white)) !important;
            }
        }

        .widget::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .widget__label {
            font-size: 0.85rem;
            font-weight: 600;
            opacity: 0.9;
            text-transform: uppercase;
            margin-bottom: 10px;
            display: block;
        }

        .widget__value {
            font-size: 2rem;
            font-weight: 800;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            letter-spacing: -1px;
            line-height: 1.2;
        }

        .widget--teal .widget__value,
        .widget--sunset .widget__value {
            font-size: 1.75rem;
        }

        .widget--blue {
            background: var(--grad-blue);
        }

        .widget--green {
            background: var(--grad-green);
        }

        .widget--red {
            background: var(--grad-red);
        }

        .widget--purple {
            background: var(--grad-purple);
        }

        .widget--teal {
            background: var(--grad-teal);
        }

        .widget--orange {
            background: var(--grad-orange);
        }

        .widget--dark {
            background: var(--grad-dark);
        }

        .widget--sunset {
            background: var(--grad-sunset);
        }

        /* Secondary Row */
        .row-split {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 25px;
            margin-top: 25px;
        }

        @media (max-width: 1399px) {
            .row-split {
                grid-template-columns: 1fr;
            }
        }

        /* Feedback Chart Widget */
        .feedback-card {
            background: white;
            padding: 25px;
            border-radius: 20px;
            border: 1px solid #f0f0f0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }

        .feedback-card__title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: var(--text-dark);
        }

        /* CSS Only Chart */
        .chart {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .chart__item {
            width: 100%;
        }

        .chart__header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .chart__bar-container {
            height: 12px;
            background: #f1f3f5;
            border-radius: 6px;
            overflow: hidden;
        }

        .chart__bar {
            height: 100%;
            border-radius: 6px;
            transition: width 1s ease-out;
        }

        .chart__bar--positive {
            background: var(--grad-green);
            width: {{ $cardData['positiveReviewPercentage'] }}%;
        }

        .chart__bar--negative {
            background: var(--grad-red);
            width: {{ $cardData['negativeReviewPercentage'] }}%;
        }

        /* Transaction Table */
        .table-widget {
            background-color: hsl(var(--white));
            padding: 25px;
            border-radius: 20px;
            border: 1px solid #f0f0f0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            text-align: left;
            padding: 12px 15px;
            font-size: 0.8rem;
            text-transform: uppercase;
            color: var(--text-muted);
            border-bottom: 1px solid #f5f5f5;
        }

        .table td {
            padding: 15px;
            font-size: 0.9rem;
            color: var(--text-dark);
            border-bottom: 1px solid #f9f9f9;
        }

        .table tr:last-child td {
            border: none;
        }

        .status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .status--completed {
            background: #e6fcf5;
            color: #0ca678;
        }

        .status--pending {
            background: #fff4e6;
            color: #f76707;
        }

        .payment-pill {
            font-weight: 600;
            color: #495057;
            font-size: 0.85rem;
        }

        /* Top Drivers List */
        .driver-list {
            margin-top: 30px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        @media (max-width: 1399px) {
            .driver-list {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 991px) {
            .driver-list {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 991px) {
            .table thead tr th {
                padding: 9px 5px;
            }

            .table tbody tr td {
                padding: 9px 5px;
            }
        }

        .driver-card {
            background: white;
            padding: 20px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            border: 1px solid #f0f0f0;
        }

        .driver-card__avatar {
            width: 50px;
            height: 50px;
            border-radius: 15px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: var(--text-muted);
        }

        .driver-card__info {
            flex-grow: 1;
        }

        .driver-card__name {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .driver-card__meta {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .driver-card__rating {
            color: #fab005;
            font-weight: 800;
            font-size: 0.85rem;
        }

        .table thead tr th {
            background: transparent;
            border-bottom: 1px solid hsl(var(--border-color) / 0.5);
            font-weight: 600;
            color: hsl(var(--text-muted));
        }

        .table tbody tr td:first-child {
            border-left: unset !important;
        }

        .table tbody tr td:last-child {
            border-right: unset !important;
        }
    </style>
@endpush
