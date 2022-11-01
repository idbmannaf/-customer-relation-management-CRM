@extends('employee.layouts.employeeMaster')
@push('title')
    Products Creation
@endpush
@push('css')
    <style>
        select#backUpType {
            background: none;
            border: none;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('back/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('back/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">

        </div>
        <div class="card-body">

            <div class="float-right">
            </div>
        </div>
    </div>
@endsection
@push('js')
@endpush
