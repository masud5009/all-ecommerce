@php
    $successMessage = $successMessage ?? null;
@endphp

<div data-review-tab-content class="space-y-5">
    @if ($successMessage)
        <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
            {{ $successMessage }}
        </div>
    @endif

    @auth('web')
        <form action="{{ route('frontend.shop.review.store', ['id' => $productId]) }}" method="POST" data-review-form
            class="rounded-2xl border border-green-100 bg-white p-5">
            @csrf
            <div class="flex flex-wrap items-center justify-between gap-3">
                <p class="text-base font-semibold text-slate-900">Write a Review</p>
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-slate-600">Rating:</label>
                    <select name="rating"
                        class="rounded-xl border border-green-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-100">
                        @for ($star = 5; $star >= 1; $star--)
                            <option value="{{ $star }}" {{ (int) old('rating', 5) === $star ? 'selected' : '' }}>
                                {{ $star }} Star{{ $star > 1 ? 's' : '' }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="mt-3">
                <textarea name="comment" rows="3"
                    class="w-full rounded-xl border border-green-200 bg-white px-4 py-3 text-sm text-slate-700 focus:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-100"
                    placeholder="Share your experience...">{{ old('comment') }}</textarea>
            </div>

            <div data-review-errors
                class="{{ $errors->has('rating') || $errors->has('comment') ? '' : 'hidden' }} mt-3 rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
                @if ($errors->has('rating'))
                    <p>{{ $errors->first('rating') }}</p>
                @endif
                @if ($errors->has('comment'))
                    <p>{{ $errors->first('comment') }}</p>
                @endif
            </div>

            <div class="mt-3 flex justify-end">
                <button type="submit" data-review-submit
                    class="inline-flex items-center rounded-xl bg-green-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200">
                    Submit Review
                </button>
            </div>
        </form>
    @else
        <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            Please <a href="{{ route('user.login') }}" class="font-semibold underline">login</a> to submit a review.
        </div>
    @endauth

    <div class="grid gap-4 lg:grid-cols-[300px_1fr]">
        <div class="rounded-2xl border border-green-100 bg-green-50/40 p-5">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Overall Rating</p>
            <p class="mt-2 text-4xl font-bold leading-none text-slate-900">{{ number_format($productRating, 1) }}</p>
            <div class="mt-2 flex items-center gap-1">
                @for ($i = 1; $i <= 5; $i++)
                    <svg class="h-4 w-4 {{ $i <= floor($productRating) ? 'text-amber-400' : 'text-slate-300' }}"
                        viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z" />
                    </svg>
                @endfor
            </div>
            <p class="mt-2 text-sm text-slate-600">
                {{ __('Based on') }} {{ $productReviews }}
                {{ \Illuminate\Support\Str::plural('reviews', $productReviews) }}
            </p>
        </div>

        <div class="rounded-2xl border border-green-100 bg-white p-5">
            @php
                $ratingsFromList = collect($productReviewList)->pluck('rating')->filter()->map(fn($r) => (int) $r);
                $ratingBase = max($ratingsFromList->count(), 1);
            @endphp

            @for ($star = 5; $star >= 1; $star--)
                @php
                    $countForStar = $ratingsFromList->filter(fn($r) => $r === $star)->count();
                    $percent = ($countForStar / $ratingBase) * 100;
                @endphp
                <div class="flex items-center gap-3 {{ $star > 1 ? 'mb-2' : '' }}">
                    <div class="flex w-12 items-center gap-1 text-xs font-semibold text-slate-600">
                        <span>{{ $star }}</span>
                        <svg class="h-3.5 w-3.5 text-amber-400" viewBox="0 0 24 24" fill="currentColor"
                            aria-hidden="true">
                            <path
                                d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z" />
                        </svg>
                    </div>
                    <div class="h-2.5 flex-1 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full bg-amber-400"
                            style="width: {{ number_format($percent, 2, '.', '') }}%"></div>
                    </div>
                    <p class="w-8 text-right text-xs font-medium text-slate-500">{{ $countForStar }}</p>
                </div>
            @endfor
        </div>
    </div>

    @if (count($productReviewList) > 0)
        <div class="grid gap-3 sm:grid-cols-2">
            @foreach ($productReviewList as $review)
                <div
                    class="rounded-2xl border border-green-100 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100 text-sm font-semibold text-green-700">
                                {{ strtoupper(substr($review['name'] ?? 'U', 0, 1)) }}
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ $review['name'] }}</p>
                                <p class="text-xs text-slate-400">Verified Purchase</p>
                            </div>
                        </div>

                        <span class="flex items-center gap-1">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= ($review['rating'] ?? 5))
                                    <svg class="h-4 w-4 text-amber-400" viewBox="0 0 24 24" fill="currentColor"
                                        aria-hidden="true">
                                        <path
                                            d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z" />
                                    </svg>
                                @else
                                    <svg class="h-4 w-4 text-slate-300" viewBox="0 0 24 24" fill="currentColor"
                                        aria-hidden="true">
                                        <path
                                            d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z" />
                                    </svg>
                                @endif
                            @endfor
                        </span>
                    </div>
                    <p class="mt-3 text-sm leading-6 text-slate-600">{{ $review['text'] }}</p>
                </div>
            @endforeach
        </div>
    @else
        <div class="rounded-2xl border border-dashed border-green-200 bg-white p-6 text-center text-sm text-slate-500">
            No reviews yet. Be the first to review this product!
        </div>
    @endif
</div>
