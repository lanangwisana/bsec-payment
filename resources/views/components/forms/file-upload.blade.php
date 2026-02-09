@props(['label', 'name', 'required' => false, 'accept' => 'image/*', 'error' => null, 'help' => null])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">
        {{ $label }}
        @if($required) <span class="text-danger">*</span> @endif
    </label>
    
    <input type="file" 
           name="{{ $name }}" 
           id="{{ $name }}"
           class="form-control @error($name) is-invalid @enderror"
           accept="{{ $accept }}"
           {{ $required ? 'required' : '' }}
           {{ $attributes }}>
    
    @if($help)
    <div class="form-text">{{ $help }}</div>
    @endif
    
    @error($name)
    <div class="invalid-feedback d-block">
        {{ $message }}
    </div>
    @enderror
    
    <div id="{{ $name }}_preview" class="mt-2 d-none">
        <img src="" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('{{ $name }}').addEventListener('change', function(e) {
        const preview = document.getElementById('{{ $name }}_preview');
        const file = e.target.files[0];
        
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.querySelector('img').src = e.target.result;
                preview.classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('d-none');
        }
    });
</script>
@endpush