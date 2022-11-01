<div class="form-group">
    <label for="team">Team</label>
    <select name="team" id="team" class="form-control" data-url="{{ route('admin.user.CompanyTeams',['user'=>$user->id]) }}">
        <option value="">Select Team</option>
        @foreach ($company->teams as $team)
            <option value="{{ $team->id }}">{{ $team->name }}</option>
        @endforeach
    </select>
</div>
{{--
<div class="form-group">
    <label for="role">Roles</label>
    <label for="role1"><input type="radio" checked name="role" id="role1" value="admin">Admin</label>
    <label for="role2"><input type="radio" name="role" id="role2" value="member">Member</label>
    <label for="role3"><input type="radio" name="role" id="role3"  value="no_role">No Role</label>
    </div> --}}
