@php
    $route ??= '';
    $active ??= '';
@endphp
<span class="relative z-30 flex flex-row text-white transition-all">
    <a href="{{ $route }}"
        class="text-sm p-[7px] w-max hover:bg-[#81338a] hover:text-white transition rounded-md ease-in-out {{ $active }}">
        {{ $slot }}
    </a>
</span>
