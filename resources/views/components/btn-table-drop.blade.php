@php
    $color ??= 'gray-100';
@endphp
<button type="button"
    class="text-white px-3 py-1 text-sm hover:bg-{{ $color }} bg-{{ $color }} transition-all border-{{ $color }} dropdown-toggle focus:bg-{{ $color }} border-[1px]"
    data-bs-toggle="dropdown" aria-expanded="false">
    <span class="visually-hidden">Toggle Dropdown</span>
</button>

<ul class="dropdown-menu border-none bg-white border-[1px] border-{{ $color }} shadow">
    {{ $slot }}
</ul>
