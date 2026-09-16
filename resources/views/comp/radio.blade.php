@props([
    'name',
    'value',
    'label' => null,
    'checked' => false,
    'disabled' => false,
    'class' => '',
])

<div class="form-check {{ $class }}">
    <input
        class="form-check-input"
        type="radio"
        name="{{ $name }}"
        value="{{ $value }}"
        id="{{ $name }}_{{ $value }}"
        @if($checked) checked @endif
        @if($disabled) disabled @endif
    >
    @if($label)
        <label class="form-check-label" for="{{ $name }}_{{ $value }}">
            {{ $label }}
        </label>
    @endif
</div>
