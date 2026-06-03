@extends($activeTemplate . 'layouts.auth')
@section('auth')
    <div class="container">
        <div class="row justify-content-center gy-4">
            <div class="col-12">
                <div class="d-flex justify-content-between flex-wrap gap-2">
                    <form class="table-search no-submit-loader">
                        <div class="input-group input--group">
                            <input name="search" value="{{ request('search') }}" type="text"
                                class="form--control form-control" placeholder="@lang('Ticket Number')">
                            <button class="input-group-text btn--base" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </form>
                    <a href="{{ route('ticket.open') }}" class="btn  btn--base mb-2"> <i class="fas fa-plus"></i>
                        @lang('New Ticket')</a>
                </div>
            </div>
            <div class="col-md-12">
                <div class="table-responsive table--responsive--lg">
                    <table class="table ">
                        <thead>
                            <tr>
                                <th>@lang('Subject')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Priority')</th>
                                <th>@lang('Last Reply')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($supports as $support)
                                <tr>
                                    <td> <a href="{{ route('ticket.view', $support->ticket) }}" class="fw-bold">
                                            [@lang('Ticket')#{{ $support->ticket }}] {{ __($support->subject) }} </a>
                                    </td>
                                    <td>
                                        @php echo $support->statusBadge; @endphp
                                    </td>
                                    <td>
                                        @if ($support->priority == Status::PRIORITY_LOW)
                                            <span class="badge badge--dark">@lang('Low')</span>
                                        @elseif($support->priority == Status::PRIORITY_MEDIUM)
                                            <span class="badge  badge--warning">@lang('Medium')</span>
                                        @elseif($support->priority == Status::PRIORITY_HIGH)
                                            <span class="badge badge--danger">@lang('High')</span>
                                        @endif
                                    </td>
                                    <td>{{ diffForHumans($support->last_reply) }} </td>

                                    <td>
                                        <a href="{{ route('ticket.view', $support->ticket) }}"
                                            class="btn btn--base btn--sm">
                                            <i class="la  la-eye"></i> @lang('View Ticket')
                                        </a>
                                    </td>
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
                {{ paginateLinks($supports) }}
            </div>
        </div>
    </div>
@endsection


@push('style')
    <style>
        .input--group .input-group-text {
            padding: 18px 14px;
        }
    </style>
@endpush
