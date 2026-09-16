@props(['name', 'label'])
<div class=" flex flex-col space-y-1 dark:text-black">
    @if ($label ?? false)
 <x-form.label name="{{ $label }}">{{ $label }}</x-form.label>
    @endif

    <select wire:model.live="{{ $name ?? '' }}"  id="{{ $name ?? '' }}"
        class="outline-none border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none focus:ring-offset-2 focus:ring-offset-gray-100 rounded-md py-2 px-3 text-sm w-full"
        {{ $attributes }}>

           {{ $slot }}
    </select>
    @error($name ?? false)
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>
