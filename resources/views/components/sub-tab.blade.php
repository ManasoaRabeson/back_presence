@php
    $data ??= 'tabs';
@endphp
<div class="tab-slider--nav">
    <ul class="tab-slider--tabs flex list-none flex-nowwrap min-w-[340px]:ml-[200px] items-center rounded-xl bg-gray-100 p-1"
        data-tabs="{{ $data }}" role="list">
        {{ $slot }}
    </ul>
</div>
