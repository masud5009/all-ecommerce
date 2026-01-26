    <script src="{{ asset('assets/front/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/jquery.nice-select.js') }}"></script>
    <!-- toaster js -->
    <script src="{{ asset('assets/admin/js/jquery.toast.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/aos.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="{{ asset('assets/front/js/countUp.umd.js') }}"></script>
    <script src="{{ asset('assets/front/js/main.js') }}"></script>

    <script>
        "use strict";

        function handleSelect(elm) {
            window.location.href = "{{ route('frontend.change_language', '') }}" + "/" + elm.value;
        }
    </script>
    @if (Session::has('success'))
        <script>
            $.toast({
                heading: 'Success',
                text: '{{ Session::get('success') }}',
                showHideTransition: 'plain',
                icon: 'success',
                allowToastClose: true,
                position: 'top-right',
                hideAfter: 5000,
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
                hideAfter: 5000,
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
                hideAfter: 5000,
                // hideAfter: false
            });
        </script>
    @endif

    @yield('script')
