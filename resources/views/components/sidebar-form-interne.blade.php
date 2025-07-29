<div
  class="flex flex-col h-full gap-2 fixed z-40 w-[50px] items-center justify-start pt-36 shadow-sm bg-gray-50 backdrop-blur-md rounded-md">
  <div class="group absolute z-10 top-3 left-7 justify-center items-center rounded-md duration-300 cursor-pointer">
    <a href="{{ url('homeFormInterne') }}" class="">
      <img src="{{ asset('img/logo/Logo_mark.svg') }}" alt="logo_lgcfp" class="w-5 h-auto">
    </a>
    <x-customHelper titre="FormaFusion">C'est un logiciel de gestion de la formation professionnelle.</x-customHelper>
  </div>
  <div class="btn-group dropright">
    <button type="button" data-bs-toggle="dropdown" aria-expanded="false"
      class="outline-none flex flex-col gap-1 w-12 h-12 relative rounded-xl justify-center hover:shadow-md duration-150 bg-[#a462a4] cursor-pointer top-3 group/sidebar items-center">
      <i class="fa-solid fa-plus text-white duration-150 cursor-pointer"></i>
    </button>
    <ul class="dropdown-menu w-max">
      <li class="text-gray-400 px-3 py-1 hover:bg-gray-100 rounded-md duration-150 cursor-pointer">
        <span>
          <i class="fa-solid fa-handshake"></i>
        </span>
        <button type="button" id="openModalBtnClient"
          class="text-gray-400 ml-2 hover:bg-gray-100 rounded-md duration-150 cursor-pointer">
          Ajouter un centre de formation
        </button>
      </li>
      <li class="text-gray-400 px-3 py-1 hover:bg-gray-100 rounded-md duration-150 cursor-pointer">
        <span>
          <i class="fa-solid fa-puzzle-piece"></i>
        </span>
        <button type="button" id="openModalBtnCours"
          class="text-gray-400 ml-2 hover:bg-gray-100 rounded-md duration-150 cursor-pointer">
          Ajouter un catalogue interne
        </button>
      </li>
    </ul>
  </div>
  <x-sidebar-menu description="Explorer vos projets" nb="{{ 2 }}" id="projet" icon="folder-open"
    text="Projets" />
  <x-sidebar-menu description="Liste de vos sessions à venir" nb="64" id="session" icon="calendar-days"
    text="Sessions" />
</div>
{{-- <x-side-drawer /> --}}
