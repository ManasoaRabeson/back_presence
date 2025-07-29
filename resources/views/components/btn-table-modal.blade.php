@php
    $titre ??= '';
    $id ??= '';
    $onclick ??= '';
    $color ??= 'gray-100';
@endphp

<div class="inline-flex items-center justify-end w-full" onclick="{{ $onclick }}">
    <button data-bs-toggle="modal" data-bs-target="#{{ $id }}"
        class="text-base text-white px-3 py-1 hover:bg-{{ $color }} bg-{{ $color }} transition duration-200 outline-none border-[1px] capitalize"
        data-bs-toggle="modal">{{ $titre }}
    </button>
    <div class="modal fade" id="{{ $id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        {{ $slot }}
    </div>
</div>
