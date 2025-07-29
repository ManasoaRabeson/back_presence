@php
    $route ??= '';
    $tooltip ??= '';
@endphp

<li class="items-center text-center" data-bs-toggle="tooltip" data-bs-original-title="{{ $tooltip }}">
    <a href="{{ $route }}"
        {{ $attributes->merge(['class' => 'rounded-xl gap-2 flex w-max duration-200 cursor-pointer items-center hover:bg-white text-md justify-center rounded-lg border-0  hover:text-inherit px-3 py-1 transition-all ease-in-out font-medium']) }}>
        {{ $slot }}
    </a>
</li>
