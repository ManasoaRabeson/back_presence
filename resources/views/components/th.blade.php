@php
  $class ??= null;
@endphp

<th @class(['p-2 font-semibold text-base text-gray-500', $class])>{{ $slot }}</th>
