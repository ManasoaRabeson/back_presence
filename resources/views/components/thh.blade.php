@php
    $colspan ??= '';
    $date ??= '';
    $se ??= '';
    $onclick ??= '';
@endphp

<td scope="col" td-se="{{ $se }}" class="capitalize" colspan="{{ $colspan }}">
    {{ $date }}
    <input type="checkbox" onclick="{{ $onclick }}" td-se="{{ $se }}" value="{{ $se }}"
        class="checkbox checkSe checkbox-sm" />
</td>
