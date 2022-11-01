<tr>

    <td><input type="time" name="start_time" onchange="update(this,'start_time')"
            value="{{ $convayances_item->start_time }}" class="form-control" id="start_time">

        <input type="hidden" class="update_url"
            value="{{ route('employee.convayancesChangeAjax', ['convayances' => $convayances->id, 'item' => $convayances_item->id]) }}">
    </td>
    <td><input type="time" name="end_time" onchange="update(this,'end_time')"
            value="{{ $convayances_item->end_time }}" class="form-control" id="end_time">
    </td>
    <td><input type="text" name="start_from" oninput="update(this,'start_from')"
            value="{{ $convayances_item->start_from }}" class="form-control" id="start_from">
    </td>
    <td><input type="text" name="start_to" oninput="update(this,'start_to')"
            value="{{ $convayances_item->start_to }}" class="form-control" id="start_to"></td>
    <td>
        <select name="travel_mode" id="travel_mode" onchange="update(this,'travel_mode')"
            class="form-control travel_mode travel_mode_ajax">
            <option value="">Select Travel Mode</option>
            <option {{ $convayances_item->travel_mode == 'Rickshaw' ? 'selected' : '' }} value="Rickshaw">Rickshaw
            </option>
            <option {{ $convayances_item->travel_mode == 'Auto Rickshaw' ? 'selected' : '' }} value="Auto Rickshaw">Auto
                Rickshaw</option>
            <option {{ $convayances_item->travel_mode == 'CNG' ? 'selected' : '' }} value="CNG">CNG</option>
            <option {{ $convayances_item->travel_mode == 'Bus' ? 'selected' : '' }} value="Bus">Bus</option>
            <option {{ $convayances_item->travel_mode == 'Motocycle' ? 'selected' : '' }} value="Motocycle">Motocycle
            </option>

        </select>
    </td>
    <td> <input type="text" name="movement_details" class="form-control" oninput="update(this,'movement_details')"
            placeholder="Movement Details" value="{{ $convayances_item->movement_details }}"></td>
    <td>
        <input type="number" name="amount" value="{{ $convayances_item->amount }}" oninput="update(this,'amount')"
            class="form-control amount amount_ajax" placeholder="0.00">
    </td>

    <td><button type="button" data-id="{{ $convayances_item->id }}"
            data-url="{{ route('employee.convayances.delete', ['convayances' => $convayances->id, 'item' => $convayances_item->id]) }}"
            class="btn btn-danger btn-sm removeCon"><i class="fas fa-trash"></i></button></td>
</tr>
{{-- <div class="temp my-1" data-id="{{ $convayances_item->id }}"
    data-url="{{ route('employee.convayancesChangeAjax', ['convayances' => $convayances->id, 'item' => $convayances_item->id]) }}">
    <div class="row">
        <div class="col-12 col-md-5">
            <input type="text" value="{{ $convayances_item->movement_details }}" name="movement_details"
                class="form-control movement_details movement_details_ajax" placeholder="Movement Details">
        </div>
        <div class="col-12 col-md-3">
            <select name="travel_mode" id="travel_mode travel_mode_ajax" class="form-control travel_mode">
                <option value="">Select Travel Mode</option>
                <option {{ $convayances_item->travel_mode == 'Rickshaw' ? 'selected' : '' }} value="Rickshaw">Rickshaw
                </option>
                <option {{ $convayances_item->travel_mode == 'CNG' ? 'selected' : '' }} value="CNG">CNG</option>
                <option {{ $convayances_item->travel_mode == 'Bus' ? 'selected' : '' }} value="Bus">Bus</option>
                <option {{ $convayances_item->travel_mode == 'Motocycle' ? 'selected' : '' }} value="Motocycle">
                    Motocycle</option>
            </select>
        </div>
        <div class="col-12 col-md-3">
            <input type="number" name="amount" value="{{ $convayances_item->amount }}"
                class="form-control amount amount_ajax" placeholder="0.00">
        </div>
        <div class="col-12 col-md-1">
            <button type="button" data-id="{{ $convayances_item->id }}"
                data-url="{{ route('employee.convayances.delete', ['convayances' => $convayances->id, 'item' => $convayances_item->id]) }}"
                class="btn btn-danger btn-sm removeCon"><i class="fas fa-trash"></i></button>
        </div>
    </div>
</div> --}}
