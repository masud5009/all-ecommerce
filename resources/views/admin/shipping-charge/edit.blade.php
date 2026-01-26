@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}"><span class="fas fa-home"></span></a>
            </li>
            <li class="breadcrumb-item">
                <a
                    href="{{ route('admin.shop.shipping_charge', ['language' => $language->code]) }}">{{ __('Shipping Charges') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Edit Charge') }}</a>
            </li>
        </ol>
    </nav>


    <div class="col-lg-12 mb-5">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-8 col-sm-6">
                        <div class="card-title">
                            <h5>{{ __('Edit Charges') }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="info-header-content">
                            <a href="{{ route('admin.shop.shipping_charge', ['language' => $language->code]) }}"
                                class="btn btn-primary btn-sm float-lg-end float-left">
                                <i class="fas fa-angle-double-left"></i>{{ __('Back') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="col-lg-8 m-auto">
                    <form id="ajaxEditForm" action="{{ route('admin.shop.shipping_charge_update', ['id' => $data->id]) }}"
                        method="post">
                        @csrf
                        <div class="row">
                            @foreach ($languages as $lang)
                                @php
                                    $sData = App\Models\ShippingCharge::where([
                                        ['language_id', $lang->id],
                                        ['unique_id', $data->unique_id],
                                    ])
                                        ->select('title', 'id')
                                        ->first();
                                @endphp
                                <input type="hidden" name="{{ $lang->code }}_id" value="{{ @$sData->id }}">
                                <x-text-input col="12" placeholder="Enter title" name="{{ $lang->code }}_title"
                                    type="text" label="Title ({{ $lang->name }})" required="*"
                                    language="{{ $lang->code }}" value="{{ $sData->title }}" />
                            @endforeach
                            <input type="hidden" name="charge_id" value="{{ $data->id }}">
                            <x-text-input col="12" placeholder="Enter text" name="text" type="textarea"
                                label="Text" required="*" value="{{ $data->text }}" />


                            <x-text-input col="12" placeholder="Enter charge" name="charge" type="number"
                                label="Charge({{ $websiteInfo->currency_text }})" required="*"
                                value="{{ $data->charge }}" />

                            <x-text-input col="12" placeholder="Serial Number" name="serial_number" type="text"
                                label="Serial Number" required="*" value="{{ $data->serial_number }}" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-success" id="updateBtn" type="button">{{ __('Update') }}</button>

            </div>
        </div>
    </div>
@endsection
