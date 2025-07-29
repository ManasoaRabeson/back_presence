@php
    $type ??= 'button';
    $onclick ??= '';
    $class ??= null;
@endphp
<button type="{{ $type }}" onclick="{{ $onclick }}"
    class="{{ $class }} btn btn-primary bg-[#A462A4]">{{ $slot }}</button>
