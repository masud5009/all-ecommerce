@extends('user.layout')
@section('content')
    <h5 class="mb-4">Hi, {{ Auth::guard('web')->user()->username }}</h5>
    
@endsection
