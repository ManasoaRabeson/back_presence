@php
    $titre ??= '';
    $className ??= '';
@endphp

<div class="flex flex-col items-start justify-start w-full h-full gap-2 p-2">
    <div class="inline-flex justify-between w-full my-2">
        <span class="text-2xl font-semibold text-gray-500">{{ $titre }}</span>
    </div>
    <div class="w-full {{ $className }}">
        <table {{ $attributes->merge(['class' => 'table-auto w-full']) }}>
            {{ $slot }}
        </table>
    </div>
</div>
{{-- md:max-w-screen-xl 2xl:max-w-screen-2xl overflow-x-scroll --}}
