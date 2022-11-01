@extends('admin.layouts.adminMaster')

@push('css')
    {{-- css Here --}}
@endpush
@section('content')
    @if (Agent::isMobile())
    @include('admin.part.mobileView')
    @else

    @include('admin.part.webView')

    @endif
@endsection
@push('js')
    {{-- JS here --}}
@endpush
