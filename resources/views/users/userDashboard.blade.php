@extends('users.layouts.userMaster')
@section('content')
    <div class="row">
        <div class="col-12 col-lg-6 m-auto">
            <div class="box box-widget widget-user-2 shadow">

                <div class="widget-user-header bg-yellow">
                    <div class="widget-user-image">
                        <img class="img-circle"
                            src="{{ route('imagecache', ['template' => 'pplg', 'filename' => $user->fi()]) }}"
                            alt="User Avatar">
                    </div>

                    <h3 class="widget-user-username"> {{ $user->name }}</h3>
                    <h5 class="widget-user-desc">&nbsp;</h5>
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <table class="table table-borderd">
                            <tr>
                                <th>Username/Email</th>
                                <td>{{ $user->username }}</td>
                            </tr>
                            <tr>
                                <th>Profile Image</th>
                                <td><img src="{{ route('imagecache', ['template' => 'sbixs', 'filename' => $user->fi()]) }}"
                                        alt=""></td>
                            </tr>
                            <tr>
                                <th>Tracking</th>
                                <td>
                                    @if ($user->track)
                                        <span class="badge badge-success"><i class="fas fa-check"></i></span>
                                    @else
                                        <span class="badge badge-danger"><i class="fas fa-times"></i></span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
