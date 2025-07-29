@php
    $color ??= '';
@endphp

@if($plan->is_recommander == 1)
<div class="relative px-8 py-10 rounded-xl border-[2px] border-[{{ $color }}] flex flex-col gap-7 w-[25em] h-[38em] hover:bg-gray-50 transition duration-300">
    <div class="absolute -top-4 rounded-full border-[0.1px] border-[{{ $color }}] px-3 py-1 bg-white text-sm text-[{{ $color }}]">
        <i class="fa-solid fa-star text-xs"></i>
        Recommandé
    </div>
@else
<div class="px-8 py-10 rounded-xl border-[1px] border-[{{ $color }}] flex flex-col gap-7 w-[25em] hover:bg-gray-50 transition duration-300">
@endif
  <div
    class="relative text-base inline-flex items-center gap-2 text-[{{ $color }}] pl-14 pr-3 py-2 bg-purple-50 rounded-full">
      <i class="absolute -left-1 top-1 fa-solid fa-gem text-3xl text-[{{ $color }}]"></i>
      {{ $plan->dedicate }}
  </div>
  <div>
    <p class="uppercase text-[{{ $color }}]">{{$plan->user_type }}</p>
    <p class="text-xl text-gray-400"><span class="text-2xl text-[{{ $color }}]" id="prixEquipe">{{ $plan->currency }}
        {{ number_format($plan->price, 0, ',', ' ') }}</span>/mois</p>
  </div>
  <div class="flex flex-col gap-1">
    <h2 class="font-medium text-2xl text-[{{ $color }}] uppercase">{{ $plan->name }}</h2>
    <p class="text-gray-400 text-base h-16">{{ $plan->description }}</p>
  </div>
  <div class="flex flex-col justify-between h-full gap-6">
    <div class="flex flex-col gap-4">
      <ul class="px-2 flex flex-col gap-1">
        @foreach ($plan->features as $feature)
        <li class="text-gray-700 text-base inline-flex gap-3 items-start">
          <i class="bi bi-check-circle-fill text-xl text-[{{ $color }}]"></i>
            <span>{{ $feature->name }} {{ $feature->value }} /mois</span>
        </li>
        @endforeach
        <li class="text-gray-700 text-base inline-flex gap-3 items-start">
          <i class="bi bi-check-circle-fill text-xl text-[{{ $color }}]"></i>
          Accédez à tout les projets de tout vos collaborateurs de nombre illimité
        </li>
      </ul>
    </div>
      <div class="flex w-full">
        <p class="py-3 border rounded-full text-xl scale-90 hover:scale-100 transition duration-200 hover:text-gray-500 text-white bg-[{{ $color }}] font-semibold w-full text-center cursor-default uppercase">{{ $plan->name }}</p>
      </div>
  </div>
</div>
