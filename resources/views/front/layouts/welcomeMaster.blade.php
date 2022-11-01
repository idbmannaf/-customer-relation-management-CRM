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
        <script src="{{ asset('js/app.js') }}" defer></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        @stack('css')
    </head>

    <body>
        <div id="app">
            <main class="">
                <div class="card p-0 m-0">
                    <div class="card-header text-8 px-5" style="background-color: #38B749">
                        <a class="text-white" href="/">
							<img class="w3-round-large" style="max-width: 80px; max-height:80px" src="{{ asset('img/logo.png') }}" alt="">
						</a>
                    </div>
					<div class=" py-4" style="background-color: #283291 ">

					</div>
                @yield('content')
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
        </div>

        @stack('js')
    </body>
    </html>
