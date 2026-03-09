<div class="language-header">
    <div class="form-group">
        @foreach ($languages as $lang)
            <label>
                <input type="checkbox" @checked($lang->is_default == 1 || $lang->is_added == true)
                    onchange="toggleLanguage({{ $lang->id }})">{{ $lang->name }}
            </label>
        @endforeach
    </div>
</div>
