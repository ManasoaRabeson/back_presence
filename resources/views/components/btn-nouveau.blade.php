@php
    $id ??= '';
    $onclick ??= '';
    $label ??= '';
@endphp
<button role="button" id="{{ $id }}" onclick="{{ $onclick }}" class="btn btn-outline">
    <i class="duration-150 cursor-pointer fa-solid fa-plus"></i>
    <p class="hidden md:block">{{ $label }}</p>
</button>
