<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  @php
    $pageData = $pageData ?? [];
    $pageValue = function ($key, $default = '') use ($pageData) {
        $value = data_get($pageData, $key);
        return filled($value) ? $value : $default;
    };
  @endphp
  <title>{{ $pageValue('page_title', 'NutriPure — Premium Supplements') }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <style>
    :root {
      --cream: #faf7f2;
      --warm-white: #f5f0e8;
      --sage: #7a9e7e;
      --sage-light: #a8c5ac;
      --sage-dark: #4f7a54;
      --charcoal: #1e1e1e;
      --muted: #8a8580;
      --gold: #c9a84c;
      --gold-light: #e8d5a3;
    }

    * { scroll-behavior: smooth; }

    body {
      font-family: 'DM Sans', sans-serif;
      font-weight: 400;
      background-color: var(--cream);
      color: var(--charcoal);
      overflow-x: hidden;
    }

    p, li, span, div {
      font-weight: 400;
    }

    .font-display { font-family: 'Cormorant Garamond', serif; }

    /* Grain texture overlay */
    body::before {
      content: '';
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
      pointer-events: none;
      z-index: 9999;
      opacity: 0.4;
    }

    /* Nav */
    nav {
      background: rgba(250, 247, 242, 0.85);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid rgba(122, 158, 126, 0.15);
    }

    .nav-link {
      font-size: 0.8rem;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      color: var(--muted);
      transition: color 0.3s;
    }
    .nav-link:hover { color: var(--sage-dark); }

    /* Hero */
    .hero-section {
      min-height: 100vh;
      background: linear-gradient(160deg, #faf7f2 0%, #f0ebe0 50%, #e8f0e9 100%);
      position: relative;
      overflow: hidden;
    }

    .hero-circle {
      position: absolute;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(122,158,126,0.12) 0%, transparent 70%);
    }

    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      background: rgba(122, 158, 126, 0.1);
      border: 1px solid rgba(122, 158, 126, 0.3);
      border-radius: 100px;
      padding: 0.35rem 0.9rem;
      font-size: 0.7rem;
      letter-spacing: 0.15em;
      text-transform: uppercase;
      color: var(--sage-dark);
    }

    .product-card-hero {
      background: white;
      border-radius: 24px;
      box-shadow: 0 30px 80px rgba(0,0,0,0.08), 0 8px 20px rgba(0,0,0,0.04);
      position: relative;
      overflow: hidden;
    }

    .product-card-hero::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--sage), var(--gold));
    }

    /* Floating elements */
    .float-tag {
      animation: float 4s ease-in-out infinite;
    }
    .float-tag:nth-child(2) { animation-delay: 1.5s; }
    .float-tag:nth-child(3) { animation-delay: 3s; }

    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
    }

    /* Marquee */
    .marquee-track {
      display: flex;
      gap: 3rem;
      animation: marquee 20s linear infinite;
      white-space: nowrap;
    }
    @keyframes marquee {
      from { transform: translateX(0); }
      to { transform: translateX(-50%); }
    }

    /* Stats */
    .stat-card {
      background: white;
      border: 1px solid rgba(122,158,126,0.15);
      border-radius: 16px;
      transition: transform 0.3s, box-shadow 0.3s;
    }
    .stat-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 20px 40px rgba(0,0,0,0.06);
    }

    /* Ingredients */
    .ingredient-pill {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      background: var(--warm-white);
      border: 1px solid rgba(122,158,126,0.2);
      border-radius: 100px;
      padding: 0.5rem 1.1rem;
      font-size: 0.82rem;
      font-weight: 500;
      color: var(--charcoal);
      transition: all 0.3s;
    }
    .ingredient-pill:hover {
      background: rgba(122,158,126,0.08);
      border-color: var(--sage);
    }

    /* Product variants */
    .variant-btn {
      border: 2px solid rgba(122,158,126,0.2);
      border-radius: 12px;
      padding: 0.6rem 1.2rem;
      font-size: 0.82rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.2s;
      background: white;
    }
    .variant-btn:hover, .variant-btn.active {
      border-color: var(--sage);
      background: rgba(122,158,126,0.06);
    }
    .variant-btn.active { color: var(--sage-dark); }

    /* CTA Button */
    .btn-primary {
      background: linear-gradient(135deg, var(--sage-dark) 0%, var(--sage) 100%);
      color: white;
      border-radius: 100px;
      padding: 1rem 2.5rem;
      font-size: 0.85rem;
      font-weight: 600;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      transition: all 0.3s;
      box-shadow: 0 8px 24px rgba(79, 122, 84, 0.3);
      cursor: pointer;
      border: none;
      display: inline-block;
    }
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 32px rgba(79, 122, 84, 0.4);
    }

    .btn-outline {
      border: 2px solid var(--sage);
      color: var(--sage-dark);
      border-radius: 100px;
      padding: 0.85rem 2rem;
      font-size: 0.82rem;
      font-weight: 600;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      transition: all 0.3s;
      cursor: pointer;
      background: transparent;
      display: inline-block;
    }
    .btn-outline:hover {
      background: rgba(122,158,126,0.06);
    }

    /* Testimonial */
    .testimonial-card {
      background: white;
      border-radius: 20px;
      border: 1px solid rgba(122,158,126,0.12);
      transition: all 0.3s;
    }
    .testimonial-card:hover {
      box-shadow: 0 16px 40px rgba(0,0,0,0.06);
      transform: translateY(-2px);
    }

    /* FAQ */
    .faq-item {
      border-bottom: 1px solid rgba(122,158,126,0.15);
    }
    .faq-btn {
      width: 100%;
      text-align: left;
      background: none;
      border: none;
      cursor: pointer;
      padding: 1.2rem 0;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-family: 'DM Sans', sans-serif;
      font-size: 0.95rem;
      font-weight: 500;
      color: var(--charcoal);
    }
    .faq-icon {
      width: 24px; height: 24px;
      border: 1.5px solid var(--sage);
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
      transition: all 0.3s;
      color: var(--sage-dark);
      font-size: 1rem;
    }
    .faq-answer {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.4s ease, padding 0.3s;
      font-size: 0.88rem;
      color: var(--muted);
      line-height: 1.7;
    }
    .faq-answer.open {
      max-height: 200px;
      padding-bottom: 1.2rem;
    }
    .faq-btn.open .faq-icon {
      background: var(--sage);
      color: white;
      transform: rotate(45deg);
    }

    /* Section fade-in */
    .reveal {
      opacity: 0;
      transform: translateY(28px);
      transition: opacity 0.7s ease, transform 0.7s ease;
    }
    .reveal.visible {
      opacity: 1;
      transform: translateY(0);
    }

    /* Product image placeholder */
    .product-img-wrap {
      background: linear-gradient(135deg, #eef5ef 0%, #e0ece1 100%);
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* Footer */
    footer {
      background: var(--charcoal);
      color: rgba(255,255,255,0.6);
    }

    /* Scrollbar */
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: var(--cream); }
    ::-webkit-scrollbar-thumb { background: var(--sage-light); border-radius: 10px; }
  </style>
</head>
<body>

<!-- ========== NAVBAR ========== -->
<nav class="fixed top-0 left-0 right-0 z-50">
  <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
    <a href="#" class="font-display text-2xl font-semibold tracking-wide text-[#1e1e1e]">
      {{ $pageValue('brand_name', 'NutriPure') }}
    </a>
    <div class="hidden md:flex items-center gap-8">
      <a href="#about" class="nav-link">About</a>
      <a href="#ingredients" class="nav-link">Ingredients</a>
      <a href="#reviews" class="nav-link">Reviews</a>
      <a href="#faq" class="nav-link">FAQ</a>
    </div>
    <a href="#buy" class="btn-primary text-sm py-2.5 px-5">{{ $pageValue('primary_cta_text', 'Order Now') }}</a>
  </div>
</nav>

<!-- ========== HERO ========== -->
<section class="hero-section flex items-center pt-20" id="about">
  <!-- Background circles -->
  <div class="hero-circle w-96 h-96 -top-20 -right-20 opacity-60"></div>
  <div class="hero-circle w-64 h-64 bottom-10 left-10 opacity-40"></div>

  <div class="max-w-6xl mx-auto px-6 py-16 grid md:grid-cols-2 gap-16 items-center w-full">
    <!-- Left: Text -->
    <div class="space-y-6">
      <div class="hero-badge">
        <span style="color:var(--gold)">✦</span>
        {{ $pageValue('hero_badge', 'Clinically Formulated · 100% Natural') }}
      </div>
      <h1 class="font-display text-6xl md:text-7xl font-normal leading-tight">
        {!! nl2br(e($pageValue('hero_title', "Fuel Your\nBest Self\nNaturally"))) !!}
      </h1>
      <p class="text-base leading-relaxed" style="color:var(--muted); max-width:420px">
        {{ $pageValue('hero_description', 'Premium plant-based supplements crafted with science-backed ingredients. No fillers, no compromise — just pure performance.') }}
      </p>

      <!-- Rating -->
      <div class="flex items-center gap-3">
        <div class="flex gap-0.5">
          <span class="text-yellow-400 text-lg">★★★★★</span>
        </div>
        <span class="text-sm font-medium">4.9/5</span>
        <span class="text-sm" style="color:var(--muted)">from 2,400+ reviews</span>
      </div>

      <!-- CTA Group -->
      <div class="flex flex-wrap items-center gap-4 pt-2">
        <a href="#buy" class="btn-primary">{{ $pageValue('primary_cta_text', 'Shop Now — Save 20%') }}</a>
        <a href="#ingredients" class="btn-outline">{{ $pageValue('secondary_cta_text', 'See Ingredients') }}</a>
      </div>

      <!-- Trust badges -->
      <div class="flex flex-wrap gap-4 pt-4">
        <div class="flex items-center gap-1.5 text-xs" style="color:var(--muted)">
          <span style="color:var(--sage)">✓</span> GMP Certified
        </div>
        <div class="flex items-center gap-1.5 text-xs" style="color:var(--muted)">
          <span style="color:var(--sage)">✓</span> Lab Tested
        </div>
        <div class="flex items-center gap-1.5 text-xs" style="color:var(--muted)">
          <span style="color:var(--sage)">✓</span> Vegan Friendly
        </div>
        <div class="flex items-center gap-1.5 text-xs" style="color:var(--muted)">
          <span style="color:var(--sage)">✓</span> 30-Day Guarantee
        </div>
      </div>
    </div>

    <!-- Right: Product Card -->
    <div class="relative flex justify-center">
      <!-- Floating tags -->
      <div class="float-tag absolute -left-6 top-12 bg-white rounded-2xl shadow-lg px-4 py-3 flex items-center gap-2 z-10">
        <span class="text-2xl">🌿</span>
        <div>
          <div class="text-xs font-semibold">100% Natural</div>
          <div class="text-xs" style="color:var(--muted)">Zero Additives</div>
        </div>
      </div>
      <div class="float-tag absolute -right-4 top-28 bg-white rounded-2xl shadow-lg px-4 py-3 flex items-center gap-2 z-10">
        <span class="text-2xl">⚡</span>
        <div>
          <div class="text-xs font-semibold">Fast Absorbing</div>
          <div class="text-xs" style="color:var(--muted)">Bioavailable</div>
        </div>
      </div>
      <div class="float-tag absolute -left-2 bottom-12 bg-white rounded-2xl shadow-lg px-4 py-3 flex items-center gap-2 z-10">
        <span class="text-2xl">🏅</span>
        <div>
          <div class="text-xs font-semibold">Award Winning</div>
          <div class="text-xs" style="color:var(--muted)">2024 Formula</div>
        </div>
      </div>

      <div class="product-card-hero p-8 max-w-sm w-full">
        <div class="product-img-wrap h-64 mb-6">
          @if (!empty($pageData['product_image']))
            <img src="{{ asset($pageData['product_image']) }}" alt="{{ $pageValue('product_name', 'Product image') }}" class="w-full h-full object-cover rounded-2xl" />
          @else
            <!-- Product SVG Illustration -->
            <svg viewBox="0 0 200 220" width="160" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect x="65" y="60" width="70" height="130" rx="14" fill="url(#bottleGrad)"/>
              <rect x="78" y="40" width="44" height="28" rx="8" fill="url(#neckGrad)"/>
              <rect x="74" y="28" width="52" height="18" rx="7" fill="#4f7a54"/>
              <rect x="72" y="90" width="56" height="75" rx="8" fill="white" opacity="0.9"/>
              <rect x="82" y="100" width="36" height="4" rx="2" fill="#7a9e7e"/>
              <rect x="78" y="110" width="44" height="6" rx="3" fill="#4f7a54"/>
              <rect x="83" y="121" width="34" height="3" rx="1.5" fill="#a8c5ac"/>
              <rect x="85" y="129" width="30" height="3" rx="1.5" fill="#a8c5ac"/>
              <ellipse cx="100" cy="148" rx="10" ry="6" fill="rgba(122,158,126,0.2)" transform="rotate(-20 100 148)"/>
              <path d="M94 148 Q100 140 106 148" stroke="#7a9e7e" stroke-width="1.5" fill="none"/>
              <defs>
                <linearGradient id="bottleGrad" x1="65" y1="60" x2="135" y2="190" gradientUnits="userSpaceOnUse">
                  <stop offset="0%" stop-color="#c8deca"/>
                  <stop offset="100%" stop-color="#8fba94"/>
                </linearGradient>
                <linearGradient id="neckGrad" x1="78" y1="40" x2="122" y2="68" gradientUnits="userSpaceOnUse">
                  <stop offset="0%" stop-color="#b8d4ba"/>
                  <stop offset="100%" stop-color="#7a9e7e"/>
                </linearGradient>
              </defs>
            </svg>
          @endif
        </div>
        <div class="text-center space-y-2">
          <p class="text-xs tracking-widest uppercase" style="color:var(--sage)">NutriPure Pro</p>
          <h3 class="font-display text-2xl font-semibold">{{ $pageValue('product_name', 'Daily Wellness Formula') }}</h3>
          <p class="text-sm" style="color:var(--muted)">{{ $pageValue('product_subtitle', '60 Capsules · 30 Day Supply') }}</p>
          <div class="pt-3 flex items-center justify-center gap-3">
            <span class="font-display text-3xl font-semibold" style="color:var(--sage-dark)">{{ $pageValue('price_now', '৳1,490') }}</span>
            <span class="text-sm line-through" style="color:var(--muted)">{{ $pageValue('price_old', '৳1,860') }}</span>
            <span class="text-xs bg-red-50 text-red-500 px-2 py-0.5 rounded-full font-medium">{{ $pageValue('discount_badge', '20% OFF') }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ========== MARQUEE STRIP ========== -->
<div class="py-4 overflow-hidden" style="background:var(--sage-dark)">
  <div class="flex" style="gap:0">
    <div class="marquee-track">
      <span class="text-white text-xs tracking-widest uppercase opacity-80">✦ Premium Quality</span>
      <span class="text-white text-xs tracking-widest uppercase opacity-80">✦ Free Shipping Over ৳2000</span>
      <span class="text-white text-xs tracking-widest uppercase opacity-80">✦ 30 Day Money Back</span>
      <span class="text-white text-xs tracking-widest uppercase opacity-80">✦ 2,400+ Happy Customers</span>
      <span class="text-white text-xs tracking-widest uppercase opacity-80">✦ Lab Tested & Certified</span>
      <span class="text-white text-xs tracking-widest uppercase opacity-80">✦ Premium Quality</span>
      <span class="text-white text-xs tracking-widest uppercase opacity-80">✦ Free Shipping Over ৳2000</span>
      <span class="text-white text-xs tracking-widest uppercase opacity-80">✦ 30 Day Money Back</span>
      <span class="text-white text-xs tracking-widest uppercase opacity-80">✦ 2,400+ Happy Customers</span>
      <span class="text-white text-xs tracking-widest uppercase opacity-80">✦ Lab Tested & Certified</span>
    </div>
  </div>
</div>

<!-- ========== STATS ========== -->
<section class="py-20 max-w-6xl mx-auto px-6 reveal">
  <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
    <div class="stat-card p-6 text-center">
      <div class="font-display text-4xl font-semibold" style="color:var(--sage-dark)">2.4K+</div>
      <div class="text-xs mt-1 tracking-wide uppercase" style="color:var(--muted)">Happy Customers</div>
    </div>
    <div class="stat-card p-6 text-center">
      <div class="font-display text-4xl font-semibold" style="color:var(--sage-dark)">4.9★</div>
      <div class="text-xs mt-1 tracking-wide uppercase" style="color:var(--muted)">Average Rating</div>
    </div>
    <div class="stat-card p-6 text-center">
      <div class="font-display text-4xl font-semibold" style="color:var(--sage-dark)">12+</div>
      <div class="text-xs mt-1 tracking-wide uppercase" style="color:var(--muted)">Key Nutrients</div>
    </div>
    <div class="stat-card p-6 text-center">
      <div class="font-display text-4xl font-semibold" style="color:var(--sage-dark)">0%</div>
      <div class="text-xs mt-1 tracking-wide uppercase" style="color:var(--muted)">Artificial Fillers</div>
    </div>
  </div>
</section>

<!-- ========== BENEFITS ========== -->
<section class="py-16 max-w-6xl mx-auto px-6 reveal">
  <div class="text-center mb-14">
    <p class="text-xs tracking-widest uppercase mb-3" style="color:var(--sage)">Why Choose Us</p>
    <h2 class="font-display text-5xl font-normal">Benefits That <em style="color:var(--sage-dark)">Actually Work</em></h2>
  </div>
  <div class="grid md:grid-cols-3 gap-6">
    <div class="p-8 rounded-2xl hover:shadow-lg transition-all" style="background:var(--warm-white)">
      <div class="text-4xl mb-5">⚡</div>
      <h3 class="font-display text-2xl font-semibold mb-3">Instant Energy Boost</h3>
      <p class="text-sm leading-relaxed" style="color:var(--muted)">Feel the difference from day one. Our rapid-absorb formula delivers sustained energy without jitters or crash.</p>
    </div>
    <div class="p-8 rounded-2xl hover:shadow-lg transition-all" style="background:rgba(122,158,126,0.07)">
      <div class="text-4xl mb-5">🛡️</div>
      <h3 class="font-display text-2xl font-semibold mb-3">Immune Defense</h3>
      <p class="text-sm leading-relaxed" style="color:var(--muted)">Fortified with Vitamin C, Zinc & Elderberry to keep your immune system strong and resilient year-round.</p>
    </div>
    <div class="p-8 rounded-2xl hover:shadow-lg transition-all" style="background:var(--warm-white)">
      <div class="text-4xl mb-5">🧠</div>
      <h3 class="font-display text-2xl font-semibold mb-3">Mental Clarity</h3>
      <p class="text-sm leading-relaxed" style="color:var(--muted)">Nootropic blend with Lion's Mane and Ashwagandha to sharpen focus and reduce mental fatigue.</p>
    </div>
    <div class="p-8 rounded-2xl hover:shadow-lg transition-all" style="background:rgba(122,158,126,0.07)">
      <div class="text-4xl mb-5">😴</div>
      <h3 class="font-display text-2xl font-semibold mb-3">Deep Recovery</h3>
      <p class="text-sm leading-relaxed" style="color:var(--muted)">Magnesium and adaptogens support deep, restorative sleep so you wake up refreshed every morning.</p>
    </div>
    <div class="p-8 rounded-2xl hover:shadow-lg transition-all" style="background:var(--warm-white)">
      <div class="text-4xl mb-5">💪</div>
      <h3 class="font-display text-2xl font-semibold mb-3">Muscle Support</h3>
      <p class="text-sm leading-relaxed" style="color:var(--muted)">Essential amino acids and B-vitamins to support lean muscle maintenance and faster recovery.</p>
    </div>
    <div class="p-8 rounded-2xl hover:shadow-lg transition-all" style="background:rgba(122,158,126,0.07)">
      <div class="text-4xl mb-5">🌸</div>
      <h3 class="font-display text-2xl font-semibold mb-3">Gut Health</h3>
      <p class="text-sm leading-relaxed" style="color:var(--muted)">Probiotic blend of 10 Billion CFU supports healthy digestion and a balanced gut microbiome.</p>
    </div>
  </div>
</section>

<!-- ========== INGREDIENTS ========== -->
<section class="py-20" style="background:var(--warm-white)" id="ingredients">
  <div class="max-w-6xl mx-auto px-6 reveal">
    <div class="grid md:grid-cols-2 gap-16 items-center">
      <div>
        <p class="text-xs tracking-widest uppercase mb-3" style="color:var(--sage)">What's Inside</p>
        <h2 class="font-display text-5xl font-normal mb-6">Pure Ingredients,<br/><em style="color:var(--sage-dark)">Zero Compromise</em></h2>
        <p class="text-sm leading-relaxed mb-8" style="color:var(--muted)">
          Every ingredient is carefully sourced, third-party tested, and used at clinically effective doses. No hidden fillers, no proprietary blends — full transparency, always.
        </p>
        <div class="flex flex-wrap gap-3">
          <div class="ingredient-pill">🌿 Ashwagandha KSM-66</div>
          <div class="ingredient-pill">🍄 Lion's Mane Extract</div>
          <div class="ingredient-pill">🫐 Elderberry 500mg</div>
          <div class="ingredient-pill">⚡ CoQ10 200mg</div>
          <div class="ingredient-pill">🌊 Omega-3 DHA/EPA</div>
          <div class="ingredient-pill">🦠 Probiotics 10B CFU</div>
          <div class="ingredient-pill">🌞 Vitamin D3 + K2</div>
          <div class="ingredient-pill">🔵 Magnesium Glycinate</div>
          <div class="ingredient-pill">💊 Zinc Bisglycinate</div>
          <div class="ingredient-pill">🍋 Vitamin C 1000mg</div>
        </div>
      </div>
      <div class="space-y-4">
        <!-- Nutrition fact style bars -->
        <div class="bg-white rounded-2xl p-6 border" style="border-color:rgba(122,158,126,0.15)">
          <h4 class="font-semibold text-sm mb-5 tracking-wide uppercase" style="color:var(--muted)">Key Nutrient Levels</h4>
          <div class="space-y-4">
            <div>
              <div class="flex justify-between text-sm mb-1.5">
                <span class="font-medium">Ashwagandha KSM-66</span>
                <span style="color:var(--sage-dark)" class="font-semibold">600mg</span>
              </div>
              <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full" style="width:90%; background:linear-gradient(90deg, var(--sage), var(--sage-dark))"></div>
              </div>
            </div>
            <div>
              <div class="flex justify-between text-sm mb-1.5">
                <span class="font-medium">Lion's Mane Extract</span>
                <span style="color:var(--sage-dark)" class="font-semibold">500mg</span>
              </div>
              <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full" style="width:75%; background:linear-gradient(90deg, var(--sage), var(--sage-dark))"></div>
              </div>
            </div>
            <div>
              <div class="flex justify-between text-sm mb-1.5">
                <span class="font-medium">Vitamin D3</span>
                <span style="color:var(--sage-dark)" class="font-semibold">5000 IU</span>
              </div>
              <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full" style="width:95%; background:linear-gradient(90deg, var(--gold-light), var(--gold))"></div>
              </div>
            </div>
            <div>
              <div class="flex justify-between text-sm mb-1.5">
                <span class="font-medium">Probiotics</span>
                <span style="color:var(--sage-dark)" class="font-semibold">10B CFU</span>
              </div>
              <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full" style="width:85%; background:linear-gradient(90deg, var(--sage), var(--sage-dark))"></div>
              </div>
            </div>
            <div>
              <div class="flex justify-between text-sm mb-1.5">
                <span class="font-medium">Magnesium Glycinate</span>
                <span style="color:var(--sage-dark)" class="font-semibold">400mg</span>
              </div>
              <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full" style="width:70%; background:linear-gradient(90deg, var(--sage), var(--sage-dark))"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="flex gap-3 text-xs" style="color:var(--muted)">
          <span>✓ No GMO</span>
          <span>✓ Gluten Free</span>
          <span>✓ Soy Free</span>
          <span>✓ Dairy Free</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ========== HOW TO USE ========== -->
<section class="py-20 max-w-6xl mx-auto px-6 reveal">
  <div class="text-center mb-14">
    <p class="text-xs tracking-widest uppercase mb-3" style="color:var(--sage)">Simple Routine</p>
    <h2 class="font-display text-5xl font-normal">How To <em style="color:var(--sage-dark)">Use It</em></h2>
  </div>
  <div class="grid md:grid-cols-4 gap-6 relative">
    <!-- connector line -->
    <div class="hidden md:block absolute top-10 left-24 right-24 h-px" style="background:linear-gradient(90deg, transparent, var(--sage-light), transparent)"></div>
    <div class="text-center relative">
      <div class="w-20 h-20 mx-auto mb-5 rounded-full flex items-center justify-center text-3xl relative z-10" style="background:var(--warm-white); border:2px solid rgba(122,158,126,0.2)">☀️</div>
      <div class="font-display text-3xl font-semibold mb-2" style="color:var(--sage-dark)">01</div>
      <h4 class="font-semibold mb-2">Morning</h4>
      <p class="text-xs leading-relaxed" style="color:var(--muted)">Take 2 capsules with breakfast for all-day energy and focus</p>
    </div>
    <div class="text-center relative">
      <div class="w-20 h-20 mx-auto mb-5 rounded-full flex items-center justify-center text-3xl relative z-10" style="background:var(--warm-white); border:2px solid rgba(122,158,126,0.2)">💧</div>
      <div class="font-display text-3xl font-semibold mb-2" style="color:var(--sage-dark)">02</div>
      <h4 class="font-semibold mb-2">Stay Hydrated</h4>
      <p class="text-xs leading-relaxed" style="color:var(--muted)">Drink a full glass of water to activate absorption</p>
    </div>
    <div class="text-center relative">
      <div class="w-20 h-20 mx-auto mb-5 rounded-full flex items-center justify-center text-3xl relative z-10" style="background:var(--warm-white); border:2px solid rgba(122,158,126,0.2)">🏃</div>
      <div class="font-display text-3xl font-semibold mb-2" style="color:var(--sage-dark)">03</div>
      <h4 class="font-semibold mb-2">Stay Active</h4>
      <p class="text-xs leading-relaxed" style="color:var(--muted)">Pair with regular movement for amplified results</p>
    </div>
    <div class="text-center relative">
      <div class="w-20 h-20 mx-auto mb-5 rounded-full flex items-center justify-center text-3xl relative z-10" style="background:var(--warm-white); border:2px solid rgba(122,158,126,0.2)">✨</div>
      <div class="font-display text-3xl font-semibold mb-2" style="color:var(--sage-dark)">04</div>
      <h4 class="font-semibold mb-2">See Results</h4>
      <p class="text-xs leading-relaxed" style="color:var(--muted)">Most customers notice improvements within 7–14 days</p>
    </div>
  </div>
</section>



<!-- ========== REVIEWS ========== -->
<section class="py-20 max-w-6xl mx-auto px-6 reveal" id="reviews">
  <div class="text-center mb-14">
    <p class="text-xs tracking-widest uppercase mb-3" style="color:var(--sage)">Real Results</p>
    <h2 class="font-display text-5xl font-normal">What Our <em style="color:var(--sage-dark)">Customers Say</em></h2>
  </div>
  <div class="grid md:grid-cols-3 gap-6">
    <div class="testimonial-card p-7">
      <div class="text-yellow-400 text-lg mb-4">★★★★★</div>
      <p class="text-sm leading-relaxed mb-5" style="color:var(--muted)">"I've tried so many supplements over the years, but NutriPure is genuinely different. My energy levels are through the roof and my focus at work has improved dramatically. 100% recommend!"</p>
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold" style="background:var(--sage)">R</div>
        <div>
          <div class="font-semibold text-sm">Rafique Ahmed</div>
          <div class="text-xs" style="color:var(--muted)">Verified Buyer · Dhaka</div>
        </div>
      </div>
    </div>
    <div class="testimonial-card p-7" style="background:rgba(122,158,126,0.04)">
      <div class="text-yellow-400 text-lg mb-4">★★★★★</div>
      <p class="text-sm leading-relaxed mb-5" style="color:var(--muted)">"Started the 3-bottle plan 2 months ago. Sleep quality is amazing, gut issues gone, and I feel more balanced overall. My whole family is now using it!"</p>
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold" style="background:var(--gold)">N</div>
        <div>
          <div class="font-semibold text-sm">Nadia Islam</div>
          <div class="text-xs" style="color:var(--muted)">Verified Buyer · Chittagong</div>
        </div>
      </div>
    </div>
    <div class="testimonial-card p-7">
      <div class="text-yellow-400 text-lg mb-4">★★★★★</div>
      <p class="text-sm leading-relaxed mb-5" style="color:var(--muted)">"As a gym-goer, I was skeptical about 'wellness' supplements. But after 3 weeks my recovery is noticeably faster and I feel sharper in every session. This is the real deal."</p>
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold" style="background:#5a7a8e">K</div>
        <div>
          <div class="font-semibold text-sm">Karim Hossain</div>
          <div class="text-xs" style="color:var(--muted)">Verified Buyer · Sylhet</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ========== GUARANTEE ========== -->
<section class="py-16 max-w-4xl mx-auto px-6 reveal">
  <div class="rounded-3xl p-12 text-center relative overflow-hidden" style="background:linear-gradient(135deg, var(--sage-dark) 0%, #3a5f3e 100%)">
    <div class="text-6xl mb-5">🛡️</div>
    <h2 class="font-display text-4xl font-normal text-white mb-4">30-Day Money Back <em>Guarantee</em></h2>
    <p class="text-sm leading-relaxed text-white opacity-80 max-w-lg mx-auto mb-8">
      We're so confident in NutriPure that if you're not completely satisfied within 30 days, we'll refund every taka — no questions asked.
    </p>
    <a href="#buy" class="inline-block bg-white font-semibold text-sm py-3 px-8 rounded-full" style="color:var(--sage-dark)">Claim Risk-Free Trial</a>
  </div>
</section>

<!-- ========== FAQ ========== -->
<section class="py-16 max-w-3xl mx-auto px-6 reveal" id="faq">
  <div class="text-center mb-12">
    <p class="text-xs tracking-widest uppercase mb-3" style="color:var(--sage)">Got Questions?</p>
    <h2 class="font-display text-5xl font-normal">Frequently <em style="color:var(--sage-dark)">Asked</em></h2>
  </div>
  <div id="faq-list">
    <div class="faq-item">
      <button class="faq-btn" onclick="toggleFaq(this)">
        When will I see results?
        <span class="faq-icon">+</span>
      </button>
      <div class="faq-answer">Most customers notice increased energy within 3–5 days and significant improvements in focus and sleep quality within 2 weeks of consistent use. Gut health improvements typically show within 30 days.</div>
    </div>
    <div class="faq-item">
      <button class="faq-btn" onclick="toggleFaq(this)">
        Are there any side effects?
        <span class="faq-icon">+</span>
      </button>
      <div class="faq-answer">NutriPure is formulated with natural ingredients and is generally well-tolerated. Some people may experience mild digestive changes when starting probiotics, which typically resolves within a few days. Consult your doctor if you have any medical conditions.</div>
    </div>
    <div class="faq-item">
      <button class="faq-btn" onclick="toggleFaq(this)">
        How should I take it?
        <span class="faq-icon">+</span>
      </button>
      <div class="faq-answer">Take 2 capsules every morning with breakfast and a full glass of water. Consistency is key — daily use for at least 30 days provides the best results.</div>
    </div>
    <div class="faq-item">
      <button class="faq-btn" onclick="toggleFaq(this)">
        Is it suitable for vegetarians/vegans?
        <span class="faq-icon">+</span>
      </button>
      <div class="faq-answer">Yes! NutriPure is 100% plant-based, vegan, and free from animal-derived ingredients. The capsule shell is made from plant cellulose, not gelatin.</div>
    </div>
    <div class="faq-item">
      <button class="faq-btn" onclick="toggleFaq(this)">
        How do I place an order in Bangladesh?
        <span class="faq-icon">+</span>
      </button>
      <div class="faq-answer">Simply click Order Now, choose your bundle, and complete checkout. We accept bKash, Nagad, Rocket, and all major cards. Delivery across Bangladesh within 2–4 business days. Cash on delivery available in Dhaka.</div>
    </div>
    <div class="faq-item">
      <button class="faq-btn" onclick="toggleFaq(this)">
        What is the return policy?
        <span class="faq-icon">+</span>
      </button>
      <div class="faq-answer">We offer a 30-day, no-questions-asked money-back guarantee. If you're unsatisfied for any reason, contact us and we'll process a full refund within 3–5 business days.</div>
    </div>
  </div>
</section>

<!-- ========== FINAL CTA ========== -->
<section class="py-20 text-center max-w-3xl mx-auto px-6 reveal">
  <p class="text-xs tracking-widest uppercase mb-4" style="color:var(--sage)">Limited Time Offer</p>
  <h2 class="font-display text-6xl font-normal mb-5">Start Your<br/><em style="color:var(--sage-dark)">Wellness Journey</em><br/>Today</h2>
  <p class="text-sm mb-8" style="color:var(--muted)">Join 2,400+ people already living better. First order gets 20% off.</p>
  <a href="#buy" class="btn-primary text-base px-10 py-4">Shop NutriPure Now</a>
  <div class="mt-6 flex justify-center gap-6 text-xs" style="color:var(--muted)">
    <span>✓ Free Shipping</span>
    <span>✓ 30-Day Guarantee</span>
    <span>✓ Secure Payment</span>
  </div>
</section>

<!-- ========== FOOTER ========== -->
<footer class="py-14">
  <div class="max-w-6xl mx-auto px-6">
    <div class="grid md:grid-cols-4 gap-10 mb-12">
      <div class="md:col-span-2">
        <div class="font-display text-3xl font-semibold text-white mb-4">{{ $pageValue('brand_name', 'NutriPure') }}</div>
        <p class="text-sm leading-relaxed opacity-60 max-w-xs">Premium plant-based supplements backed by science. Helping people across Bangladesh live healthier, more vibrant lives.</p>
      </div>
      <div>
        <h5 class="text-white font-semibold text-sm mb-4 tracking-wide">Quick Links</h5>
        <ul class="space-y-2 text-sm opacity-60">
          <li><a href="#about" class="hover:opacity-100 transition-opacity">About Us</a></li>
          <li><a href="#ingredients" class="hover:opacity-100 transition-opacity">Ingredients</a></li>
          <li><a href="#reviews" class="hover:opacity-100 transition-opacity">Reviews</a></li>
          <li><a href="#faq" class="hover:opacity-100 transition-opacity">FAQ</a></li>
        </ul>
      </div>
      <div>
        <h5 class="text-white font-semibold text-sm mb-4 tracking-wide">Contact</h5>
        <ul class="space-y-2 text-sm opacity-60">
          <li>📧 {{ $pageValue('footer_email', 'hello@nutripure.com.bd') }}</li>
          <li>📞 {{ $pageValue('footer_phone', '01700-000000') }}</li>
          <li>📍 {{ $pageValue('footer_address', 'Dhaka, Bangladesh') }}</li>
        </ul>
      </div>
    </div>
    <div class="border-t border-white border-opacity-10 pt-6 flex flex-col md:flex-row items-center justify-between gap-4">
      <p class="text-xs opacity-40">{{ $pageValue('footer_copyright', '© 2024 NutriPure Bangladesh. All rights reserved.') }}</p>
      <p class="text-xs opacity-40">Privacy Policy · Terms of Service · Refund Policy</p>
    </div>
  </div>
</footer>

<script>
  // FAQ toggle
  function toggleFaq(btn) {
    const answer = btn.nextElementSibling;
    const isOpen = answer.classList.contains('open');
    // close all
    document.querySelectorAll('.faq-answer').forEach(a => a.classList.remove('open'));
    document.querySelectorAll('.faq-btn').forEach(b => b.classList.remove('open'));
    // open clicked
    if (!isOpen) {
      answer.classList.add('open');
      btn.classList.add('open');
    }
  }

  // Scroll reveal
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.classList.add('visible');
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

  // Nav scroll shadow
  window.addEventListener('scroll', () => {
    const nav = document.querySelector('nav');
    nav.style.boxShadow = window.scrollY > 30 ? '0 4px 20px rgba(0,0,0,0.06)' : 'none';
  });
</script>
</body>
</html>
