@php
  $color ??= '[#A462A4]';
  $date ??= "Aujourd'hui";
@endphp

<div class="inline-flex gap-6 items-center mb-2">
  <span class="w-3 h-3 bg-{{ $color }} rounded-full"></span>
  <div class="inline-flex items-center gap-2 px-2 py-1 bg-gray-100 rounded-md">
    <i class="fa-regular fa-calendar text-gray-600"></i>
    <p class="text-gray-600 text-lg font-mediu">{{ $date }}</p>
  </div>
</div>
