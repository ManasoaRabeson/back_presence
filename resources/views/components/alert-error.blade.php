@php
    $message ??= '';
@endphp
<div id="success-message" class="absolute top-0 z-50 flex items-center justify-center w-full cursor-pointer">
    <div class="p-3 rounded-md shadow-xl w-[25em] bg-red-700/70">
        <div class="flex items-center justify-center w-full">
            <i class="w-[7rem] text-4xl text-center text-white fa-solid fa-xmark"></i>
            <div class="flex flex-col w-full gap-2">
                <p class="text-lg font-semibold text-white">Erreur</p>
                <p class="text-white">{{ $message }}</p>
            </div>
        </div>
    </div>
</div>
