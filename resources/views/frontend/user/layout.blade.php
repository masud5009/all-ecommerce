@extends('front.layout')

@section('title', 'My Dashboard | FreshCart')
@section('page', 'user-dashboard')

@section('content')
<section class="min-h-[calc(100vh-160px)] bg-slate-50">
    <div class="flex h-full">
        <!-- Sidebar -->
        <aside class="hidden w-64 flex-shrink-0 overflow-y-auto border-r border-slate-200 bg-white lg:block">
            @include('frontend.user.partials.sidebar')
        </aside>

        <!-- Mobile Sidebar Toggle -->
        <div class="lg:hidden">
            <button id="sidebarToggle" class="p-4">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            @yield('dashboard-content')
        </main>
    </div>
</section>

<!-- Mobile Sidebar Modal -->
<div id="sidebarModal" class="fixed inset-0 z-40 hidden bg-black bg-opacity-50 lg:hidden">
    <div class="fixed inset-y-0 left-0 w-64 overflow-y-auto bg-white">
        @include('frontend.user.partials.sidebar')
    </div>
</div>

<script>
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarModal = document.getElementById('sidebarModal');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            sidebarModal.classList.toggle('hidden');
        });

        sidebarModal.addEventListener('click', (e) => {
            if (e.target === sidebarModal) {
                sidebarModal.classList.add('hidden');
            }
        });
    }
</script>
@endsection
