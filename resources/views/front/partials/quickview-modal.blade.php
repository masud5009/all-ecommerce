{{-- Quick View Modal Backdrop --}}
<div data-quickview-backdrop
    class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm transition-opacity hidden"
    aria-hidden="true"></div>

{{-- Quick View Modal --}}
<div data-quickview-modal
    class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden"
    role="dialog"
    aria-modal="true"
    aria-labelledby="quickview-title">

    <div class="relative w-full max-w-4xl max-h-[90vh] overflow-y-auto rounded-3xl border border-green-100 bg-white shadow-2xl">
        {{-- Close Button --}}
        <button type="button" data-quickview-close
            class="absolute right-4 top-4 z-10 flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-slate-500 shadow-sm transition hover:bg-green-50 hover:text-green-700"
            aria-label="Close quick view">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        {{-- Modal Content (loaded via AJAX) --}}
        <div data-quickview-content class="p-6">
            {{-- Loading state --}}
            <div class="flex items-center justify-center py-20">
                <div class="h-10 w-10 animate-spin rounded-full border-4 border-green-200 border-t-green-600"></div>
            </div>
        </div>
    </div>
</div>
