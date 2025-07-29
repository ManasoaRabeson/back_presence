@php
    $hour ??= '';
    $color ??= '[#A462A4]'; 
    $module ??= '';
    $cfp ??= 'Numerika center';
    $lieu ??= 'Antananarivo';
    $salle ??= 'Salle 1';
@endphp
<div class="inline-flex items-center gap-4 px-10">
    <p>{{ $hour }}</p>
    <div class="flex flex-col px-4 border-l-2 border-{{ $color }}">
    <p class="text-lg font-semibold text-gray-700">Module : {{ $module }}</p>
    <p class="text-base text-gray-400">{{ $cfp }}</p>
    <p class="text-base text-gray-400">{{ $lieu }}, {{ $salle }}</p>
    </div>
</div>