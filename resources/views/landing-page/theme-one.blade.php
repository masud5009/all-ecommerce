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
  @endphp
  <title>{{ $pageValue('page_title', 'SmartFit Watch - Product Landing Page') }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-900">
  <!-- Header -->
  <header class="bg-white border-b sticky top-0 z-10">
    <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
      <h1 class="text-xl font-bold">{{ $pageValue('brand_name', 'SmartFit Watch') }}</h1>
      <a href="#order" class="bg-slate-900 text-white px-5 py-2 rounded-full text-sm font-semibold">
        {{ $pageValue('cta_primary_text', 'Order Now') }}
      </a>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="max-w-6xl mx-auto px-4 py-14 grid md:grid-cols-2 gap-10 items-center">
    <div>
      <p class="inline-block bg-emerald-100 text-emerald-700 px-4 py-1 rounded-full text-sm font-semibold mb-4">
        {{ $pageValue('hero_badge', 'Limited Offer • Free Delivery') }}
      </p>
      <h2 class="text-4xl md:text-5xl font-extrabold leading-tight mb-5">
        {{ $pageValue('hero_title', 'Track Your Health & Fitness Every Day') }}
      </h2>
      <p class="text-lg text-slate-600 mb-6">
        {{ $pageValue('hero_description', 'Premium smart watch with heart-rate monitor, sleep tracking, step counter, long battery life, and stylish design.') }}
      </p>
      <div class="flex flex-wrap gap-3 mb-8">
        <a href="#order" class="bg-slate-900 text-white px-7 py-3 rounded-xl font-bold">
          🛒 {{ $pageValue('cta_primary_text', 'Buy Now') }}
        </a>
        <a href="#features" class="bg-white border px-7 py-3 rounded-xl font-bold">
          {{ $pageValue('cta_secondary_text', 'View Features') }}
        </a>
      </div>
      <div class="text-amber-500 font-semibold">
        ★★★★★ <span class="text-slate-600 ml-2">{{ $pageValue('rating_text', '4.9/5 from 1,250+ customers') }}</span>
      </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl p-8 text-center">
      @if (!empty($pageData['hero_image']))
        <img src="{{ asset($pageData['hero_image']) }}" alt="{{ $pageValue('product_name', 'Product image') }}" class="w-full h-80 object-cover rounded-3xl">
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

  <!-- Features -->
  <section id="features" class="bg-white py-14">
    <div class="max-w-6xl mx-auto px-4">
      <h3 class="text-3xl font-bold text-center mb-10">Why Customers Love It</h3>
      <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-5">
        <div class="p-6 rounded-2xl bg-slate-50 border">
          <div class="text-3xl mb-4">✅</div>
          <h4 class="font-bold text-lg mb-2">Health Tracking</h4>
          <p class="text-slate-600 text-sm">Heart rate, sleep, steps and calories.</p>
        </div>
        <div class="p-6 rounded-2xl bg-slate-50 border">
          <div class="text-3xl mb-4">🚚</div>
          <h4 class="font-bold text-lg mb-2">Fast Delivery</h4>
          <p class="text-slate-600 text-sm">Delivery within 24-72 hours.</p>
        </div>
        <div class="p-6 rounded-2xl bg-slate-50 border">
          <div class="text-3xl mb-4">🛡️</div>
          <h4 class="font-bold text-lg mb-2">Premium Quality</h4>
          <p class="text-slate-600 text-sm">Durable body with modern design.</p>
        </div>
        <div class="p-6 rounded-2xl bg-slate-50 border">
          <div class="text-3xl mb-4">🔁</div>
          <h4 class="font-bold text-lg mb-2">Easy Return</h4>
          <p class="text-slate-600 text-sm">7-day replacement support.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Product Details + Price -->
  <section class="max-w-6xl mx-auto px-4 py-14 grid md:grid-cols-2 gap-8 items-center">
    <div>
      <h3 class="text-3xl font-bold mb-5">Perfect For Daily Life</h3>
      <p class="text-slate-600 mb-5">
        Office, gym, walking, running, or casual use—{{ $pageValue('product_name', 'SmartFit Watch') }} helps you stay connected and active all day.
      </p>
      <ul class="space-y-3 text-slate-700">
        <li>✓ Bluetooth calling support</li>
        <li>✓ 7 days battery backup</li>
        <li>✓ Water-resistant design</li>
        <li>✓ Compatible with Android & iPhone</li>
      </ul>
    </div>

    <div class="bg-slate-900 text-white rounded-3xl p-8">
      <p class="text-slate-300 line-through text-xl">{{ $pageValue('price_old', '৳3,990') }}</p>
      <p class="text-5xl font-extrabold my-2">{{ $pageValue('price_now', '৳2,490') }}</p>
      <p class="text-emerald-300 font-semibold mb-6">{{ $pageValue('save_text', 'Save ৳1,500 today') }}</p>
      <a href="#order" class="block text-center bg-white text-slate-900 py-4 rounded-xl font-extrabold">
        {{ $pageValue('cta_primary_text', 'Order Now') }}
      </a>
    </div>
  </section>

  <!-- Reviews -->
  <section class="bg-white py-14">
    <div class="max-w-6xl mx-auto px-4">
      <h3 class="text-3xl font-bold text-center mb-8">Customer Reviews</h3>
      <div class="grid md:grid-cols-3 gap-5">
        <div class="p-6 rounded-2xl border bg-slate-50">
          <div class="text-amber-500 mb-3">★★★★★</div>
          <p class="text-slate-700">“Battery backup khub valo. Design premium.”</p>
          <p class="font-bold mt-4">Verified Customer</p>
        </div>
        <div class="p-6 rounded-2xl border bg-slate-50">
          <div class="text-amber-500 mb-3">★★★★★</div>
          <p class="text-slate-700">“Delivery fast chilo, product exactly same.”</p>
          <p class="font-bold mt-4">Verified Customer</p>
        </div>
        <div class="p-6 rounded-2xl border bg-slate-50">
          <div class="text-amber-500 mb-3">★★★★★</div>
          <p class="text-slate-700">“Fitness tracking er jonno perfect.”</p>
          <p class="font-bold mt-4">Verified Customer</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Order Form -->
  <section id="order" class="max-w-3xl mx-auto px-4 py-14">
    <div class="bg-white rounded-3xl shadow-xl p-8 border">
      <h3 class="text-3xl font-bold text-center mb-2">Place Your Order</h3>
      <p class="text-center text-slate-600 mb-8">{{ $pageValue('order_notice', 'Cash on delivery available all over Bangladesh') }}</p>
      <form class="space-y-4">
        <input class="w-full border rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-slate-900" type="text" placeholder="Your Name" />
        <input class="w-full border rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-slate-900" type="tel" placeholder="Phone Number" />
        <textarea class="w-full border rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-slate-900" rows="4" placeholder="Delivery Address"></textarea>
        <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-xl font-extrabold">
          📞 {{ $pageValue('order_button_text', 'Confirm Order') }}
        </button>
      </form>
    </div>
  </section>

  <!-- FAQ -->
  <section class="max-w-4xl mx-auto px-4 pb-14">
    <h3 class="text-3xl font-bold text-center mb-8">FAQ</h3>
    <div class="space-y-4">
      <div class="bg-white border rounded-2xl p-5">
        <h4 class="font-bold mb-2">Delivery kotodin lage?</h4>
        <p class="text-slate-600">Usually 24-72 hours, location er upor depend kore.</p>
      </div>
      <div class="bg-white border rounded-2xl p-5">
        <h4 class="font-bold mb-2">Cash on delivery ache?</h4>
        <p class="text-slate-600">Yes, all over Bangladesh cash on delivery available.</p>
      </div>
      <div class="bg-white border rounded-2xl p-5">
        <h4 class="font-bold mb-2">Return policy ache?</h4>
        <p class="text-slate-600">Yes, 7-day replacement support available.</p>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="text-center py-8 text-slate-500 text-sm border-t bg-white">
    {{ $pageValue('footer_text', '© 2026 SmartFit Watch. All rights reserved.') }}
  </footer>
</body>
</html>
