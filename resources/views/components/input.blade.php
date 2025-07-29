@php
    $type ??= 'text';
    $description ??= '';
    $class ??= null;
    $name ??= '';
    $value ??= '';
    $label ??= '';
    $accept ??= '';
    $required ??= false;
    $onkeyup ??= null;
    $disabled ??= false;
    $unity ??= null;
    $screen ??= '';
    $readonly ??= false;
    // $label ??= Str::ucfirst($name);
    $placeholder ??= '';
@endphp

<div class="flex flex-col w-full gap-1">
    <span class="inline-flex items-center w-full gap-2">
        <label for="{{ $name }}"
            class="text-slate-700 @if ($required == true) after:content-['*'] after:ml-0.5 after:text-red-500 @endif">{{ $label }}</label>
        <p for="" class="text-sm italic text-slate-500">
            {{ $description }} @if ($required == true)
                Ce champ est obligatoire !
            @endif
        </p>
    </span>

    @if ($type === 'textarea')
        <textarea onkeyup="{{ $onkeyup }}" name="{{ $name }}" cols="30" rows="6" id="{{ $name }}"
            class=" {{ $name }} {{ $name }}_{{ $screen }} {{ $class }} textarea textarea-bordered"
            style="height: 35px !important;">{{ $value }}</textarea>
    @elseif ($type === 'password')
        <div class="relative inline-flex w-full">
            <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}"
                value="{{ $value }}" data-target="{{ $name }}" style="height: 35px;"
                class="@error($name) border-red-500    
        @enderror {{ $name }} {{ $name }}_{{ $screen }}  password password-toggle input input-bordered w-full">
            <i class="absolute bi bi-eye-fill top-2 right-4 eye-icon-toggle" data-target="{{ $name }}"></i>
        </div>
    @else
        <div class="relative inline-flex w-full">
            <input type="{{ $type }}" id="{{ $name }}" value="{{ $value }}"
                accept="{{ $accept }}" onkeyup="{{ $onkeyup }}"
                @if ($readonly == true) readonly @endif name="{{ $name }}"
                placeholder="{{ $placeholder }}" @if ($disabled == 'true') disabled @endif
                class="@error($name) border-red-500
      @enderror @if ($disabled == 'true') border-amber-500 @endif @if ($readonly == true) !bg-slate-200 hover:!border-slate-200 !cursor-not-allowed @endif outline-none {{ $name }} {{ $name }}_{{ $screen }}  input input-bordered w-full">
            @if ($unity != null)
                <span
                    class="h-9 bg-slate-100 font-medium text-sm text-slate-500 px-3 absolute top-[5px] flex items-center justify-center right-[5px] rounded-lg">{{ $unity }}</span>
            @endif
        </div>
    @endif
    @error($name)
        <div id="error_{{ $name }}" class="text-sm text-red-500">
            {{ $message }}
        </div>
    @enderror
</div>
