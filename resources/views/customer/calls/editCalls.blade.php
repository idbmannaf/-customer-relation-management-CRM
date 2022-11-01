@extends('customer.layouts.customerMaster')
@push('title')
   Customer Dashboard | Attendance Report
@endpush

@push('css')
@endpush
@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Edit Assigned Call
            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <form action="{{ route('customer.updateCalls', $call) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <label for="date">Date</label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror" name="date"
                                    value="{{ old('date') ?? \Carbon\Carbon::parse($call->date_time)->format('Y-m-d') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="time">Time</label>
                                <input type="time" class="form-control @error('time') is-invalid @enderror" name="time"
                                    value="{{ old('time') ?? \Carbon\Carbon::parse($call->date_time)->format('H:i') }}">
                            </div>
                        </div>
                    </div>


                    <div class="form-group col-12 col-md-6" id="userField">
                        <label for="customer_office_location">Office Location: <a href="" id="add-new-location" class="btn btn-sm btn-info"><i class="fas fa-plus"></i></a></label>
                        @if ($call->customer_address)

                        <input type="text" name="customer_office_location" class="customer_address_location form-control"  placeholder="Add Address" value="{{$call->customer_address}}">
                        @else
                        <select id="customer_office_location" name="customer_office_location"
                            class="form-control select2 @error('customer_office_location') is-invalid @enderror ">
                            <option value="">Select Customer Office</option>
                            @foreach ($office_location as $office)
                                <option {{ $office->id == $call->customer_location_id ? 'selected' : '' }}
                                    value="{{ $office->id }}"> {{ $office->title }}</option>
                            @endforeach
                        </select>
                        @endif


                    </div>

                    <div class="col-12">
                        <label for="type" class="form-label">Call Type</label>
                        <select name="type" id="type" class="form-control">
                            <option value="">Select Call Type</option>
                            <option {{$call->type == 'service' ? 'selected' : ''}} value="service">Service Call</option>
                            <option {{$call->type == 'warranty' ? 'selected' : ''}} value="warranty">Warranty Call</option>
                            <option {{$call->type == 'amc' ? 'selected' : ''}} value="amc">AMC Call</option>
                            <option {{$call->type == 'installation' ? 'selected' : ''}} value="installation">Installation</option>
                            <option {{$call->type == 'demonstration' ? 'selected' : ''}} value="demonstration">Demonstration</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="purpose_of_visit" class="form-label">Purpose of Visit</label>
                        <textarea name="purpose_of_visit" id="purpose_of_visit" cols="30" rows="2"
                            class="form-control @error('purpose_of_visit') is-invalid @enderror">{{ $call->purpose_of_visit }}</textarea>
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
        // $('.select2').select2();
        $("#employee").change(function() {
            var that = $(this);
            var url = that.attr('data-url');
            var finalUrl = url + "?employee=" + that.val();
            $.ajax({
                url: finalUrl,
                method: "GET",
                success: function(res) {
                    console.log(res.success);
                    if (res.success) {
                        $("#customer").html('');
                        $.each(res.customer, function(i, item) {
                            $("#customer").append(
                                "<option value='" + item.id + "'>" + item.customer_name +
                                "</option>");
                        });
                        $("#customer").trigger('change');
                    } else {
                        $("#customer").html(' ');
                    }

                }
            })
        });
        $("#customer").change(function() {
            var that = $(this);
            var url = that.attr('data-url');
            var finalUrl = url + "?customer=" + that.val();
            $.ajax({
                url: finalUrl,
                method: "GET",
                success: function(res) {
                    console.log(res.success);
                    if (res.success) {
                        $("#customer_office_location").html(res.html);
                    } else {
                        $("#customer_office_location").html(' ');
                    }

                }
            })
        });
        $('#add-new-location').click(function(e) {
                            e.preventDefault()
                            var address =
                                `<input type="text" name="customer_office_location" class="customer_address_location form-control"  placeholder="Add Address">`;
                            $('.customer_address_location').html('');
                            $('.customer_address_location').html(address);


                        })
    </script>
@endpush
