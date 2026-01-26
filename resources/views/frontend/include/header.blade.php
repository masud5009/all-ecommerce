       <!-- NAVBAR (responsive, accessible) -->
       <nav class="navbar navbar-expand-lg  navbar-dark py-4 sticky-top" id="siteNavbar">
           <div class="container">
               <a class="navbar-brand fw-bold" href="index.html">
                   <img src="assets/images/logo.png" alt="" height="40">
               </a>
               <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu"
                   aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
                   <span class="navbar-toggler-icon"></span>
               </button>
               <div class="collapse navbar-collapse" id="navMenu">
                   @if (isset($menus))
                       <ul class="navbar-nav ms-auto align-items-lg-center">
                           @foreach ($menus as $menu)
                               @if (isset($menu['children']) && count($menu['children']) > 0)
                                   {{-- Dropdown parent --}}
                                   <li class="nav-item dropdown">
                                       <a class="nav-link dropdown-toggle" href="#"
                                           id="menuDropdown{{ $loop->index }}" role="button" data-bs-toggle="dropdown"
                                           aria-expanded="false">
                                           {{ $menu['title'] }}
                                       </a>
                                       <ul class="dropdown-menu" aria-labelledby="menuDropdown{{ $loop->index }}">
                                           @foreach ($menu['children'] as $child)
                                               @if ($child['type'] == 'prebuilt')
                                                   <li>
                                                       <a class="dropdown-item" href="{{ getHref($child['title']) }}">
                                                           {{ $child['title'] }}
                                                       </a>
                                                   </li>
                                               @else
                                                   <li>
                                                       <a class="dropdown-item" href="{{ $child['url'] }}">
                                                           {{ $child['title'] }}
                                                       </a>
                                                   </li>
                                               @endif
                                           @endforeach
                                       </ul>
                                   </li>
                               @else
                                   {{-- Single (no children) --}}
                                   @if ($menu['type'] == 'prebuilt')
                                       <li class="nav-item">
                                           <a class="nav-link" href="{{ getHref($menu['title']) }}">
                                               {{ $menu['title'] }}
                                           </a>
                                       </li>
                                   @else
                                       <li class="nav-item">
                                           <a class="nav-link" href="{{ $menu['url'] }}">
                                               {{ $menu['title'] }}
                                           </a>
                                       </li>
                                   @endif
                               @endif
                           @endforeach
                       </ul>
                   @endif

                   <div class="language px-3">
                       @if (!empty($currentLang))
                           <select class="nice-select" onchange="handleSelect(this)">
                               @foreach ($languages as $language)
                                   <option value="{{ $language->code }}"
                                       {{ $currentLang->code === $language->code ? 'selected' : '' }}>
                                       {{ $language->name }}</option>
                               @endforeach
                           </select>
                       @endif
                   </div>
                   <div class="dropdown px-3">
                       <button class="btn account-btn dropdown-toggle" type="button" id="dropdownMenuButton"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           @if (Auth::guard('web')->check())
                               {{ Auth::user()->name ?? __('Account') }}
                           @else
                               {{ __('Account') }}
                           @endif
                       </button>
                       <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                           @if (Auth::guard('web')->check())
                               <a class="dropdown-item" href="{{ route('user.dashboard') }}">Dashboard</a>
                               <a class="dropdown-item" href="{{ route('user.logout') }}">Logout</a>
                           @else
                               <a class="dropdown-item" href="{{ route('user.login') }}">Login</a>
                               <a class="dropdown-item" href="{{ route('user.signup') }}">Signup</a>
                           @endif
                       </div>
                   </div>
               </div>
           </div>
       </nav>
