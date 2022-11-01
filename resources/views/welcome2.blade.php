<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{asset('prt/css/w3.css')}}">
        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <link rel="stylesheet" href="{{asset('prt/css/theme.css')}}">

        {{-- <!-- Vendor CSS -->
		<link rel="stylesheet" href="{{asset('prt/vendor/bootstrap/css/bootstrap.min.css')}}">
		<link rel="stylesheet" href="{{asset('prt/vendor/fontawesome-free/css/all.min.css')}}">
		<link rel="stylesheet" href="{{asset('prt/vendor/animate/animate.min.css')}}">
		<link rel="stylesheet" href="{{asset('prt/vendor/simple-line-icons/css/simple-line-icons.min.css')}}">
        <link rel="stylesheet" href="{{asset('prt/vendor/owl.carousel/assets/owl.carousel.min.css')}}">
		<link rel="stylesheet" href="{{asset('prt/vendor/owl.carousel/assets/owl.theme.default.min.css')}}">
		<link rel="stylesheet" href="{{asset('prt/vendor/magnific-popup/magnific-popup.min.css')}}">

		<!-- Theme CSS -->
		<link rel="stylesheet" href="{{asset('prt/css/theme.css')}}">
		<link rel="stylesheet" href="{{asset('prt/css/theme-elements.css')}}">
		<link rel="stylesheet" href="{{asset('prt/css/theme-blog.css')}}">
		<link rel="stylesheet" href="{{asset('prt/css/theme-shop.css')}}">

		<!-- Current Page CSS -->
		<link rel="stylesheet" href="{{asset('prt/vendor/rs-plugin/css/settings.css')}}">
		<link rel="stylesheet" href="{{asset('prt/vendor/rs-plugin/css/layers.css')}}">
		<link rel="stylesheet" href="{{asset('prt/vendor/rs-plugin/css/navigation.css')}}">
		<link rel="stylesheet" href="{{asset('prt/vendor/circle-flip-slideshow/css/component.css')}}">


		<!-- Demo CSS -->


		<!-- Skin CSS -->
		<link rel="stylesheet" href="{{asset('prt/css/skins/default.css')}}">

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="{{asset('prt/css/custom.css')}}">

		<!-- Head Libs -->
        <script src="{{asset('prt/vendor/modernizr/modernizr.min.js')}}"></script> --}}
        @stack('css')
    </head>

    <body>
        <div id="app">
            {{-- @include('layouts.header') --}}

            <main class="">
                <div class="card p-0 m-0">
                    <div class="card-header text-8 px-5" style="background-color: #38B749">
                        <a class="text-white" href="/">
							<img class="w3-round-large" style="max-width: 80px; max-height:80px" src="{{ asset('img/dhpl.jpg') }}" alt="">
						</a>
                    </div>
					<div class=" py-4" style="background-color: #283291 ">

					</div>
                    <div class="card-body- text-center- d-none d-md-block" style="height: 85vh; background: url({{ asset('img/slides/slide2.jpg') }}) no-repeat top right; background-size: 100%">
                        <div class="container pt-4 text-white w3-xxlarge pt-4" style="line-height: 34pt">
                            Welcome to, <br>
                            {{ env('APP_FULL_NAME') ?? 'Dhaka Homoeo Hall Pvt. Ltd.' }}
                        </div>
                        <div class="container pt-5">
                            <a class="btn btn-secondary border-none text-5 w3-orange w3-text-white" href="{{ route('login') }}">Login</a>
                        </div>
                        {{-- <img class="m-auto" style="max-width: 100%" src="{{ asset('img/slides/slide1.jpg') }}" alt=""> --}}
                    </div>
                    <div class="card-body- text-center- d-md-none" style="height: 80vh; background: url({{ asset('img/slides/slide1.jpg') }}) no-repeat top right; background-size: 100%">
                        <div class="container text-white w3-xxlarge pt-4" style="line-height: 34pt">
                            Welcome to, <br>
                            {{ env('APP_FULL_NAME') ?? 'Dhaka Homoeo Hall Pvt. Ltd.' }}
                        </div>
                        <div class="container pt-5">
                            <a class="btn btn-secondary text-5 w3-orange w3-text-white" href="{{ route('login') }}">Login</a>
                        </div>
                        {{-- <img class="m-auto" style="max-width: 100%" src="{{ asset('img/slides/slide1.jpg') }}" alt=""> --}}
                    </div>
                </div>
            </main>
            <footer id="">
                <div class="container">
                    <div class="text-center">
                        <span>© Copyright 2021. All Rights Reserved.</span>
                        <span> | </span>
                        <span class="">Developed by Multisoft</span>
                    </div>
                </div>
            </footer>
            {{-- @include('layouts.footer') --}}
        </div>

        @stack('js')
    </body>
    </html>
