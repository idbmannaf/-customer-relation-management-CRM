<label for="team_admin_employee_id">Team Admin</label> <br>
<select name="team_admin_employee_id" id="team_admin_employee_id" class="form-control">
    <option value="">Select Team Admin</option>
    @foreach ($team_admins as $admin)
        <option {{ $admin->employee_id == $employee->team_admin_id ? 'selected' : '' }}
            value="{{ $admin->employee_id }}">{{ $admin->name }}: {{ $admin->company->name }} :
            {{ $admin->user->officeLocation ? $admin->user->officeLocation->title : '' }}</option>
    @endforeach

</select>
