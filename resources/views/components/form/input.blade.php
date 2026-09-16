@props(['name', 'icon', 'type' => 'text', 'true', 'aff' => 'true','label'])

<div class="flex flex-col space-y-1 dark:text-black">
    @if ($aff ?? false)
        <x-form.label name="{{ $name }}"> {{ $label }}</x-form.label>
    @endif

    <div class="relative flex ">
        @if ($icon ?? false)
            <div class=" absolute flex inset-y-0 left-0 pl-3 items-center pointer-events-none">
                <x-dynamic-component :component="'heroicon-o-' . $icon" class="h-5 w-5 text-gray-400" />
            </div>
        @endif
        <input type="{{ $type }}" wire:model.defer="{{ $name ?? '' }}"
            {{ $attributes->class([
                ' w-full  pl-10 pr-4 border border-gray-400 outline-blue-100 py-2 rounded-md focus:ring-4 focus:ring-blue-500 focus:border-blue-500',
                'pl-10' => $icon ?? false,
            ]) }} />
    </div>
    @error($name ?? false)
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>
