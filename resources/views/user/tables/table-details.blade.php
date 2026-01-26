@extends('user.layout')
@section('content')
    <div class="container">
        <h2>Table Details</h2>
        <p><strong>Table Number:</strong> {{ $table->table_number }}</p>
        <p><strong>Capacity:</strong> {{ $table->capacity }}</p>
        <p><strong>Status:</strong> {{ $table->status }}</p>
        <!-- অন্যান্য টেবিল তথ্য -->
    </div>
@endsection
