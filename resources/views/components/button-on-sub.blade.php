@php
  $label ??= '';
  $icon ??= '';
  $url ??= '';
@endphp

<div>
  <a href="{{ $url }}">
    <div class="inline-flex gap-2 text-md justify-center items-center bg-transparent hover:text-gray-500/90 border-purple-500 text-gray-500 duration-200">
      <i class="fa-solid fa-{{ $icon }} text-sm"></i>
      {{ $label }}
    </div>
  </a>
</div>
