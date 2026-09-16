@props([
    'name' => null,
    'id' => null,
    'label' => null,
    'value' => null,
    'placeholder' => null,
    'rows' => 4,
    'help' => null,
])

@php
    $id = $id ?? $name;
    $inputName = $name ?? $attributes->get('name');
    $value = old($inputName, $value);
    $hasError = $errors->has($inputName);
@endphp

<div class="mb-4">
    @if ($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-slate-700">
            {{ $label }}
        </label>
    @endif

    <textarea wire:model="{{ $name }}"
        id="{{ $id }}"
        name="{{ $inputName }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->class([
            'block w-full rounded-lg border px-3 py-2 text-sm transition duration-150 ease-in-out focus:outline-none focus:ring-2',
            'border-red-300 bg-red-50 text-red-900 placeholder:text-red-400 focus:border-red-500 focus:ring-red-200' => $hasError,
            'border-slate-300 bg-white text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:ring-brand-200' => ! $hasError,
        ]) }}
    >{{ $value }}</textarea>

    @if ($help)
        <p class="mt-2 text-sm text-slate-500">
            {{ $help }}
        </p>
    @endif

    @error($inputName)
        <p class="mt-2 text-sm text-red-600">
            {{ $message }}
        </p>
    @enderror
</div>
