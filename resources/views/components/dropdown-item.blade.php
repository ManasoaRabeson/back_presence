@php
  $icon ??= '';
  $label ??= '';
  $route ??= '';
  $type ??= 'link';
  $onclick ??= '';
  $icontype ??= 'regular';
@endphp

@if ($type == 'link')
  <li class="inline-flex items-center w-full gap-2 p-0 text-gray-500 duration-150 cursor-pointer">
    <a class="inline-flex items-center w-full gap-2 py-1 pl-3 text-gray-500 duration-150 cursor-pointer dropdown-item"
      href="{{ $route }}">
      <i class="fa-{{ $icontype }} fa-{{ $icon }} text-base"></i>
      {{ $label }}</a>
  </li>
@elseif ($type == 'btn')
  <li class="inline-flex items-center w-full gap-2 p-0 text-gray-500 duration-150 cursor-pointer dropdown-item">
    <button type="button" onclick="{{ $onclick }}"
      class="inline-flex items-center w-full gap-2 py-1 pl-3 text-left rotate-0">
      <span>
        <i class="fa-{{ $icontype }} fa-{{ $icon }} text-sm"></i>
      </span>
      {{ $label }}
    </button>
  </li>
@endif
