@php
    $sidebarSearchStaticItems = [
        [
            'title' => __('General Settings'),
            'parentLabel' => __('Settings'),
            'href' => route('admin.website_setting.general_setting'),
            'iconClass' => 'fas fa-cog',
        ],
        [
            'title' => __('Email Settings'),
            'parentLabel' => __('Settings'),
            'href' => route('admin.website_setting.config_email'),
            'iconClass' => 'fas fa-envelope',
        ],
        [
            'title' => __('Email Templates'),
            'parentLabel' => __('Settings'),
            'href' => route('admin.website_setting.mail_template'),
            'iconClass' => 'fas fa-envelope-open-text',
        ],
        [
            'title' => __('Plugins'),
            'parentLabel' => __('Settings'),
            'href' => route('admin.plugin'),
            'iconClass' => 'fas fa-plug',
        ],
        [
            'title' => __('Payment Gateway'),
            'parentLabel' => __('Settings'),
            'href' => route('admin.gateway'),
            'iconClass' => 'fas fa-credit-card',
        ],
        [
            'title' => __('Page Heading'),
            'parentLabel' => __('Settings'),
            'href' => route('admin.website_setting.page_heading'),
            'iconClass' => 'fas fa-heading',
        ],
        [
            'title' => __('Maintenance Mode'),
            'parentLabel' => __('Settings'),
            'href' => route('admin.maintenance'),
            'iconClass' => 'fas fa-wrench',
        ],
    ];
@endphp

<script>
    let themeColor = "{{ session()->has('themeColor') ? session()->get('themeColor') : 'light' }}";
    let currency_symbol = "{{ $websiteInfo->currency_symbol }}";
    let symbol_position = "{{ $websiteInfo->currency_symbol_position }}";
    const baseUrl = "{{ url('/') }}";
    let Monthly_Sale = "{{ __('Monthly Sale') }}"
    let Monthly_Orders = "{{ __('Monthly Orders') }}"
    let activeText = "{{ __('Active') }}";
    let InactiveText = "{{ __('Inactive') }}";
    window.sidebarSearchStaticItems = @json($sidebarSearchStaticItems);
</script>

<!-- jquery cdn -->
<script src="{{ asset('assets/admin/js/jquery.min.js') }}"></script>
<!-- bootstrap js-->
<script src="{{ asset('assets/admin/js/bootstrap.bundle.min.js') }}"></script>
<!-- fontawsome kit -->
<script src="{{ asset('assets/admin/js/icon.js') }}"></script>
<!-- datatables js -->
<script src="{{ asset('assets/admin/js/dataTables.min.js') }}"></script>
<script type="text/javascript" charset="utf8"
    src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
<!-- color picker js -->
<script src="{{ asset('assets/admin/js/jscolor.min.js') }}"></script>
<!-- toaster js -->
<script src="{{ asset('assets/admin/js/jquery.toast.min.js') }}"></script>
<!--dropzone js-->
<script src="{{ asset('assets/admin/js/dropzone.min.js') }}"></script>
<!-- flatpicker js-->
<script src="{{ asset('assets/admin/js/flatpickr.js') }}"></script>
<!-- sweetalert js -->
<script src="{{ asset('assets/admin/js/sweetalert2.all.min.js') }}"></script>
<!-- tags-input js -->
<script src="{{ asset('assets/admin/js/tags-input.js') }}"></script>
<!-- tinymce js -->
<script src="{{ asset('assets/admin/js/tinymce/tinymce.min.js') }}"></script>
<!-- iconpicker js-->
<script src="{{ asset('assets/admin/js/fontawesome-iconpicker.min.js') }}"></script>
<!-- select2 js -->
<script src="{{ asset('assets/admin/js/select2.min.js') }}"></script>
<!-- iconpicker js-->
<script src="{{ asset('assets/admin/js/iconpicker.js') }}"></script>
<!-- main js-->
<script src="{{ asset('assets/admin/js/app.js') }}"></script>
<script src="{{ asset('assets/admin/js/config.js') }}"></script>
<script src="{{ asset('assets/admin/js/sidebar-search.js') }}"></script>



@if (Session::has('success'))
    <script>
        $.toast({
            heading: 'Success',
            text: '{{ Session::get('success') }}',
            showHideTransition: 'plain',
            icon: 'success',
            allowToastClose: true,
            position: 'top-right',
            hideAfter: 4000,
            // hideAfter: false
        });
    </script>
@endif
@if (Session::has('error'))
    <script>
        $.toast({
            heading: 'Error',
            text: '{{ Session::get('error') }}',
            showHideTransition: 'plain',
            icon: 'error',
            allowToastClose: true,
            position: 'top-right',
            hideAfter: 4000,
            // hideAfter: false
        });
    </script>
@endif
@if (Session::has('warning'))
    <script>
        $.toast({
            heading: 'Warning',
            text: '{{ Session::get('warning') }}',
            showHideTransition: 'plain',
            icon: 'warning',
            allowToastClose: true,
            position: 'top-right',
            hideAfter: 4000,
            // hideAfter: false
        });
    </script>
@endif
