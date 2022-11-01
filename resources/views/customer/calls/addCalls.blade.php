@extends('customer.layouts.customerMaster')
@push('title')
    customer Dashboard | Attendance Report
@endpush

@push('css')
@endpush
@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Assign New Call/Task/Complain
                {{-- <a href="{{ route('employee.addCalls') }}" class="btn btn-danger"><i class="fas fa-plus"></i></a> --}}
            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <form action="{{ route('customer.addCalls') }}" method="POST">
                @csrf
                <input type="hidden" name="customer" value="{{ $customer->id }}">
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
                        <label for="customer_office_location">Office Location: <a href="" id="add-new-location"
                                class="btn btn-sm btn-info"><i class="fas fa-plus"></i></a></label>
                        <div class="customer_address_location">
                            <select id="customer_office_location" name="customer_office_location"
                                class="form-control select2 @error('customer_office_location') is-invalid @enderror ">
                                <option value="">Select Office</option>
                                @foreach ($office_location as $office)
                                    <option value="{{ $office->title }}">{{ $office->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="type" class="form-label">Call Type</label>
                        <select name="type" id="type" class="form-control">
                            <option value="">Select Call Type</option>
                            <option value="service">Service Call</option>
                            <option value="warranty">Warranty Call</option>
                            <option value="amc">AMC Call</option>
                            <option value="installation">Installation</option>
                            <option value="demonstration">Demonstration</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="purpose_of_visit" class="form-label">Purpose of Visit</label>
                        <textarea name="purpose_of_visit" id="purpose_of_visit" cols="30" rows="2"
                            class="form-control @error('purpose_of_visit') is-invalid @enderror"></textarea>
                    </div>



                    <div class="col-12">
                        <div class="text-right pt-2">
                            <input type="submit" class="btn btn-info">
                        </div>
                    </div>



                </div>
            </form>
        </div>
    </div>
@endsection



@push('js')
    <script>
        $('#add-new-location').click(function(e) {
            e.preventDefault()
            var address =
                `<input type="text" name="customer_office_location" class="customer_address_location form-control"  placeholder="Add Address">`;
            $('.customer_address_location').html('');
            $('.customer_address_location').html(address);


        })
    </script>
@endpush
