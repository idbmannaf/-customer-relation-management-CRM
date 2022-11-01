<div class="form-group">
<label for="role">Roles</label>
<label for="role1"><input type="radio" {{$team->hasAdminRole($user->id)? 'checked': ''}}  name="role" id="role1" value="admin">Admin</label>
<label for="role2"><input type="radio" {{$team->hasMemberRole($user->id)? 'checked': ''}} name="role" id="role2" value="member">Member</label>
<label for="role3"><input type="radio" {{$team->hasNoRole($user->id)? 'checked': ''}} name="role" id="role3"  value="no_role">No Role</label>
</div>
