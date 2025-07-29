@php
  $url ??= '';
@endphp
<div class="w-full border-[1px] border-gray-50 bg-gray-50 rounded-md shadow-sm flex flex-col justify-center gap-2 p-4">

  <div class="flex flex-col">
    <div class="inline-flex items-center text-gray-500 font-medium gap-2">
      <i class="fa-solid fa-ranking-star text-xl"></i>
      <h5 class="text-xl font-semibold">Avis et notes</h5>
    </div>
    <p class="text-base text-gray-400 font-normal">Regardez ce que les gens pensent de cette formation.</p>
  </div>

  <div class="flex flex-col">
    <div class="inline-flex items-center gap-2">
      <h4 class="text-base text-gray-700 font-medium">4.6</h4>
      <div class="inline-flex items-center gap-1">
        <i class="fa-solid fa-star text-amber-400 text-sm"></i>
        <i class="fa-solid fa-star text-amber-400 text-sm"></i>
        <i class="fa-solid fa-star text-amber-400 text-sm"></i>
        <i class="fa-solid fa-star text-amber-400 text-sm"></i>
        <i class="fa-solid fa-star text-amber-400 text-sm"></i>
      </div>
      <a href="{{ $url }}" class="text-base text-gray-700 font-medium">(121 avis)</a>
    </div>
    <p class="text-base text-gray-400 font-normal">93% des gens recommandent cette formation.</p>
  </div>
  <div class="w-full">
    <table class="w-full">
      <thead>
        <tr>
          <x-th>Critères</x-th>
          <x-th class="text-right">Notes</x-th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <x-td>Contenu</x-td>
          <x-td>
            <p class="w-full text-right">4.3</p>
          </x-td>
        </tr>
        <tr>
          <x-td>Formateur</x-td>
          <x-td>
            <p class="w-full text-right">4.7</p>
          </x-td>
        </tr>
        <tr>
          <x-td>Impact</x-td>
          <x-td>
            <p class="w-full text-right">5</p>
          </x-td>
        </tr>
        <tr>
          <x-td>Général</x-td>
          <x-td>
            <p class="w-full text-right">4.9</p>
          </x-td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
