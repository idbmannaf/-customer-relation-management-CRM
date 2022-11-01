<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Role Name</th>
                <th>User</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="">
            @foreach ($team->teamMember() as $team_member)
                <tr>
                    <td>{{ $team_member->pivot->role_name }}</td>
                    <td>{{ $team_member->name }}</td>
                    <td>
                        <a href="{{ route('admin.roleDelete', ['role' => $team_member->pivot->id, 'team' => $team]) }}"
                            class="text-danger deleteRole"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
</div>
{{ $team->teamMember()->render() }}
