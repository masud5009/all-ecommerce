 <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="50">
     <div class="card h-100 pricing-card {{ $package->recomended == 1 ? 'popular' : '' }}">
         @if ($package->recomended == 1)
             <div class="ribbon">Most popular</div>
         @endif
         <div class="card-body text-center p-4">
             <h5 class="card-title">{{ $package->title }}</h5>
             <div class="price my-3">
                 <span class="currency">$</span><span class="amount">{{ $package->price }}</span>
                 <small class=" ">/{{ $package->term }}</small>
             </div>
             <ul class="list-unstyled mt-3 mb-4 text-start">
                 <li><i class="fas fa-check"></i> 5 Projects</li>
                 <li><i class="fas fa-check"></i> Basic Analytics</li>
                 <li><i class="fas fa-check"></i> Email Support</li>
                 <li><i class="fas fa-check"></i> Access to Community Forum</li>
                 <li><i class="fas fa-xmark"></i> Team Collaboration</li>
                 <li><i class="fas fa-xmark"></i> API Access</li>
                 <li><i class="fas fa-xmark"></i> Priority Support</li>
             </ul>
             @php
                 $buttonClass = $package->recomended == 1 ? 'btn-primary' : 'btn-outline-primary';
             @endphp
             <a href="{{ route('frontend.register.view', ['id' => $package->id]) }}"
                 class="btn {{ $buttonClass }}">{{ __('Purchase') }}</a>
         </div>
     </div>
 </div>
