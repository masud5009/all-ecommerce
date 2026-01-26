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
    <div class="container">
        <div class="mx-auto mt-5">
            <div class="col-lg-6 m-auto">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center">Signup</h4>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('user.signup_submit') }}" method="post">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="">Username</label>
                                <input placeholder="username" type="text" class="form-control" name="username">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Email</label>
                                <input placeholder="email" type="email" class="form-control" name="email">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Password</label>
                                <input placeholder="password" type="password" class="form-control" name="password">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Confirm Password</label>
                                <input placeholder="confirm password" type="password" class="form-control"
                                    name="password_confirmation">
                            </div>
                            <button type="submit" class="btn btn-success">submit</button>
                        </form>
                        <a href="{{ route('user.login') }}">Alredy have a account?</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
