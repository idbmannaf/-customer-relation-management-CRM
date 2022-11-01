@extends('admin.layouts.adminMaster')
@push('title')
    Holidays
@endpush


@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="d-flex justify-content-between">
                <div>
                    Holidays Of Year: <select id="year" name="year" data-url="{{ route('admin.holiday.index') }}">
                        {{ $last = date('Y') - 10 }}
                        {{ $now = date('Y') }}
                        @for ($i = $now; $i >= $last; $i--)
                            <option {{ $this_year == $i ? 'selected' : '' }} value="{{ $i }}">{{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                @can('holiday-add')
                <div>
                    <a class="btn btn-danger" href="{{ route('admin.holiday.create') }}">New Holiday</a>
                </div>
                @endcan

            </div>
            {{-- <select name="year" id="year">
                @for ($i = 0; $i > 20; $i++)
                    <option value="{{(date("Y")+1) - $i}}">{{(date("Y")+1) - $i}}</option>
                    {{$i++}}
                @endfor

            </select> --}}
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>#SL</th>
                            @can('holiday-update')
                            <th>Action</th>
                            @endcan
                            <th>Date</th>
                            <th>Purpose</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($holidays as $holiday)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                @can('holiday-update')
                                <td>
                                    <a class="btn btn-warning btn-sm" data-toggle="modal"
                                        data-target="#holiday{{ $holiday->id }}" data-whatever="@fat">Edit</a>
                                </td>
                                @endcan
                                <td>{{ $holiday->date }}</td>
                                <td>{{ $holiday->purpose }}</td>
                            </tr>
                            {{-- Modal --}}
                            <div class="modal fade" id="holiday{{ $holiday->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit {{ $holiday->date }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('admin.holiday.update', ['holiday' => $holiday]) }}"
                                                method="post">
                                                @method('PATCH')
                                                @csrf
                                                <div class="form-group">
                                                    <label for="date">Holiday Date</label>
                                                    <input type="date" name="date" value="{{ $holiday->date }}"
                                                        class='date form-control'>
                                                    <span class="text-danger dateError"></span>
                                                </div>
                                                <div class="form-group">
                                                    <label for="date">Holiday Purpose</label>
                                                    <input type="text" name="purpose[]" value="{{ $holiday->purpose }}"
                                                        class='purpose form-control'>
                                                </div>
                                                <div class="form-group">
                                                    <input type="submit" value="Update" class="btn btn-info">
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            {{-- Modal --}}
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="float-right">
                {{ $holidays->appends(['year', $this_year])->render() }}
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).on('change', '#year', function() {
            var url = $(this).attr('data-url');
            var year = $(this).val()
            var finalUrl = url + "?year=" + year;
            window.location.href = finalUrl;
        })
    </script>
@endpush
