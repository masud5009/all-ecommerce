@extends('front.layout')

@section('title', 'My Wishlist')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">My Wishlist</h1>

        @if ($wishlistItems->isEmpty())
            <p>Your wishlist is empty.</p>
        @else
            <div class="row">
                @foreach ($wishlistItems as $item)
                    <div class="col-md-4 mb-4">
                        @include('front.product-card', ['product' => $item->product])
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
