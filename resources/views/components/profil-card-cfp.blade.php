@php
  $id ??= '--';
  $badge ??= '--';
  $nom ??= '--';
  $prenom ??= '--';
  $cfpName ??= '--';
  $mail ??= '--';
  $telephone ??= '--';
  $adresse ??= '--';
  //   $fonction ??= '--';
  $img ??= null;
@endphp
<div class="h-96 w-full bg-gray-50 rounded-xl p-3">
  <div class="flex flex-col gap-2 h-full w-full">
    <div class="h-2/5 w-full">
      <div class="inline-flex items-start justify-between w-full">
        <div class="flex flex-row items-start gap-2">
          <div
            class="w-44 h-24 rounded-xl flex items-center justify-center uppercase bg-gray-100 text-gray-500 text-3xl font-medium">
            @if ($img != null)
              <img src="/img/entreprises/{{ $img }}" alt="photo" class="object-cover h-full w-full rounded-xl">
            @else
              {{ $cfpName[0] }}
            @endif
          </div>
          {{-- <p class="text-lg font-medium flex-wrap text-gray-700">{!! $cfpName !!}</p> --}}
          {{-- <p class="text-gray-400">{{ $fonction }}</p> --}}
        </div>

        <div class="dropdown">
          <button type="button" title="Cliquer pour afficher le menu"
            class="w-8 h-8 bg-[#A462A4] rounded-md hover:bg-[#A462A4]/90 duration-150 cursor-pointer shadow-sm shadow-purple-500"
            data-bs-toggle="dropdown" aria-expanded="false">
            <span class=""><i class="fa-solid fa-bars-staggered text-white"></i></span>
          </button>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
            <li>
              <a class="cursor-pointer hover:bg-gray-100 duration-200 p-2 hover:text-inherit w-full h-full inline-flex items-center gap-2"
                data-bs-toggle="offcanvas" href="#offcanvas" role="button" aria-controls="offcanvas"
                onclick="editClient({{ $id }}); sessionStorage.setItem('ID_CROP_IMG_ETP', {{ $id }} )">
                <i class="fa-solid fa-pen text-sm text-gray-700"></i>
                <span>Editer</span>
              </a>
              <input type="file" class="hidden inputFile" name="logofileEtp-{{ $id }}" id="logofileEtp">
            </li>
          </ul>

          {{-- <x-drawer-edit-client></x-drawer-edit-client> --}}

          <x-drawer-edit-cfp></x-drawer-edit-cfp>

        </div>
      </div>
    </div>
    <div class="h-3/5 bg-white rounded-xl w-full">
      <div class="h-full flex flex-col gap-2 p-3">
        <div class="h-1/2 w-full">
          <div class="grid grid-cols-5">
            <div class="grid grid-cols-subgrid col-span-3">
              <div class="flex flex-col">
                <span class="text-gray-400">Référent</span>
                <span class="text-gray-500">{{ $nom }} {!! $prenom !!}</span>
                <span class="text-purple-700 hover:text-purple-500 duration-200 underline cursor-pointer">voir tous les référents</span>
                {{-- <span onclick="showCustomer({{ $id }}, '/etp/cfp-drawer/')" class="text-purple-700 hover:text-purple-500 duration-200 underline cursor-pointer">voir tous les référents</span> --}}
              </div>
            </div>
            <div class="grid grid-cols-subgrid col-span-2">
              <div class="flex flex-col">
                @if ($badge == 0)
                  <div class="w-full flex justify-end">
                    <label class="text-base px-2 py-1 rounded-md text-white bg-amber-400">En
                      attente</label>
                  </div>
                @else
                  <div class="w-full flex justify-end">
                    <label class="text-base px-2 py-1 rounded-md text-white bg-green-400">Membre</label>
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>
        <div class="h-1/2 w-full flex flex-col gap-1">
          <div class="inline-flex items-center gap-1">
            <div class="w-[18px]">
              <i class="fa-solid fa-envelope text-gray-500"></i>
            </div>
            <p class="text-gray-500">{{ $mail }}</p>
          </div>
          <div class="inline-flex items-center gap-1">
            <div class="w-[18px]">
              <i class="fa-solid fa-phone text-gray-500"></i>
            </div>
            <p class="text-gray-500">{{ $telephone }}</p>
          </div>
          <div class="inline-flex items-center gap-1">
            <div class="w-[18px]">
              <i class="fa-solid fa-location-dot text-gray-500"></i>
            </div>
            <p class="text-gray-500">{{ $adresse }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
