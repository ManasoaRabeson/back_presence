<div class="flex flex-col justify-center gap-2 px-4 py-3 border border-gray-400 shadow-md rounded-2xl text-nowrap mb-3">
  <div class="flex items-center gap-3">
    <div class="relative w-20 h-10 border border-gray-400 rounded-lg">
      @isset($logo)
        {{-- <img onclick="showCustomer({{ $idEtp }}, '/cfp/etp-drawer/')" src="/img/entreprises/{{ $logo }}" 
          class="absolute transform -translate-x-1/2 -translate-y-1/2 cursor-pointer top-1/2 left-1/2 ">--}}
      @else
        <p class="absolute p-3 text-3xl font-bold text-white transform -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2">
          {{ $initial }}</p>
      @endisset
    </div>
    <div class="flex flex-col gap-1 text-left">
      <div>
        <p class="text-2xl font-bold text-black">{{ DashboardFormat::formatPrice($cost) }}</p>
      </div>
      <div>
        <p class="text-lg font-semibold text-gray-500 truncate">{{ $title }}</p>
      </div>
      <div class="flex items-center gap-2">
        <div>
          <i class="fa-solid fa-user-graduate"></i>
        </div>
        <div>
          <p class="text-black-400">{{ $students }}</p>
        </div>
      </div>
    </div>
  </div>
</div>
