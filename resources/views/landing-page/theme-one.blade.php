<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  @php
    $pageData = $pageData ?? [];
    $pageValue = function ($key, $default = '') use ($pageData) {
        $value = data_get($pageData, $key);
        return filled($value) ? $value : $default;
    };
    $hasSelectedProduct = !empty($selectedProduct);
    $productTitle = filled($selectedProductTitle ?? null) ? $selectedProductTitle : $pageValue('product_name', 'SmartFit Watch');
    $productDescription = filled($selectedProductSummary ?? null)
        ? $selectedProductSummary
        : $pageValue('hero_description', 'Premium smart watch with heart-rate monitor, sleep tracking, step counter, long battery life, and stylish design.');
    $productImage = $hasSelectedProduct ? ($selectedProductImage ?? null) : ($pageData['hero_image'] ?? null);
    $productCurrentPrice = $hasSelectedProduct ? $selectedProductPriceLabel : $pageValue('price_now', 'Tk 2,490');
    $productOldPrice = $hasSelectedProduct ? $selectedProductOldPriceLabel : $pageValue('price_old', 'Tk 3,990');
    $productSaveText = $hasSelectedProduct ? $selectedProductSaveLabel : $pageValue('save_text', 'Save Tk 1,500 today');
    $primaryCtaText = $hasSelectedProduct ? $selectedProductCtaText : $pageValue('cta_primary_text', 'Buy Now');
    $priceCtaText = $hasSelectedProduct ? $selectedProductCtaText : $pageValue('price_cta_text', $primaryCtaText);
    $browserTitle = $hasSelectedProduct ? $productTitle . ' - Product Landing Page' : $pageValue('page_title', 'SmartFit Watch - Product Landing Page');
    $featureCards = [];
    $featureItems = data_get($pageData, 'feature_items');
    if (is_array($featureItems)) {
        foreach ($featureItems as $featureItem) {
            if (!is_array($featureItem)) {
                continue;
            }

            if (!filled($featureItem['icon'] ?? null) && !filled($featureItem['title'] ?? null) && !filled($featureItem['description'] ?? null)) {
                continue;
            }

            $featureCards[] = [
                'icon' => $featureItem['icon'] ?? '',
                'title' => $featureItem['title'] ?? '',
                'description' => $featureItem['description'] ?? '',
            ];
        }
    } else {
        for ($i = 1; $i <= 4; $i++) {
            $featureCards[] = [
                'icon' => $pageValue('feature_' . $i . '_icon', ['&#9989;', '&#128666;', '&#128737;', '&#128257;'][$i - 1]),
                'title' => $pageValue('feature_' . $i . '_title', ['Health Tracking', 'Fast Delivery', 'Premium Quality', 'Easy Return'][$i - 1]),
                'description' => $pageValue('feature_' . $i . '_description', ['Heart rate, sleep, steps and calories.', 'Delivery within 24-72 hours.', 'Durable body with modern design.', '7-day replacement support.'][$i - 1]),
            ];
        }
    }
    $lifestyleBullets = [];
    $lifestyleBulletItems = data_get($pageData, 'lifestyle_bullets');
    if (is_array($lifestyleBulletItems)) {
        foreach ($lifestyleBulletItems as $lifestyleBulletItem) {
            if (!is_array($lifestyleBulletItem) || !filled($lifestyleBulletItem['text'] ?? null)) {
                continue;
            }

            $lifestyleBullets[] = $lifestyleBulletItem['text'];
        }
    } else {
        for ($i = 1; $i <= 4; $i++) {
            $lifestyleBullets[] = $pageValue('lifestyle_bullet_' . $i, ['Bluetooth calling support', '7 days battery backup', 'Water-resistant design', 'Compatible with Android & iPhone'][$i - 1]);
        }
    }
    $reviews = [];
    $reviewItems = data_get($pageData, 'review_items');
    if (is_array($reviewItems)) {
        foreach ($reviewItems as $reviewItem) {
            if (!is_array($reviewItem)) {
                continue;
            }

            if (!filled($reviewItem['rating'] ?? null) && !filled($reviewItem['text'] ?? null) && !filled($reviewItem['author'] ?? null)) {
                continue;
            }

            $reviews[] = [
                'rating' => $reviewItem['rating'] ?? '',
                'text' => $reviewItem['text'] ?? '',
                'author' => $reviewItem['author'] ?? '',
            ];
        }
    } else {
        for ($i = 1; $i <= 3; $i++) {
            $reviews[] = [
                'rating' => $pageValue('review_' . $i . '_rating', '&#9733;&#9733;&#9733;&#9733;&#9733;'),
                'text' => $pageValue('review_' . $i . '_text', ['Battery backup khub valo. Design premium.', 'Delivery fast chilo, product exactly same.', 'Fitness tracking er jonno perfect.'][$i - 1]),
                'author' => $pageValue('review_' . $i . '_author', 'Verified Customer'),
            ];
        }
    }
    $faqs = [];
    $faqItems = data_get($pageData, 'faq_items');
    if (is_array($faqItems)) {
        foreach ($faqItems as $faqItem) {
            if (!is_array($faqItem)) {
                continue;
            }

            if (!filled($faqItem['question'] ?? null) && !filled($faqItem['answer'] ?? null)) {
                continue;
            }

            $faqs[] = [
                'question' => $faqItem['question'] ?? '',
                'answer' => $faqItem['answer'] ?? '',
            ];
        }
    } else {
        for ($i = 1; $i <= 3; $i++) {
            $faqs[] = [
                'question' => $pageValue('faq_' . $i . '_question', ['Delivery kotodin lage?', 'Cash on delivery ache?', 'Return policy ache?'][$i - 1]),
                'answer' => $pageValue('faq_' . $i . '_answer', ['Usually 24-72 hours, location er upor depend kore.', 'Yes, all over Bangladesh cash on delivery available.', 'Yes, 7-day replacement support available.'][$i - 1]),
            ];
        }
    }
  @endphp
  <title>{{ $browserTitle }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-900">
  <header class="bg-white border-b sticky top-0 z-10">
    <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
      <h1 class="text-xl font-bold">{{ $pageValue('brand_name', 'SmartFit Watch') }}</h1>
      <a href="#order" class="bg-slate-900 text-white px-5 py-2 rounded-full text-sm font-semibold">
        {{ $primaryCtaText }}
      </a>
    </div>
  </header>

  <section class="max-w-6xl mx-auto px-4 py-14 grid md:grid-cols-2 gap-10 items-center">
    <div>
      <p class="inline-block bg-emerald-100 text-emerald-700 px-4 py-1 rounded-full text-sm font-semibold mb-4">
        {{ $pageValue('hero_badge', 'Limited Offer - Free Delivery') }}
      </p>
      <h2 class="text-4xl md:text-5xl font-extrabold leading-tight mb-5">
        {{ $productTitle }}
      </h2>
      <p class="text-lg text-slate-600 mb-6">
        {{ $productDescription }}
      </p>
      <div class="flex flex-wrap gap-3 mb-8">
        <a href="#order" class="bg-slate-900 text-white px-7 py-3 rounded-xl font-bold">
          {{ html_entity_decode('&#128722;') }} {{ $primaryCtaText }}
        </a>
        <a href="#features" class="bg-white border px-7 py-3 rounded-xl font-bold">
          {{ $pageValue('cta_secondary_text', 'View Features') }}
        </a>
      </div>
      <div class="text-amber-500 font-semibold">
        {{ html_entity_decode('&#9733;&#9733;&#9733;&#9733;&#9733;') }}
        <span class="text-slate-600 ml-2">{{ $pageValue('rating_text', '4.9/5 from 1,250+ customers') }}</span>
      </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl p-8 text-center">
      @if (!empty($productImage))
        <img src="{{ asset($productImage) }}" alt="{{ $productTitle }}" class="w-full h-80 object-cover rounded-3xl">
      @else
        <div class="h-80 rounded-3xl bg-gradient-to-br from-slate-200 to-slate-400 flex items-center justify-center">
          <div class="w-44 h-64 bg-slate-950 rounded-[2.5rem] shadow-2xl p-4">
            <div class="h-full rounded-[2rem] bg-slate-800 text-white flex flex-col items-center justify-center">
              <p class="text-sm text-slate-300">12:45</p>
              <p class="text-4xl font-bold mt-2">82</p>
              <p class="text-sm text-emerald-300">BPM</p>
            </div>
          </div>
        </div>
      @endif
    </div>
  </section>

  <section id="features" class="bg-white py-14">
    <div class="max-w-6xl mx-auto px-4">
      <h3 class="text-3xl font-bold text-center mb-10">{{ $pageValue('features_title', 'Why Customers Love It') }}</h3>
      <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-5">
        @foreach ($featureCards as $feature)
          <div class="p-6 rounded-2xl bg-slate-50 border">
            <div class="text-3xl mb-4">{{ html_entity_decode($feature['icon']) }}</div>
            <h4 class="font-bold text-lg mb-2">{{ $feature['title'] }}</h4>
            <p class="text-slate-600 text-sm">{{ $feature['description'] }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <section class="max-w-6xl mx-auto px-4 py-14 grid md:grid-cols-2 gap-8 items-center">
    <div>
      <h3 class="text-3xl font-bold mb-5">{{ $pageValue('lifestyle_title', 'Perfect For Daily Life') }}</h3>
      <p class="text-slate-600 mb-5">
        {{ $pageValue('lifestyle_description', 'Office, gym, walking, running, or casual use - ' . $productTitle . ' helps you stay connected and active all day.') }}
      </p>
      <ul class="space-y-3 text-slate-700">
        @foreach ($lifestyleBullets as $bullet)
          <li>{{ html_entity_decode('&#10003;') }} {{ $bullet }}</li>
        @endforeach
      </ul>
    </div>

    <div class="bg-slate-900 text-white rounded-3xl p-8">
      @if (!empty($productOldPrice))
        <p class="text-slate-300 line-through text-xl">{{ $productOldPrice }}</p>
      @endif
      <p class="text-5xl font-extrabold my-2">{{ $productCurrentPrice }}</p>
      @if (!empty($productSaveText))
        <p class="text-emerald-300 font-semibold mb-6">{{ $productSaveText }}</p>
      @endif
      <a href="#order" class="block text-center bg-white text-slate-900 py-4 rounded-xl font-extrabold">
        {{ $priceCtaText }}
      </a>
    </div>
  </section>

  <section class="bg-white py-14">
    <div class="max-w-6xl mx-auto px-4">
      <h3 class="text-3xl font-bold text-center mb-8">{{ $pageValue('reviews_title', 'Customer Reviews') }}</h3>
      <div class="grid md:grid-cols-3 gap-5">
        @foreach ($reviews as $review)
          <div class="p-6 rounded-2xl border bg-slate-50">
            <div class="text-amber-500 mb-3">{{ html_entity_decode($review['rating']) }}</div>
            <p class="text-slate-700">"{{ $review['text'] }}"</p>
            <p class="font-bold mt-4">{{ $review['author'] }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <section id="order" class="max-w-3xl mx-auto px-4 py-14">
    <div class="bg-white rounded-3xl shadow-xl p-8 border">
      <h3 class="text-3xl font-bold text-center mb-2">{{ $pageValue('order_title', 'Place Your Order') }}</h3>
      <p class="text-center text-slate-600 mb-8">{{ $pageValue('order_notice', 'Cash on delivery available all over Bangladesh') }}</p>
      <form class="space-y-4">
        <input class="w-full border rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-slate-900" type="text" placeholder="Your Name" />
        <input class="w-full border rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-slate-900" type="tel" placeholder="Phone Number" />
        <textarea class="w-full border rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-slate-900" rows="4" placeholder="Delivery Address"></textarea>
        <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-xl font-extrabold">
          {{ html_entity_decode('&#128222;') }} {{ $pageValue('order_button_text', 'Confirm Order') }}
        </button>
      </form>
    </div>
  </section>

  <section class="max-w-4xl mx-auto px-4 pb-14">
    <h3 class="text-3xl font-bold text-center mb-8">{{ $pageValue('faq_title', 'FAQ') }}</h3>
    <div class="space-y-4">
      @foreach ($faqs as $faq)
        <div class="bg-white border rounded-2xl p-5">
          <h4 class="font-bold mb-2">{{ $faq['question'] }}</h4>
          <p class="text-slate-600">{{ $faq['answer'] }}</p>
        </div>
      @endforeach
    </div>
  </section>

  <footer class="text-center py-8 text-slate-500 text-sm border-t bg-white">
    {{ $pageValue('footer_text', '2026 SmartFit Watch. All rights reserved.') }}
  </footer>
</body>
</html>
