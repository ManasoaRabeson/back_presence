@php
    $color ??= '';
@endphp

<div
    class="w-14 h-14 rounded-full text-center cursor-pointer flex justify-center items-center text-xl font-medium 
    @if ($sub == null) bg-gray-100 @else bg-[{{ $color }}] @endif transition-all duration-300 shadow-md uppercase">
    <i class="fa-solid fa-user text-gray-700 text-lg"></i>
</div>
