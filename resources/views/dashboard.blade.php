@extends('layouts.app')

@section('title', __('messages.dashboard_heading'))

@section('content')
    <h1 class="display-5 animate__animated animate__fadeInDown">{{ __('messages.dashboard_heading') }}</h1>
    <p class="lead animate__animated animate__fadeInUp animate__delay-1s">
        {{ __('messages.dashboard_lead') }}
    </p>
@endsection