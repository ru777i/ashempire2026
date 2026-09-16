@props([
    'name' => 'files',
    'label' => null,
    'multiple' => false,
    'accept' => null,
    'required' => false,
    'help' => null,
])

<div class="form-group relative h-auto {{ $attributes->get('class') }}" {{ $attributes->except('class') }}>
    @if ($label)
        <label for="{{ $name }}">{{ $label }} @if ($required)
                <span style="color:red">*</span>
            @endif
        </label>
    @endif

    <input wire:model="{{ $name }} {{ $multiple ? '[]' : '' }}" type="file" id="{{ $name }}" {{ $multiple ? 'multiple' : '' }}
        @if ($accept) accept="{{ $accept }}" @endif {{ $required ? 'required' : '' }}
        {{ $attributes->class('form-control-file') }}
        onchange="window.__fileInputPreview && window.__fileInputPreview(event, '{{ $name }}')" />
    <x-heroicon-o-document
        class="w-5 h-5 text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none" />
    <div id="{{ $name }}-preview" class="file-input-preview"
        style="margin-top:.75rem; display:flex; gap:.75rem; flex-wrap:wrap"></div>

    @error($name ?? false)
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
    @if ($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endif
</div>
{{-- 
@once
    <script>
        window.__fileInputPreview = function(event, name) {
            const input = event.target;
            const preview = document.getElementById(name + '-preview');

            if (!preview) {
                return;
            }

            preview.innerHTML = '';

            const files = Array.from(input.files || []);
            if (!files.length) {
                return;
            }

            files.forEach((file) => {
                const item = document.createElement('div');
                item.style.minWidth = '100px';
                item.style.maxWidth = '180px';
                item.style.display = 'flex';
                item.style.flexDirection = 'column';
                item.style.alignItems = 'center';
                item.style.padding = '0.35rem';
                item.style.border = '1px solid #e5e7eb';
                item.style.borderRadius = '0.5rem';
                item.style.background = '#f9fafb';

                const title = document.createElement('div');
                title.textContent = file.name;
                title.style.fontSize = '0.75rem';
                title.style.textAlign = 'center';
                title.style.wordBreak = 'break-word';
                title.style.marginTop = '0.35rem';

                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.style.width = '140px';
                    img.style.height = '100px';
                    img.style.objectFit = 'cover';
                    img.style.borderRadius = '0.5rem';
                    img.style.border = '1px solid #d1d5db';

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(file);

                    item.appendChild(img);
                }

                item.appendChild(title);
                preview.appendChild(item);
            });
        };
    </script>
@endonce --}}
