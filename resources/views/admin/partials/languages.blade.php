<div class="d-flex align-items-center h-100">
    <select name="language" id="languageSelect" class="form-select form-select-sm w-100">
        @foreach (app('languages') as $language)
            <option value="{{ $language->code }}" {{ $language->code == $defaultLanguage->code ? 'selected' : '' }}>
                {{ $language->name }}
            </option>
        @endforeach
    </select>
</div>
