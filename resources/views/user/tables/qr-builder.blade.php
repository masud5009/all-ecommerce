@extends('user.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('user.dashboard') }}"><span class="fas fa-home"></span></a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('user.tables', ['language' => $defaultLang->code]) }}">{{ __('Tables') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('QR Builder') }}</a>
            </li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Qr Code Generator</h4>
                </div>
                <div class="card-body">
                    <form id="qrGeneratorForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="table_id" value="{{ $table->id }}">

                        <!-- Color Field -->
                        <div class="form-group">
                            <label for="">Color</label>
                            <input type="text"
                                   class="form-control jscolor"
                                   name="color"
                                   value="{{ $table->color ?? '#000000' }}"
                                   data-jscolor="{}">
                            <p class="mb-0 text-warning">If the QR Code cannot be scanned, then choose a darker color</p>
                        </div>

                        <!-- Size Field -->
                        <div class="form-group">
                            <label for="">Size</label>
                            <input class="form-control p-0 range-slider"
                                   name="size"
                                   type="range"
                                   min="200"
                                   max="350"
                                   value="{{ $table->size ?? 300 }}">
                            <span class="text-white size-text float-right">{{ $table->size ?? 300 }}</span>
                        </div>

                        <!-- White Space / Margin Field -->
                        <div class="form-group">
                            <label for="">White Space</label>
                            <input class="form-control p-0 range-slider"
                                   name="margin"
                                   type="range"
                                   min="0"
                                   max="5"
                                   value="{{ $table->margin ?? 1 }}">
                            <span class="text-white size-text float-right">{{ $table->margin ?? 1 }}</span>
                        </div>

                        <!-- Style Field -->
                        <div class="form-group">
                            <label for="">Style</label>
                            <select name="style" class="form-control">
                                <option value="square" {{ ($table->style ?? 'square') == 'square' ? 'selected' : '' }}>Square</option>
                                <option value="round" {{ ($table->style ?? 'square') == 'round' ? 'selected' : '' }}>Round</option>
                            </select>
                        </div>

                        <!-- Eye Style Field -->
                        <div class="form-group">
                            <label for="">Eye Style</label>
                            <select name="eye_style" class="form-control">
                                <option value="square" {{ ($table->eye_style ?? 'square') == 'square' ? 'selected' : '' }}>Square</option>
                                <option value="circle" {{ ($table->eye_style ?? 'square') == 'circle' ? 'selected' : '' }}>Circle</option>
                            </select>
                        </div>

                        <!-- Type Field -->
                        <div class="form-group">
                            <label for="">Type</label>
                            <select name="type" class="form-control">
                                <option value="default" {{ ($table->type ?? 'default') == 'default' ? 'selected' : '' }}>Default</option>
                                <option value="image" {{ ($table->type ?? 'default') == 'image' ? 'selected' : '' }}>Image</option>
                                <option value="text" {{ ($table->type ?? 'default') == 'text' ? 'selected' : '' }}>Text</option>
                            </select>
                        </div>

                        <!-- Image Type Options -->
                        <div id="type-image" class="types">
                            <div class="form-group">
                                <div class="col-12 mb-2">
                                    <label for="image"><strong> Image</strong></label>
                                </div>
                                <div class="col-md-12 showImage mb-3">
                                    <img src="{{ $table->image ? asset('assets/img/tables/' . $table->image) : asset('assets/admin/img/noimage.jpg') }}"
                                        alt="..." class="img-thumbnail qr">
                                </div>
                                <input type="file" name="image" id="image" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="">Image Size</label>
                                <input class="form-control p-0 range-slider"
                                       name="image_size"
                                       type="range"
                                       min="1"
                                       max="20"
                                       value="{{ $table->image_size ?? 10 }}">
                                <span class="text-white size-text float-right d-block">{{ $table->image_size ?? 10 }}</span>
                                <p class="mb-0 text-warning">If the QR Code cannot be scanned, then reduce this size</p>
                            </div>

                            <div class="form-group">
                                <label for="">Image Horizontal Position</label>
                                <input class="form-control p-0 range-slider"
                                       name="image_x"
                                       type="range"
                                       min="0"
                                       max="100"
                                       value="{{ $table->image_x ?? 50 }}">
                                <span class="text-white size-text float-right">{{ $table->image_x ?? 50 }}</span>
                            </div>

                            <div class="form-group">
                                <label for="">Image Vertical Position</label>
                                <input class="form-control p-0 range-slider"
                                       name="image_y"
                                       type="range"
                                       min="0"
                                       max="100"
                                       value="{{ $table->image_y ?? 50 }}">
                                <span class="text-white size-text float-right">{{ $table->image_y ?? 50 }}</span>
                            </div>
                        </div>

                        <!-- Text Type Options -->
                        <div id="type-text" class="types">
                            <div class="form-group">
                                <label>Text</label>
                                <input type="text"
                                       name="text"
                                       value="{{ $table->text ?? '' }}"
                                       class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Text Color</label>
                                <input type="text"
                                       name="text_color"
                                       value="{{ $table->text_color ?? '#000000' }}"
                                       class="form-control jscolor">
                            </div>

                            <div class="form-group">
                                <label for="">Text Size</label>
                                <input class="form-control p-0 range-slider"
                                       name="text_size"
                                       type="range"
                                       min="1"
                                       max="15"
                                       value="{{ $table->text_size ?? 5 }}">
                                <span class="text-white size-text float-right d-block">{{ $table->text_size ?? 5 }}</span>
                                <p class="mb-0 text-warning">If the QR Code cannot be scanned, then reduce this size</p>
                            </div>

                            <div class="form-group">
                                <label for="">Text Horizontal Position</label>
                                <input class="form-control p-0 range-slider"
                                       name="text_x"
                                       type="range"
                                       min="0"
                                       max="100"
                                       value="{{ $table->text_x ?? 50 }}">
                                <span class="text-white size-text float-right">{{ $table->text_x ?? 50 }}</span>
                            </div>

                            <div class="form-group">
                                <label for="">Text Vertical Position</label>
                                <input class="form-control p-0 range-slider"
                                       name="text_y"
                                       type="range"
                                       min="0"
                                       max="100"
                                       value="{{ $table->text_y ?? 50 }}">
                                <span class="text-white size-text float-right">{{ $table->text_y ?? 50 }}</span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card bg-white">
                <div class="card-header" style="border-bottom: 1px solid #ebecec!important;">
                    <h4 class="card-title" style="color: #575962;">Preview</h4>
                </div>
                <div class="card-body text-center py-5">
                    <div class="p-3 border-rounded d-inline-block border" style="background-color: #f8f9fa!important;">
                        <img id="preview"
                            src="{{ $table->qr_image ? asset('assets/img/tables/' . $table->qr_image) . '?t=' . time() : asset('assets/admin/img/noimage.jpg') }}"
                            alt="QR Preview"
                            style="max-width: 100%; height: auto;">
                    </div>
                </div>
                <div class="card-footer text-center" style="border-top: 1px solid #ebecec!important;">
                    <a id="downloadBtn"
                       class="btn btn-success"
                       download="qr-image.png"
                       href="{{ $table->qr_image ? asset('assets/img/tables/' . $table->qr_image) : '#' }}">Download Image</a>
                </div>
            </div>
            <span id="text-size" style="visibility: hidden;">{{ $table->text ?? '' }}</span>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title d-flex justify-content-between">
                        <span>Included QR Code Banners (PSDs)</span>
                        <a class="btn btn-success"
                           href="{{ asset('assets/img/qr_banners/QR_Banners_PSDs.zip') }}"
                           download="qr_banners_psds.zip">Download</a>
                    </h5>
                </div>
                <div class="card-body">
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                        </ol>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img class="d-block w-100"
                                     src="{{ asset('assets/img/qr_banners/1.png') }}"
                                     alt="First slide">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block w-100"
                                     src="{{ asset('assets/img/qr_banners/2.png') }}"
                                     alt="Second slide">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block w-100"
                                     src="{{ asset('assets/img/qr_banners/3.png') }}"
                                     alt="Third slide">
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button"
                            data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button"
                            data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a class="btn btn-success"
                       href="{{ asset('assets/img/qr_banners/QR_Banners_PSDs.zip') }}"
                       download="qr_banners_psds.zip">Download PSDs</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- jscolor library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jscolor/2.4.5/jscolor.min.js"></script>

    <script type="text/javascript">
        let isGenerating = false;

        function generateQr() {
            if (isGenerating) {
                console.log("⏳ Already generating, please wait...");
                return;
            }

            console.log("🔄 generateQr() called");

            loadDiv($("select[name='type']").val());
            $(".request-loader").addClass('show');
            isGenerating = true;

            let formData = new FormData(document.getElementById('qrGeneratorForm'));

            // Debug: Check all form values
            console.log("📋 Form Data:");
            for (let [key, value] of formData.entries()) {
                console.log(`  ${key}: ${value}`);
            }

            $(".range-slider").attr('disabled', true);

            $.ajax({
                url: "{{ route('user.tables.generate_qr') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log("✅ Success:", response);

                    $(".request-loader").removeClass('show');
                    $(".range-slider").attr('disabled', false);
                    isGenerating = false;

                    if (response.success) {
                        const timestamp = new Date().getTime();
                        const newImageUrl = response.image_url.split('?')[0] + '?t=' + timestamp;

                        const previewImg = document.getElementById('preview');
                        previewImg.style.opacity = '0.5';

                        const tempImage = new Image();
                        tempImage.onload = function() {
                            previewImg.src = newImageUrl;
                            previewImg.style.opacity = '1';
                            console.log("✅ Preview updated");
                        };

                        tempImage.onerror = function() {
                            console.error("❌ Image load failed");
                            previewImg.style.opacity = '1';
                        };

                        tempImage.src = newImageUrl;

                        $('#downloadBtn').attr('href', newImageUrl);

                        if (typeof toastr !== 'undefined') {
                            toastr.success('QR code updated');
                        }
                    } else {
                        if (typeof toastr !== 'undefined') {
                            toastr.error(response.message || 'Failed to generate QR');
                        }
                    }
                },
                error: function(xhr) {
                    console.error("❌ Error:", xhr.responseText);

                    $(".request-loader").removeClass('show');
                    $(".range-slider").attr('disabled', false);
                    isGenerating = false;

                    let errorMessage = 'Failed to generate QR code';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        console.error("Validation Errors:", xhr.responseJSON.errors);
                        errorMessage += '\n' + Object.values(xhr.responseJSON.errors).flat().join('\n');
                    }

                    if (typeof toastr !== 'undefined') {
                        toastr.error(errorMessage);
                    }
                }
            });
        }

        function loadDiv(type) {
            $(".types").removeClass('d-block').addClass('d-none');
            $("#type-" + type).removeClass("d-none").addClass("d-block");
        }

        $(document).ready(function() {
            let type = $("select[name='type']").val();
            loadDiv(type);

            // Range slider value display
            $(".range-slider").on("input", function() {
                let value = $(this).val();
                $(this).next(".size-text").html(value);
                console.log(`📏 ${$(this).attr('name')} changed to: ${value}`);
            });

            // Debounce function
            let qrTimeout;
            function debouncedGenerateQr() {
                clearTimeout(qrTimeout);
                qrTimeout = setTimeout(generateQr, 500);
            }

            // Event handlers
            $('select[name="type"]').on('change', function() {
                console.log("📝 Type changed to:", $(this).val());
                loadDiv($(this).val());
                generateQr();
            });

            $('select[name="style"], select[name="eye_style"]').on('change', function() {
                console.log("📝 Select changed:", $(this).attr('name'), $(this).val());
                generateQr();
            });

            $('.jscolor').on('change', function() {
                console.log("🎨 Color changed:", $(this).attr('name'), $(this).val());
                generateQr();
            });

            $('input[type="range"]').on('input', function() {
                let value = $(this).val();
                $(this).next(".size-text").html(value);
            });

            $('input[type="range"]').on('change', debouncedGenerateQr);

            $('input[name="text"]').on('input', debouncedGenerateQr);

            $('input[name="text_color"]').on('change', generateQr);

            $('input[type="file"]').on('change', function() {
                console.log("📁 File selected:", this.files[0]?.name);
                generateQr();
            });

            // Initialize jscolor
            if (typeof jscolor !== 'undefined') {
                jscolor.installByClassName("jscolor");
            }
        });

        // Test function
        function testQR() {
            console.log("🧪 Testing QR Generation...");
            console.log("Size value:", $('input[name="size"]').val());
            console.log("Margin value:", $('input[name="margin"]').val());
            generateQr();
        }
    </script>
@endsection
