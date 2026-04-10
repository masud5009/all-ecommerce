@php
    $wrapperClass = $wrapperClass ?? 'mt-5';
    $theme = $theme ?? 'light';
    $recaptchaEnabled = !empty($websiteInfo->google_recaptcha_status) && !empty($websiteInfo->google_recaptcha_site_key);
@endphp

@if ($recaptchaEnabled)
    <div class="{{ $wrapperClass }}" data-recaptcha-block>
        <div class="js-google-recaptcha" data-sitekey="{{ $websiteInfo->google_recaptcha_site_key }}"
            data-theme="{{ $theme }}"></div>
        <p data-recaptcha-error
            class="{{ $errors->has('g-recaptcha-response') ? '' : 'hidden' }} mt-2 text-xs font-medium text-red-600">
            {{ $errors->first('g-recaptcha-response') }}
        </p>
    </div>
@endif
