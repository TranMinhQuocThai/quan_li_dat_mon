@props(['type' => 'text', 'name', 'var' => null, 'value' => null])
@php
  if (is_null($value)) {
    $value = !is_null($var) ? $var[$name] : '';
  }
@endphp
<input type="{{ $type }}" name="{{ $name }}"
  id="{{ $name }}"
  value="{{ old($name, $value) }}"
  {{ $attributes->merge(['class' => 'form-control ' . ($errors->has($name) ? 'is-invalid' : '')]) }}>

@error($name)
  <div class="invalid-feedback">
    {{ $message }}
  </div>
@enderror
