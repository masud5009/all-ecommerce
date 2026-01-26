<div class="card mb-4">
    <div class="card-body">
        <label for="" class="mb-2">{{ __($label) }} *</label>

        @if (!is_null($sliders))
            <div class="col-12">
                <table class="table table-striped" id="imgtable">
                    @foreach ($sliders as $item)
                        <tr class="trdb table-row" id="trdb{{ $item->id }}">
                            <td>
                                <div class="">
                                    <img class="thumb-preview wf-150"
                                        src="{{ asset('assets/img/product/gallery/' . $item->image) }}" alt="Ad Image">
                                </div>
                            </td>
                            <td>
                                <i class="fa fa-times rmvbtndb" data-indb="{{ $item->id }}"></i>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif

        <div class="px-2">
            <form id="my-dropzone" enctype="multipart/form-data" class="dropzone create">
                @csrf
                <div class="dz-message d-flex flex-column align-items-center">
                    <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                    <span>{{ __('Drag & Drop your files here or click to upload') }}</span>
                </div>
            </form>
            <p class="text-warning">
                {{ __($noteText) }}
            </p>
            <p class="em text-danger mb-0" id="err_slider_images"></p>
        </div>
    </div>
</div>
