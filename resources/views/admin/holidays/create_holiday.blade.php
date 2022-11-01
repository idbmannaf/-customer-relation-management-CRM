@extends('admin.layouts.adminMaster')
@push('title')
    Create Holidays
@endpush


@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            Add New Holidays
        </div>
        <div class="card-body">
@include('alerts.alerts')
                <form action="{{ route('admin.holiday.store') }}" method="POST">
                    @csrf
                    <div class="showData">
                    <div class="row">
                        <div class="col-12 col-md-8 m-auto">
                            <div class="row">
                                <div class="col-12 col-md-5">
                                    <input type="date" name="date[]" class='date form-control'>
                                    <span class="text-danger dateError"></span>
                                </div>
                                <div class="col-12 col-md-5">
                                    <input type="text" name="purpose[]" class='purpose form-control'>
                                </div>
                                <div class="col-12 col-md-2">
                                    <a class="addTempHoliday holiday btn btn-success form-control"><i
                                            class="fas fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="row pt-4">
                        <div class="col-12 col-md-2 m-auto">
                            <input type="submit" class="form-control btn btn-warning">
                        </div>
                    </div>
                </form>

        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).on('click', '.addTempHoliday', function() {
            var that = $(this);
            var date = that.closest('.row').find('.date').val();
            var purpose = that.closest('.row').find('.purpose').val();
            if (date == '' || date == null) {
                that.closest('.row').find('.dateError').text('Select Date');
                return;
            }
            var html = `<div class="row pt-2">
                <div class="col-12 col-md-8 m-auto">
                   <div class="row ">
                    <div class="col-12 col-md-5">
                        <input type="date" name="date[]" value="${date}" class='date form-control'>
                    </div>
                    <div class="col-12 col-md-5">
                        <input type="text" value="${purpose}"  name="purpose[]" class='purpose form-control'>
                    </div>
                    <div class="col-12 col-md-2">
                        <a class="deleteTempHoliday btn btn-danger holiday form-control"><i class="fas fa-trash"></i></a>
                    </div>
                   </div>
                </div>
            </div>`;

            that.closest('.showData').append(html)
            that.closest('.row').find('.date').val('');
            that.closest('.row').find('.purpose').val('');
        })
        $(document).on('click', '.deleteTempHoliday', function() {
            var that = $(this);
            var date = that.closest('.row').remove();
        })
    </script>
@endpush
