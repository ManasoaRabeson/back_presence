@php
    $title ??= '';
    $id ??= '';
@endphp
<div class="offcanvas offcanvas-end !w-[45em]" tabindex="-1" id="{{ $id }}" aria-labelledby="offcanvas">
    <div class="flex flex-col w-full">
        <div class="inline-flex items-center justify-between w-full px-4 py-2 bg-gray-100">
            <p class="text-lg font-medium text-gray-500">{{ $title }}</p>
            <a data-bs-toggle="offcanvas" href="#{{ $id }}" role="button" aria-controls="offcanvas"
                class="flex items-center justify-center w-10 h-10 duration-200 rounded-md cursor-pointer hover:text-inherit hover:bg-gray-200">
                <i class="text-gray-500 fa-solid fa-xmark"></i>
            </a>
        </div>

        <div class="w-full overflow-x-auto p-4 overflow-y-auto h-[100vh] pb-3">
            {{ $slot }}
        </div>
    </div>
</div>
