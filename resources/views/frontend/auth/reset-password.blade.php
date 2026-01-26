@extends('frontend.layout')
@section('pageHeading')
    {{ __('Home') }}
@endsection
@section('metaKeywords')
    {{ !empty($seoInfo) ? $seoInfo->meta_keyword_home : '' }}
@endsection

@section('metaDescription')
    {{ !empty($seoInfo) ? $seoInfo->meta_description_home : '' }}
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-8 m-auto mt-5">
            <div class="card">
                <div class="card-header">Reset your password now</div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session()->has('error'))
                    <h6 class="text-danger">{{ session()->get('error') }}</h6>
                @endif
                <div class="card-body">
                    <form action="{{ route('user.reset_password') }}" method="post">
                        @csrf
                        <div class="form-group mb-2">
                            <label for="">new password</label>
                            <input type="password" placeholder="Enter password" name="new_password" class="form-control">
                        </div>
                        <div class="form-group mb-2">
                            <label for="">confirm new password</label>
                            <input type="password" placeholder="Enter password" name="new_password_confirmation"
                                class="form-control">
                        </div>
                        <button class="btn btn-primary">Reset</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
