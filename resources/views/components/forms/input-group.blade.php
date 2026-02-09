@props(['label', 'name', 'type' => 'text', 'value' => '', 'required' => false, 'error' => null, 'help' => null])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">
        {{ $label }}
        @if($required) <span class="text-danger">*</span> @endif
    </label>
    
    <div class="input-group">
        {{ $slot }}
        
        <input type="{{ $type }}" 
               name="{{ $name }}" 
               id="{{ $name }}"
               value="{{ old($name, $value) }}"
               class="form-control @error($name) is-invalid @enderror"
               {{ $required ? 'required' : '' }}
               {{ $attributes }}>
    </div>
    
    @if($help)
    <div class="form-text">{{ $help }}</div>
    @endif
    
    @error($name)
    <div class="invalid-feedback d-block">
        {{ $message }}
    </div>
    @enderror
</div>