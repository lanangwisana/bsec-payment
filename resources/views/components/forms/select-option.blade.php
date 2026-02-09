@props(['label', 'name', 'options' => [], 'value' => '', 'required' => false, 'error' => null, 'placeholder' => 'Pilih...'])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">
        {{ $label }}
        @if($required) <span class="text-danger">*</span> @endif
    </label>
    
    <select name="{{ $name }}" 
            id="{{ $name }}"
            class="form-select @error($name) is-invalid @enderror"
            {{ $required ? 'required' : '' }}
            {{ $attributes }}>
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $key => $option)
            @if(is_array($option))
                <option value="{{ $key }}" 
                        {{ old($name, $value) == $key ? 'selected' : '' }}>
                    {{ $option['label'] }}
                </option>
            @else
                <option value="{{ $key }}" 
                        {{ old($name, $value) == $key ? 'selected' : '' }}>
                    {{ $option }}
                </option>
            @endif
        @endforeach
    </select>
    
    @error($name)
    <div class="invalid-feedback d-block">
        {{ $message }}
    </div>
    @enderror
</div>