@props(['name', 'value' => null ])
<input type="hidden" name="{{ $name }}" id="{{ $name }}"
  value="{{ $value }}"
  {{ $attributes->merge(['class' => 'form-control ' . ($errors->has($name) ? 'is-invalid' : '')]) }}
  >

@error($name)
  <div class="invalid-feedback">
    {{ $message }}
  </div>
@enderror
