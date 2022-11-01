<div class="card">
    <div class="card-header">
        Claim Details For Convayances Bill
        @if ($convayances->status == 'pending')
            <span class="badge badge-warning">Pending</span>
        @elseif ($convayances->status == 'rejected')
            <span class="badge badge-danger">Rejected</span>
        @elseif ($convayances->status == 'approved')
            <span class="badge badge-success">Approved</span>
        @endif
    </div>
    @if ($convayances->status == 'temp')
        <div class="card-body showcon">
            @foreach ($convayances->items as $item)
                <div class="temp py-1" data-id="{{ $item->id }}"
                    data-url="{{ route('employee.convayancesChangeAjax', ['convayances' => $convayances->id, 'item' => $item->id]) }}">
                    <div class="row">
                        <div class="col-12 col-md-5">
                            <input type="text" value="{{ $item->movement_details }}" name="movement_details"
                                class="form-control movement_details movement_details_ajax"
                                placeholder="Movement Details">
                        </div>
                        <div class="col-12 col-md-3">
                            <select name="travel_mode" id="travel_mode"
                                class="form-control travel_mode travel_mode_ajax">
                                <option value="">Select Travel Mode</option>
                                <option {{ $item->travel_mode == 'Rickshaw' ? 'selected' : '' }} value="Rickshaw">
                                    Rickshaw</option>
                                <option {{ $item->travel_mode == 'CNG' ? 'selected' : '' }} value="CNG">CNG
                                </option>
                                <option {{ $item->travel_mode == 'Bus' ? 'selected' : '' }} value="Bus">Bus
                                </option>
                                <option {{ $item->travel_mode == 'Motocycle' ? 'selected' : '' }} value="Motocycle">
                                    Motocycle</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <input type="number" name="amount" value="{{ $item->amount }}"
                                class="form-control amount amount_ajax" placeholder="0.00">
                        </div>
                        <div class="col-12 col-md-1">
                            <button type="button" data-id="{{ $item->id }}"
                                data-url="{{ route('employee.convayances.delete', ['convayances' => $convayances->id, 'item' => $item->id]) }}"
                                class="btn btn-danger btn-sm removeCon"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="temp">
                <div class="row">
                    <div class="col-12 col-md-5">
                        <input type="text" name="movement_details" class="form-control movement_details"
                            placeholder="Movement Details">
                    </div>
                    <div class="col-12 col-md-3">
                        <select name="travel_mode" class="form-control travel_mode">
                            <option value="">Select Travel Mode</option>
                            <option value="Rickshaw">Rickshaw</option>
                            <option value="CNG">CNG</option>
                            <option value="Bus">Bus</option>
                            <option value="Motocycle">Motocycle</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <input type="number" name="amount" class="form-control amount" placeholder="0.00">
                    </div>
                    <div class="col-12 col-md-1">
                        <button type="button"
                            data-url="{{ route('employee.convayances.add', ['convayances' => $convayances, 'visit' => $visit]) }}"
                            class="btn btn-success btn-sm addCon"><i class="fas fa-plus"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="d-flex justify-content-between">
                <div class="total_amount"> <b>Total Amount: </b>{{ $convayances->total_amount }}</div>
                <div class="submit">
                    <form
                        action="{{ route('employee.convayancesSubmit', ['convayances' => $convayances, 'visit' => $visit]) }}">
                        <input type="submit" class="btn btn-info" name="submit" value="pending"
                            onclick="return confirm('Are you Sure? you want to submit this convayances bill?')">
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-8 m-auto">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Serial</th>
                                            <th>Amount</th>
                                            <th>Travel Mode</th>
                                            <th>Movement Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($convayances->items as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->amount }}</td>
                                                <td>{{ $item->travel_mode }}</td>
                                                <td>{{ $item->movement_details }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between">
                <div class="total_amount"> <b>Total Amount: </b>{{ $convayances->total_amount }}</div>
            </div>
        </div>

    @endif


</div>
