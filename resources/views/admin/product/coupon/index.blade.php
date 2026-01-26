@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}"><span class="fas fa-home"></span></a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Product Management') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Coupons') }}</a>
            </li>
        </ol>
    </nav>


    <div class="col-lg-12">
        <div class="card">
            <x-bulk-delete :url="route('admin.product.coupon_bulk_delete')" itemTextName="coupons" />
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-4 col-sm-6">
                        <div class="card-title">
                            <h5>{{ __('Coupons') }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-8 col-sm-6">
                        <div class="info-header-content">
                            <a href="#" class="btn btn-primary btn-sm float-lg-end float-left" data-bs-toggle="modal"
                                data-bs-target="#createModal">
                                <i class="fas fa-plus"></i> {{ __('Add') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="col-lg-12 mx-auto">
                    @if (count($coupons) == 0)
                        <h5 class="text-center">{{ __('NO COUPON FOUND') . '!' }}</h5>
                    @else
                        <div class="table-responsive">
                            <table id="myTable" class="table table-striped">
                                <thead>
                                    <th scope="col">
                                        <input type="checkbox" class="bulk-check" data-val="all">
                                    </th>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Code') }}</th>
                                    <th scope="col">{{ __('Discount') }}</th>
                                    <th scope="col">{{ __('Created') }}</th>
                                    <th scope="col">{{ __('Type') }}</th>
                                    <th scope="col">{{ __('Action') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($coupons as $key => $coupon)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="bulk-check" data-val="{{ $coupon->id }}">
                                            </td>
                                            <td>{{ $coupon->name }}</td>
                                            <td>{{ $coupon->code }}</td>
                                            <td>{{ $coupon->value }}</td>
                                            <td>{{ $coupon->created_at }}</td>
                                            <td>
                                                @if ($coupon->status == 'fixed')
                                                    <span class="badge bg-success">{{ __('Fixed') }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ __('Percentage') }}</span>
                                                @endif
                                            </td>
                                            <td class="action-buttons">
                                                <a href="" class="btn btn-sm editBtn edit-button"
                                                    data-bs-toggle="modal" data-bs-target="#editModal"
                                                    data-id="{{ $coupon->id }}" data-name="{{ $coupon->name }}"
                                                    data-code="{{ $coupon->code }}" data-type="{{ $coupon->type }}"
                                                    data-value="{{ $coupon->value }}"
                                                    data-start_date="{{ $coupon->start_date }}"
                                                    data-end_date="{{ $coupon->end_date }}"
                                                    data-amount_spend="{{ $coupon->amount_spend }}">
                                                    <span class="fas fa-edit"></span>
                                                </a>
                                                <form class="deleteForm d-inline-block"
                                                    action="{{ route('admin.product.coupon_delete') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" value="{{ $coupon->id }}" name="coupon_id">
                                                    <button class="btn btn-sm deleteBtn delete-button" type="button">
                                                        <span class="fas fa-trash"></span>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
            <div class="card-footer py-2">
            </div>
        </div>
    </div>
    @includeIf('admin.product.coupon.create')
    @includeIf('admin.product.coupon.edit')
@endsection
