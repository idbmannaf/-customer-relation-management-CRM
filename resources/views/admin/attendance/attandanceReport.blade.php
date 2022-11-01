@extends('admin.layouts.adminMaster')
@push('title')
    | Admin Dashboard | Attendance Report
@endpush

@push('css')
    <link href="{{ asset('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css') }}"
        rel="stylesheet" />
    <link rel="stylesheet"
        href="{{ asset('https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css') }}">
@endpush
@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Attendance Report
                {{-- <a href="{{ route('admin.company.create') }}" class="btn btn-danger"><i
                        class="fas fa-plus"></i></a> --}}
            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <form action="{{ route('admin.attendanceReport', 'filter') }}" method="GET">
                <div class="row py-2">
                    <div class="col-12 col-md-3">
                      From:  <input type="date" name="s_date" value="{{ $input ? $input['s_date'] : old('s_date') }}" required
                            class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        To:
                        <input type="date" name="e_date" value="{{ $input ? $input['e_date'] : old('e_date') }}" required
                            class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        Employee:
                        <select id="user" name="employee"
                            class="form-control select2-container step2-select select2 @error('employee') is-invalid @enderror"
                            data-placeholder=" Employee ID, Name"
                            data-ajax-url="{{ route('admin.selectNewRole', ['type' => 'only_employee']) }}"
                            data-ajax-cache="true" data-ajax-dataType="json" data-ajax-delay="200" style="width: 100%;">
                            @if (isset($employee))
                                <option value="{{ $employee->id }}" selected>
                                    {{ $employee->name }}({{ $employee->employee_id }})</option>
                            @endif
                            <option>{{ old('user') }}</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <div>&nbsp;</div>
                        <input type="submit" class="btn btn-info" value="Submit">
                    </div>
                </div>
            </form>

            @if (isset($type) && $type == 'filter')
                @if (isset($input['s_date']) && isset($input['e_date']))
                    @include('admin.attendance.ajax.attendanceList')
                @endif
            @endif


        </div>
    </div>
@endsection



@push('js')
    <script src="{{ asset('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                minimumInputLength: 1,
                ajax: {
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        // alert(data[0].s);
                        var data = $.map(data, function(obj) {
                            obj.id = obj.id || obj.username;
                            return obj;
                        });
                        var data = $.map(data, function(obj) {
                            obj.text = obj.text || obj.name + "(" + obj.employee_id + ")";
                            return obj;
                        });
                        return {
                            results: data,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    }
                },
            });
        });
    </script>
@endpush
