@extends('app')

@section('head')
<style>
body {
    overflow: hidden;
}
</style>
@endsection

@section('content')
<div class="min-h-screen flex bg-[#2FA769] p-10 overflow-y-hidden">
    <div class="w-full max-w-2xl bg-[#253239] rounded-xl p-8 min-h-screen flex flex-col justify-center mr-8">
        <div class="text-left mb-2">
            <h1 class="text-4xl mb-2" style="font-family: 'Arial Black', sans-serif; font-weight: 900;"><span class="text-[#2FA769]">DAV</span><span class="text-white">ELLA</span></h1>
        </div>
        @include('auth.login.components.LoginForm')
    </div>
    <div class="flex-1 flex items-center justify-center text-white text-left px-8">
        <div>
            <h1 class="text-4xl mb-2" style="font-family: 'Arial Black', sans-serif; font-weight: 900;"><span class="text-white">DAV</span><span class="text-white">ELLA</span></h1>
            <h3 class="text-xl italic mb-4 text-gray-200">Real-Time Sales Tracking and Housing Unit Availability Platform</h3>
            <p class="text-base leading-relaxed">Davella is a digital platform for monitoring housing sales activities while checking the availability of clusters and units in real-time. With a centralized and user-friendly system, Davella helps marketing and management teams control sales progress, unit status, and cluster distribution more quickly, accurately, and organized.</p>
        </div>
    </div>
</div>
@endsection