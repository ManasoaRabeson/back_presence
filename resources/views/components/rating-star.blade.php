@php
  $avis ??= '27';
  $star ??= '4';
  $user ??= 'cfp';
@endphp

<div class="flex items-center w-full gap-2">
  <span class="inline-flex items-center gap-1">
    @if ($star == '0')
      <i class="text-sm fa-regular fa-star text-gray-400"></i>
      <i class="text-sm fa-regular fa-star text-gray-400"></i>
      <i class="text-sm fa-regular fa-star text-gray-400"></i>
      <i class="text-sm fa-regular fa-star text-gray-400"></i>
      <i class="text-sm fa-regular fa-star text-gray-400"></i>
    @elseif ($star == '1')
      <i class="text-sm fa-solid fa-star text-amber-400"></i>
      <i class="text-sm fa-regular fa-star text-gray-400"></i>
      <i class="text-sm fa-regular fa-star text-gray-400"></i>
      <i class="text-sm fa-regular fa-star text-gray-400"></i>
      <i class="text-sm fa-regular fa-star text-gray-400"></i>
    @elseif ($star == '2')
      <i class="text-sm fa-solid fa-star text-amber-400"></i>
      <i class="text-sm fa-solid fa-star text-amber-400"></i>
      <i class="text-sm fa-regular fa-star text-gray-400"></i>
      <i class="text-sm fa-regular fa-star text-gray-400"></i>
      <i class="text-sm fa-regular fa-star text-gray-400"></i>
    @elseif ($star == '3')
      <i class="text-sm fa-solid fa-star text-amber-400"></i>
      <i class="text-sm fa-solid fa-star text-amber-400"></i>
      <i class="text-sm fa-solid fa-star text-amber-400"></i>
      <i class="text-sm fa-regular fa-star text-gray-400"></i>
      <i class="text-sm fa-regular fa-star text-gray-400"></i>
    @elseif ($star == '4')
      <i class="text-sm fa-solid fa-star text-amber-400"></i>
      <i class="text-sm fa-solid fa-star text-amber-400"></i>
      <i class="text-sm fa-solid fa-star text-amber-400"></i>
      <i class="text-sm fa-solid fa-star text-amber-400"></i>
      <i class="text-sm fa-regular fa-star text-gray-400"></i>
    @elseif ($star == '5')
      <i class="text-sm fa-solid fa-star text-amber-400"></i>
      <i class="text-sm fa-solid fa-star text-amber-400"></i>
      <i class="text-sm fa-solid fa-star text-amber-400"></i>
      <i class="text-sm fa-solid fa-star text-amber-400"></i>
      <i class="text-sm fa-solid fa-star text-amber-400"></i>
    @endif
  </span>
  <h3 class="text-gray-500 font-normal">{{ $star }}</h3>
  {{-- <h3 class="text-gray-500 font-normal">(112 avis)</h3> --}}
  <a href="@if ($user == 'etp') {{ route('catalogueFormationEtp.avis') }}
  @elseif ($user == 'cfp') {{ route('catalogueFormation.avis') }}
  @elseif ($user == 'formateur') {{ route('projetForm.avis') }}
  @elseif ($user == 'employe') {{ route('projetEmp.avis') }} @endif"
    class="text-gray-500 hover:text-purple-500 cursor-pointer font-normal">({{ $avis }} avis)</a>
</div>
