<div class="language-header">
    <div class="form-group">
        @foreach ($languages as $lang)
            <label>
                <input type="checkbox" @checked($lang->id == $defaultLang->id || $lang->is_added == true)
                    onchange="toggleLanguage({{ $lang->id }})">{{ $lang->name }}
            </label>
        @endforeach
    </div>
</div>
