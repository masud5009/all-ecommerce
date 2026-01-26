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
                <div class="card-header">Forget password page</div>
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
                    <form action="{{ route('user.forget_password.send_email') }}" method="post">
                        @csrf
                        <div class="form-group mb-2">
                            <label for="">Enter Email</label>
                            <input type="email" placeholder="Enter Email" name="email" class="form-control">
                        </div>
                        <button class="btn btn-primary">Send</button>
                    </form>
                </div>
            </div>
            <div class="d-flex px-2">
                <a href="{{ route('user.login') }}">Login</a>
            </div>
        </div>
    </div>
@endsection
