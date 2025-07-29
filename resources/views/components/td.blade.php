@php
    $class ??= null;
@endphp

<td class='p-2 text-base text-gray-500 {{ $class }}' {{ $attributes }}>
    {{ $slot }}
</td>
