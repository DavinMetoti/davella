@extends('app')

@section('content')
@include('partials.navbar')

<div class="flex mt-16">
    <div class="sidebar fixed top-19 left-0 w-64 bg-white text-gray-800 min-h-screen transition-all duration-300 overflow-hidden z-10">
        @include('partials.sidebar')
    </div>

    <main class="main flex-1 p-6 pt-6 ml-64">
        @yield('main')
    </main>
</div>
@endsection