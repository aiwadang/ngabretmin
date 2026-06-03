@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <ul class="breadcrumb-list  in-section">
                        <li class="breadcrumb-list__item">
                            <a href="{{ route('home') }}" class="breadcrumb-list__item-link">@lang('Home')</a>
                        </li>
                        <li class="breadcrumb-list__item icon"><i class="las la-angle-right"></i></li>
                        <li class="breadcrumb-list__item">
                            <span class="breadcrumb-list__item-text">
                                {{ __($policy?->data_values?->title ?? '') }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="co-12">
                    @php
                        echo $policy?->data_values?->details ?? '';
                    @endphp
                </div>
            </div>
        </div>
    </div>
@endsection
