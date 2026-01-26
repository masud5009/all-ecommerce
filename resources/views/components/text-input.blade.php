<div class="col-lg-{{ $col }}">
    <div class="form-group">
        @if ($label)
            <label>{{ $label }} <span class="text-danger">{{ $required }}</span></label>
        @endif
        @switch($type)
            @case('text')
                <input {{ $attribute }} type="{{ $type }}" placeholder="{{ __($placeholder) }}"
                    name="{{ $name }}" value="{{ $value }}"
                    class="form-control {{ $action == 'store' ? 'err_' . $name : 'editErr_' . $name }}"
                    @if ($action == 'edit') id="in_{{ $name }}" @endif>
                <p id="{{ $action == 'store' ? 'err_' . $name : 'editErr_' . $name }}" class="text-danger em"></p>
            @break

            @case('password')
                <input {{ $attribute }} type="{{ $type }}" placeholder="{{ __($placeholder) }}"
                    name="{{ $name }}" value="{{ $value }}"
                    class="form-control {{ $action == 'store' ? 'err_' . $name : 'editErr_' . $name }}"
                    @if ($action == 'edit') id="in_{{ $name }}" @endif>
                <p id="{{ $action == 'store' ? 'err_' . $name : 'editErr_' . $name }}" class="text-danger em"></p>
            @break

            @case('number')
                <input {{ $attribute }} type="{{ $type }}" placeholder="{{ __($placeholder) }}"
                    name="{{ $name }}" min="0" value="{{ $value }}"
                    class="form-control {{ $action == 'store' ? 'err_' . $name : 'editErr_' . $name }}"
                    @if ($action == 'edit') id="in_{{ $name }}" @endif>
                <p id="{{ $action == 'store' ? 'err_' . $name : 'editErr_' . $name }}" class="text-danger em"></p>
            @break

            @case('date')
                <input {{ $attribute }} type="text" placeholder="{{ __($placeholder) }}" name="{{ $name }}"
                    value="{{ $value }}"
                    class="form-control flatpickr {{ $action == 'store' ? 'err_' . $name : 'editErr_' . $name }}"
                    @if ($action == 'edit') id="in_{{ $name }}" @endif>
                <p id="{{ $action == 'store' ? 'err_' . $name : 'editErr_' . $name }}" class="text-danger em"></p>
            @break

            @case('tagsinput')
                <input {{ $attribute }} type="text" data-role="tagsinput" class="form-control"
                    @if ($action == 'edit') id="in_{{ $name }}" @endif name="{{ $name }}"
                    value="{{ $value }}">
            @break

            @case('editor')
                <textarea name="{{ $name }}" rows="6"
                    @if ($action == 'edit') id="in_{{ $name }}" @endif
                    class="form-control editor summernote {{ $action == 'store' ? 'err_' . $name : 'editErr_' . $name }}">{{ $value }}</textarea>
                <p id="{{ $action == 'store' ? 'err_' . $name : 'editErr_' . $name }}" class="text-danger em"></p>
            @break

            @case('select')
                <select name="{{ $name }}"
                    class="form-select {{ $action == 'store' ? 'err_' . $name : 'editErr_' . $name }}"
                    @if ($action == 'edit') id="in_{{ $name }}" @endif>
                    <option selected disabled>{{ __($placeholder) }}</option>
                    @foreach ($dataInfo as $category)
                        <option value="{{ $category->id }}" @selected($category->id == $value)>{{ $category->name }}</option>
                    @endforeach
                </select>
                <p id="{{ $action == 'store' ? 'err_' . $name : 'editErr_' . $name }}" class="text-danger em"></p>
            @break

            @case('radio')
                <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                        <input type="radio" name="{{ $name }}" value="1" class="selectgroup-input"
                            @checked($value == 1)>
                        <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>

                    <label class="selectgroup-item">
                        <input type="radio" name="{{ $name }}" value="0" class="selectgroup-input"
                            @checked($value == 0)>
                        <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                </div>
                <p id="{{ $action == 'store' ? 'err_' . $name : 'editErr_' . $name }}" class="text-danger em"></p>
            @break

            @case('custom-select')
                <select name="{{ $name }}"
                    class="form-select {{ $action == 'store' ? 'err_' . $name : 'editErr_' . $name }}"
                    @if ($action == 'edit') id="in_{{ $name }}" @endif>
                    @if ($placeholder)
                        <option selected disabled>{{ __($placeholder) }}</option>
                    @endif
                    @foreach ($dataInfo as $key => $data)
                        <option value="{{ $key }}" @selected($key == $value)>{{ $data }}</option>
                    @endforeach
                </select>
                <p id="{{ $action == 'store' ? 'err_' . $name : 'editErr_' . $name }}" class="text-danger em"></p>
            @break

            @case('textarea')
                <textarea name="{{ $name }}" rows="4" class="form-control"
                    @if ($action == 'edit') id="in_{{ $name }}" @endif>{{ $value }}</textarea>
                <p id="{{ $action == 'store' ? 'err_' . $name : 'editErr_' . $name }}" class="text-danger em"></p>
            @break

            @default
                <input {{ $attribute }} type="text" placeholder="{{ __($placeholder) }}" name="{{ $name }}"
                    value="{{ $value }}"
                    class="form-control {{ $action == 'store' ? 'err_' . $name : 'editErr_' . $name }}"
                    @if ($action == 'edit') id="in_{{ $name }}" @endif>
                <p id="{{ $action == 'store' ? 'err_' . $name : 'editErr_' . $name }}" class="text-danger em"></p>
        @endswitch
    </div>
</div>

{{--
<div class="col-lg-12">
    <div class="form-group">
        <label>{{ __('Name') }} <span class="text-danger">*</span></label>
        <input type="text" placeholder="{{ __('Enter category name') }}" name="name" value="{{ old('name') }}"
            class="form-control err_name">
        <p id="err_name" class="text-danger em"></p>
    </div>
</div> --}}
