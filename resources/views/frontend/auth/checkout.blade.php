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
                <div class="card-header">
                    Billing Details
                </div>
                <div class="card-body">
                    <form action="{{ route('front.membership.checkout') }}" method="post">
                        @csrf
                        <input type="text" placeholder="enter company name" name="company_name">
                        <li>
                            <span>Issue Date:
                                {{ \Carbon\Carbon::today()->format('d-m-Y') }}
                            </span>
                        </li>
                        <li>
                            <span>Expire Date:
                                @if ($package->term === 'monthly')
                              {{ \Carbon\Carbon::today()->addMonth()->format('d-m-Y') }}
                            @elseif($package->term === 'lifetime')
                              {{ __('Lifetime') }}
                            @else
                              {{ \Carbon\Carbon::today()->addYear()->format('d-m-Y') }}
                            @endif
                            </span>
                        </li>
                        <button type="submit" class="btn btn-success mt-3">Payment Now</button>
                    </form>
                </div>
            </div>
            <div class="d-flex px-2">
                <a href="{{ route('user.login') }}">Login</a>
            </div>
        </div>
    </div>
@endsection
