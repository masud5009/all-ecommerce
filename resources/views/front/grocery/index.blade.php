@extends('front.layout')
@section('content')
    <!-- Hero banner slider -->
    @include('front.grocery.hero')

    <!-- Categories section -->
    @include('front.grocery.categories')

    <!-- Featured products -->
    @include('front.grocery.featured-product')

    <!-- Popular products section -->
    @include('front.grocery.popular-product')

    <!-- Flash sale products section -->
    @include('front.grocery.flash-sale-product')

    <!-- Rewards section -->
    {{-- @include('front.grocery.rewards') --}}

    <!-- Features section -->
    @include('front.grocery.features')
@endsection
