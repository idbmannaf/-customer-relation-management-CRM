@extends('employee.layouts.employeeMaster')
@push('title')
    {{-- {{ $unused->product_name }} Assign To Team Member For {{ ucfirst($status) }} --}}
@endpush
@push('css')
    <link href="{{ asset('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet"
        href="{{ asset('https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css') }}">
@endpush
@section('content')
    <div class="card shadow">
        <div class="card-header bg-info noPring">
            {{ $unused->product_name }} Assign To Team Member {{ ucfirst($status) }}

        </div>

        @include('alerts.alerts')
        <div class="table responsive">
            <table class="table table-bordered table-sm text-nowrap">
                <thead>
                    <tr>
                        <th>Assigned To</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Repair Status</th>
                        <th>Recharge Status</th>
                        <th>Unit Price</th>
                        <th>Final Price</th>
                    </tr>
                </thead>
                <tbody>


                    <td>{{ $unused->assignedTo ? $unused->assignedTo->name : '' }}</td>
                    <td>{{ $unused->product_name }}</td>
                    <td>{{ $unused->quantity }}</td>
                    <td>
                        @if ($unused->status == 'repair')
                            <div class="badge badge-warning">Repair</div>
                        @elseif ($unused->status == 'recharge')
                            <div class="badge badge-success">Recharge</div>
                        @elseif ($unused->status == 'bad')
                            <div class="badge badge-danger">Bad</div>
                        @endif
                    </td>
                    <td>
                        @if ($unused->repair_status == 'use')
                            <div class="badge badge-success">Reuse</div>
                        @elseif ($unused->repair_status == 'bad')
                            <div class="badge badge-danger">Bad</div>
                        @endif
                    </td>
                    <td>
                        @if ($unused->recharge_status == 'use')
                            <div class="badge badge-success">Reuse</div>
                        @elseif ($unused->recharge_status == 'bad')
                            <div class="badge badge-danger">Bad</div>
                        @endif
                    </td>
                    <td>{{ $unused->unit_price }}</td>
                    <td>{{ $unused->total_price }}</td>
                    </tr>

            </table>
        </div>
        <form
            action="{{ route('employee.sendReceiveUnusedProductToTeamMemberPost', ['status' => $status, 'unused' => $unused]) }}"
            method="post">
            @csrf
            @if (!$unused->assign_to)
                <div class="row">
                    <div class="col-12 col-md-6 m-auto">

                        <div class="form-group">
                            <select name="assign_to" id="assign_to" class="form-control">
                                <option value="">Select Employee</option>
                                @foreach ($myEmployees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>


                        @if ($status == 'repair')
                            <input type="submit" name="repair_status" value="Repair" class="btn btn-success">
                        @elseif ($status == 'recharge')
                            <input type="submit" name="recharge_status" value="Recharge" class="btn btn-success">
                        @endif

                        {{-- <div class="form-group">
                @if ($unused->repair_use_by || $unused->bad_by)
                @else
                    @if ($status == 'repair' && !$unused->repair_status)
                        <div class="btn-group btn-sm">
                            <input type="submit" name="repair_status" value="Done" class="btn btn-success">

                            <input type="submit" name="repair_status" value="Not Possible" class="btn btn-danger">

                        </div>
                    @elseif ($status == 'recharge' && !$unused->recharge_status)
                        <div class="btn-group btn-sm">
                            <input type="submit" name="recharge_status" value="Done" class="btn btn-success">

                            <input type="submit" name="recharge_status" value="Not Possible" class="btn btn-danger">


                        </div>
                    @endif
                @endif
            </div> --}}
                    </div>
                </div>
            @endif
        </form>
    </div>
@endsection

@push('js')
    <script src="{{ asset('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js') }}"></script>
    <script>
        $(document).on('click', '.checkedItem', function() {
            var that = $(this);
            var url = that.attr('data-url');
            $.ajax({
                url: url,
                method: "GET",
                success: function(res) {
                    // console.log(res);
                }

            })
        })

        $('#assign_to').select2();
    </script>
@endpush
