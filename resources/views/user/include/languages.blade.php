

  <div class="language-header py-3 px-3 mb-4">
      <div class="form-group d-flex flex-wrap justify-content-start align-items-center gap-2">
          @foreach ($languages as $lang)
              <label class="language-checkbox mb-0">
                  <input type="checkbox" @checked($lang->id == $defaultLang->id || $lang->is_added == true) onchange="toggleLanguage({{ $lang->id }})">
                  {{ $lang->name }}
              </label>
          @endforeach
      </div>
  </div>
