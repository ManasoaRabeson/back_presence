<div
  class="inline-flex items-center gap-3 bg-gray-50 w-[calc(100%-50px)] min-h-10 fixed top-[65px] z-10 px-2 rounded-md shadow-sm">
  {{-- puzzle-piece --}}
  <a href="{{ url()->previous() }}" class="text-gray-500 hover:text-gray-600 cursor-pointer">
    <div class="w-8 h-8 rounded-md hover:bg-gray-200 duration-150 cursor-pointer flex items-center justify-center">
      <i class="fa-solid fa-chevron-left text-base text-gray-700"></i>
    </div>
  </a>
  <div class="inline-flex items-center gap-2">
    <i class="fa-solid fa-{{ $icon }} text-xl text-gray-600"></i>
    <label class="text-lg text-gray-600 font-semibold">{{ $label }}</label>
  </div>
  {{ $slot }}
</div>
