@php
  $active ??= '';
  $route ??= '';
  $endpoint = explode('/', $route);
@endphp

<li class="relative flex flex-row text-gray-500 hover:text-gray-200 rounded-md transition-all {{ $active }}">
  <a href=" {{ $route }}" id="{{ $endpoint[3] }}"
    class="text-sm p-[10px] w-max hover:bg-gray-100 hover:text-gray-500 rounded-md transition ease-in-out">{{ $slot }}</a>
</li>
