@php
    $class ??= null;
    $id ??= null;
    $role ??= null;
@endphp

<tr id="{{ $id }}" @class([
    'border-b-[1px] border-gray-100 hover:bg-gray-50 duration-150 cursor-pointer',
    $class,
]) {{ $attributes }}>
    {{ $slot }}
</tr>
