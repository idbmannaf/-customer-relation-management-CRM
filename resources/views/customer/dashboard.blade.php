@extends('customer.layouts.customerMaster')
@section('content')
    <div class="row">
        <div class="col-lg-6 col-6">
            <div class="small-box">
                <div class="inner">
                    <h5><b>{{ $customer->customer_name }}</b></h5>
                    <p>{{ $customer->client_address }}</p>
                    <p>{{ $customer->email }}</p>
                    <p>{{ $customer->mobile }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-6">
            <a href="{{ route('customer.transactionHistory') }}" class="small-box">
                <div class="inner">
                    <h3>Ledger Balance</h3>
                    <p>{{ $customer->ledger_balance }}</p>
                </div>
            </a>

        </div>
        @if ($customer->employee)
            <div class="col-lg-6 col-12">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $customer->employee->company ? $customer->employee->company->name : '' }}</h3>
                        <p>({{ $customer->employee->designation ? $customer->employee->designation->title : '' }})</p>
                        @if ($customer->employee->mobile)
                            <a class="btn btn-success" href="tel:{{ $customer->employee->mobile }}"><i
                                    class="fas fa-phone-volume"></i>{{ $customer->employee->mobile }}</a>
                        @endif
                        @if ($customer->employee->email)
                            <a class="btn btn-success" href="mailto:{{ $customer->employee->email }}"><i
                                    class="fas fa-envelope"></i>{{ $customer->employee->email }}</a>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        {{-- @if ($customer->employee)
            <div class="col-lg-6 col-12">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>Team Member</h3>
                        <p>{{ $customer->employee ? $customer->employee->name : '' }}</p>
                        @if ($customer->employee->mobile)
                            <a class="btn btn-success" href="tel:{{ $customer->employee->mobile }}"><i
                                    class="fas fa-phone-volume"></i>{{ $customer->employee->mobile }}</a>
                        @endif
                        @if ($customer->employee->email)
                            <a class="btn btn-success" href="mailto:{{ $customer->employee->email }}"><i
                                    class="fas fa-envelope"></i>{{ $customer->employee->email }}</a>
                        @endif
                    </div>
                </div>
            </div>
        @endif --}}

        @if ($customer->employee && $customer->employee->teamHead)
            <div class="col-lg-6 col-12">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>Team Head</h3>
                        <p>{{ $customer->employee->teamHead ? $customer->employee->teamHead->name : '' }}</p>
                        @if ($customer->employee->teamHead->mobile)
                            <a class="btn btn-success" href="tel:{{ $customer->employee->teamHead->mobile }}"><i
                                    class="fas fa-phone-volume"></i>{{ $customer->employee->teamHead->mobile }}</a>
                        @endif
                        @if ($customer->employee->teamHead->email)
                            <a class="btn btn-success" href="mailto:{{ $customer->employee->teamHead->email }}"><i
                                    class="fas fa-envelope"></i>{{ $customer->employee->teamHead->email }}</a>
                        @endif
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection
