@props(['name', 'label','value'])

<div wire:model.defer="{{ $name }}" class="flex items-center space-x-2 {{ $attributes->get('class') }}" {{ $attributes->except('class') }}>
    <input type="checkbox" id="{{ $name }}" wire:model="{{ $name }}" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" value="{{ $value }}" {{ $attributes->except('class') }}>
    <label for="{{ $name }}" class="text-sm font-medium text-gray-700">{{ $label }}</label>
    @error($name)
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
    <!-- The whole future lies in uncertainty: live immediately. - Seneca -->
</div>
