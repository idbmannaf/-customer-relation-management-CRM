@extends('front.layouts.welcomeMaster')
@push('css')

@endpush
@section('content')

    <div class="card-body- text-center- d-none d-md-block" style="height: 75vh; background: url({{ asset('img/back.jpg') }}) no-repeat center center; background-size: 50%">
        <div class="container pt-4 text-dark w3-xxlarge pt-4" style="line-height: 34pt">
            Welcome to, <br>
            {{ env('APP_FULL_NAME') ?? 'Tracking' }}
        </div>
        <div class="container pt-5">
            @auth
            <a class="btn btn-secondary border-none text-5 w3-orange w3-text-white" href="{{ route('admin.dashboard') }}">Dashboard</a>
            @endauth
          @guest
          <a class="btn btn-secondary border-none text-5 w3-orange w3-text-white" href="{{ route('login') }}">Login</a>
          @endguest
        </div>
        {{-- <img class="m-auto" style="max-width: 100%" src="{{ asset('img/slides/slide1.jpg') }}" alt=""> --}}
    </div>
    <div class="card-body- text-center- d-md-none" style="height: 80vh; background: url({{ asset('img/slides/slide1.jpg') }}) no-repeat top right; background-size: 100%">
        <div class="container text-white w3-xxlarge pt-4" style="line-height: 34pt">
            Welcome to, <br>
            {{ env('APP_FULL_NAME') ?? 'Tracking' }}
        </div>
        <div class="container pt-5">
            <a class="btn btn-secondary text-5 w3-orange w3-text-white" href="{{ route('login') }}">Login</a>
        </div>
        {{-- <img class="m-auto" style="max-width: 100%" src="{{ asset('img/slides/slide1.jpg') }}" alt=""> --}}
    </div>
@endsection
