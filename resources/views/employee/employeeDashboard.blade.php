@extends('employee.layouts.employeeMaster')
@push('css')
    <style>
        .borderDanger {
            border: 1px solid red;
        }

        .borderSuccess {
            border: 1px solid green;
        }
    </style>
@endpush
@section('content')
    <div class="card shadow">
        <div class="card-body text-center">
            @if ($employee->team_admin)
                <h1>Team Admin Of {{ $employee->company->name }}</h1>
            @else
                <h1>Team Member Of {{ $employee->company->name }}</h1>
            @endif
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <b>My Cash: </b> {{ $myCash }}
        </div>
    </div>

    @if (Agent::isMobile())
        @include('employee.part.mobileView')
    @else
        @include('employee.part.webView')
    @endif


<div class="card">
    <div class="card-header">
        <h3 class="text-center">Visit Plans</h3>
        <div class="card-body">
            @foreach ($visitPlans as $visit)
                <a href="{{ route('employee.customerVisits', $visit) }}">
                    <div class="card shadow {{ $visit->visited_at ? 'borderSuccess' : 'borderDanger' }} ">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                {{-- <b>
                                @if ($visit->type == 'weekly')
                                    <span class="text-danger">Weekly</span>
                                @elseif($visit->type == 'monthly')
                                    <span class="text-success">Monthly</span>
                                @else
                                <span class="text-warning">Yearly</span>
                                @endif
                            </b> --}}
                                <b>
                                    <i class="fas fa-date fa-2x"></i> {{ $visit->date_time }}
                                </b>
                                @if ($visit->invoice_id)
                                    <b class="text-success">Collection</b>
                                @endif
                                <b>
                                    @if ($visit->visited_at)
                                        <span class="text-success"> <i class="fas fa-check fa-2x"></i></span>
                                    @else
                                        <span class="text-danger"> <i class="fas fa-times fa-2x"></i></span>
                                    @endif
                                </b>
                            </div>
                            <br>
                            <div class="d-flex justify-content-between flex-wrap">
                                <div>
                                    <span
                                        class="{{ auth()->user()->employee->id == $visit->employee_id ? 'text-danger' : '' }}"><b>Employee:</b>{{ $visit->employee->name }}
                                        ({{ $visit->employee->employee_id }})
                                    </span> <br>
                                    <span><b>Customer Name:</b>{{ $visit->customer->customer_name }}</span> <br>
                                    <span><b>Company:</b>{{ $visit->customer->company->name }}</span> <br>
                                    <span><b>Customer
                                            Address:</b>{{ $visit->customer_address ? $visit->customer_address : ($visit->customer ? $visit->customer->client_address : '') }}</span>
                                    <br>

                                </div>
                                <div>
                                    <span><b>Phone:</b>{{ $visit->customer->phone }}</span> <br>
                                    <span><b>Mobile:</b>{{ $visit->customer->mobile }}</span> <br>
                                    <span><b>Designation:</b>{{ $visit->customer->designation }}</span> <br>
                                    @if ($visit->call)
                                        <span><b>Service Address</b> {{ $visit->call->service_address }}</span>
                                        @else
                                        <span><b>Service Address</b> {{ $visit->service_address }}</span>

                                    @endif

                                </div>
                                <div>
                                    <span><b>Purpose Of Visit:</b>{{ $visit->purpose_of_visit }}</span> <br>
                                    <span><b>Payment Collection
                                            Date:</b>{{ $visit->payment_collection_date }}</span> <br>
                                    <span><b>Payment Maturity Date:</b>{{ $visit->payment_maturity_date }}</span>
                                    <br>
                                </div>
                            </div>

                        </div>
                    </div>
                </a>
            @endforeach
            <div class="text-center">
                {{ $visitPlans->render() }}
            </div>
        </div>

    </div>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="text-center">Service Calls</h3>
        <div class="card-body">
            @foreach ($service_calls as $call)
                <div class="card">
                    <a href="{{ route('employee.calls', ['call' => $call]) }}"
                        class="card-body d-flex justify-content-between flex-wrap">
                        <b>Date Time:{{ $call->date_time }} </b>
                        <p><b>Customer: </b>{{ $call->customer ? $call->customer->customer_name : '' }}</p>
                        <p> <b>Customer Address: </b>{{ $call->customer_address }}</p>
                        <p><b>Service Address: </b>{{ $call->service_address }}</p>
                        <p><b>Employee: </b>{{ $call->employee ? $call->employee->name : '' }}</p>
                        <p><b>Purpose Of Visit: </b>{{ $call->purpose_of_visit }}</p>
                    </a>
                </div>
            @endforeach

            <div class="text-center">
                {{ $service_calls->render() }}
            </div>
        </div>

    </div>
</div>
@endsection
