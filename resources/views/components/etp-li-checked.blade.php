@php
    $id ??= '';
    $initial ??= '';
    $nom ??= '';
    $mail ??= '';
    $check ??= false;
    $onclick ??= '';
@endphp

<li
    class="grid grid-cols-5 w-full gap-2 justify-between px-3 py-2 border-[1px] border-gray-100 cursor-pointer hover:bg-gray-50 duration-200 rounded-md">
    <div class="col-span-4">
        <div class="inline-flex items-center gap-2">
            <span id="photo_etp_selected_{{ $id }}" data-etpid="{{ $id }}">
                {{-- <div class="flex items-center justify-center w-10 h-10 text-gray-500 uppercase bg-gray-200 rounded-full">
          {{ $initial }}</div> --}}
            </span>
            <div class="flex flex-col gap-0">
                <p class="text-base font-normal text-gray-700">{{ $nom }}</p>
                <p class="text-sm text-gray-400 lowercase">{{ $mail }}</p>
            </div>
        </div>
    </div>
    <div class="grid items-center justify-center w-full col-span-1">
        {{-- <div onclick="{{ $onclick }}"
      class="flex items-center justify-center w-10 h-10 duration-150 bg-red-100 rounded-full cursor-pointer icon hover:bg-red-50 group/icon">
      <i class="text-sm text-red-500 duration-150 fa-solid fa-minus group-hover/icon:text-red-600"></i>
    </div> --}}
    </div>
</li>
