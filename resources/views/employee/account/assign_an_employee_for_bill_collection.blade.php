@extends('employee.layouts.employeeMaster')
@push('title')
    Ready To Collection List
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info noPring">
            Assing To Collection for Invoice {{ $invoice->id }}

        </div>
        <div class="card-body">

            @include('alerts.alerts')
            @include('employee.account.part.invoice_card')
            @if (!$invoice->hasVisitPlan())
                <div class="card">
                    <div class="card-header">
                        <h4>Assign To Collection</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('employee.customerVisit.store', ['invoice' => $invoice->id]) }}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="service_type" value="collection" id="collection">
                            <input type="hidden" name="approved" value="1" id="approved">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <label for="date">Date</label>
                                            <input type="date" class="form-control @error('date') is-invalid @enderror"
                                                name="date" value="{{ old('date') }}">
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="time">Time</label>
                                            <input type="time" class="form-control @error('time') is-invalid @enderror"
                                                name="time" value="{{ old('time') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-12 col-md-6" id="userField">
                                    <label for="employee">Employee: <i class="fas fa-info"
                                            title="If you Not Select Employee Then You are the Employee of This Customer"></i>
                                    </label>
                                    <select id="employee" name="employee" class="form-control select2">
                                        <option value="0">Select Employee</option>
                                        @foreach ($my_employees as $my_employee)
                                            <option value="{{ $my_employee->id }}">{{ $my_employee->name }}
                                                ({{ $my_employee->employee_id }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('employee')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="form-group col-12 col-md-6" id="userField">
                                    <label for="customer">Customer:</label>
                                    <input type="text" disabled name="" id="" class="form-control"
                                        value="{{ $invoice->customer ? $invoice->customer->customer_name : '' }}">
                                    <input type="hidden" name="customer" value="{{ $invoice->customer_id }}">
                                </div>



                                <div class="form-group col-12 col-md-6" id="location-mf">
                                    <label for="customer_address">Customer Address/Location:</label>
                                    <input type="text" class="form-control" disabled
                                        value="{{ $invoice->customer ? $invoice->customer->client_address : '' }}">
                                    <input type="hidden" name="customer_address"
                                        value="{{ $invoice->customer ? $invoice->customer->client_address : '' }}">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="payment_collection_date">Payment Collection Date</label>
                                    <input type="date"
                                        class="form-control @error('payment_collection_date') is-invalid @enderror "
                                        name="payment_collection_date" value="{{ old('payment_collection_date') }}">
                                </div>
                                {{-- <div class="col-12 col-md-6">
                                    <label for="payment_maturity_date">Payment Maturity Date</label>
                                    <input type="date"
                                        class="form-control @error('payment_maturity_date') is-invalid @enderror "
                                        name="payment_maturity_date" value="{{ old('payment_maturity_date') }}">
                                </div> --}}


                                <div class="col-12">
                                    <label for="purpose_of_visit" class="form-label">Purpose of Visit</label>
                                    <textarea name="purpose_of_visit" id="purpose_of_visit" cols="30" rows="2"
                                        class="form-control @error('purpose_of_visit') is-invalid @enderror"></textarea>
                                </div>

                                <div class="col-12">
                                    {{-- <div class="col-12 col-md-6 text-start pt-2">
                                        <label for=""></label>
                                        <label for="approved" class="form-label"><input type="checkbox" name="approved"
                                                id="approved"> Approved? </label>
                                    </div> --}}
                                    <div class="text-right pt-2">
                                        <input type="submit" class="btn btn-info">
                                    </div>
                                </div>



                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="card shadow">
                    <div class="card-body">Employee ({{ $invoice->assignedBy ? $invoice->assignedBy->name : '' }} -
                        {{ $invoice->assignedBy ? $invoice->assignedBy->id : '' }})Assigned For Collection</div>
                </div>
            @endif

        </div>


    </div>
@endsection
