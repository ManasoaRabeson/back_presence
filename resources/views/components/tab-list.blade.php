@php
    $id ??= '';
    $selected ??= 'false';
    $click ??= '';
    $tab ??= 'tabAnnuaire';
    $class ??= '';
@endphp

<li rel="{{ $tab }}" id='{{ $id }}' class="items-center text-center btnTab {{$class}}"
    onclick="{{ $click }}">
    <a {{ $attributes->merge(['class' => 'tracking-wide rounded-xl gap-2 flex w-max duration-200 cursor-pointer items-center text-md justify-center rounded-lg border-0 bg-inherit hover:text-inherit px-3 py-1 transition-all ease-in-out']) }}
        role="tab" aria-selected="{{ $selected }}" aria-controls="{{ $id }}">
        {{ $slot }}
    </a>
</li>
