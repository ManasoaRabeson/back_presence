<div
    class="flex flex-col h-full gap-2 fixed z-40 w-[50px] items-center justify-start pt-36 shadow-sm bg-gray-50 backdrop-blur-md rounded-md">
    <div class="group absolute z-10 top-3 left-7 justify-center items-center rounded-md duration-300 cursor-pointer">
        <a href="{{ url('home') }}" class="">
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
                    <i class="fa-solid fa-tarp"></i>
                </span>
                <button onclick="mainGetIdProject()" type="button" id="openModalBtn"
                    class="text-gray-400 ml-2 hover:bg-gray-100 rounded-md duration-150 cursor-pointer">
                    Créer un projet interne
                </button>
            </li>
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

            <li class="text-gray-400 px-3 py-1 hover:bg-gray-100 rounded-md duration-150 cursor-pointer">
                <span>
                    <i class="fa-solid fa-user-graduate"></i>
                </span>
                <button type="button" id="openModalBtnFormateur"
                    class="text-gray-400 ml-2 hover:bg-gray-100 rounded-md duration-150 cursor-pointer">
                    Ajouter un formateur interne
                </button>
            </li>

            <li class="text-gray-400 px-3 py-1 hover:bg-gray-100 rounded-md duration-150 cursor-pointer">
                <span>
                    <i class="fa-solid fa-people-group"></i>
                </span>
                <button onclick="mainGetEtpEmpls()" type="button" id="openModalBtnEmploye"
                    class="text-gray-400 ml-2 hover:bg-gray-100 rounded-md duration-150 cursor-pointer">
                    Ajouter un employé
                </button>
            </li>

            <li class="text-gray-400 px-3 py-1 hover:bg-gray-100 rounded-md duration-150 cursor-pointer">
                <span>
                    <i class="fa-solid fa-user-tie"></i>
                </span>
                <button type="button" id="openModalBtnReferent"
                    class="text-gray-400 ml-2 hover:bg-gray-100 rounded-md duration-150 cursor-pointer">
                    Ajouter un référent
                </button>
            </li>

            <li class="text-gray-400 px-3 py-1 hover:bg-gray-100 rounded-md duration-150 cursor-pointer">
                <span>
                    <i class="fa-solid fa-door-closed"></i>
                </span>
                <button type="button" onclick="mainLoadVille()" id="openModalBtnSalle"
                    class="text-gray-400 ml-2 hover:bg-gray-100 rounded-md duration-150 cursor-pointer">
                    Ajouter une salle
                </button>
            </li>

        </ul>
    </div>
    <x-sidebar-menu description="Explorer vos projets" id="projet" nb="{{ $total }}" icon="folder-open"
        text="Projets" />
    <x-sidebar-menu description="Liste de vos sessions à venir" nb="64" id="session" icon="calendar-days"
        text="Sessions" />

    {{-- <div class="btn-group dropright">
    <button type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Aspects financiers"
      class="flex flex-col gap-1 w-12 h-12 relative justify-center hover:shadow-md duration-150 bg-white cursor-pointer rounded-xl top-3 group/sidebar items-center">
      <i
        class="fa-solid fa-landmark text-gray-400 group-hover/sidebar:text-gray-600 group-focus-within:text-purple-500 duration-150 cursor-pointer"></i>
    </button>

    <ul class="dropdown-menu w-max">
      <li class="hover:bg-gray-100 rounded-md duration-150 cursor-pointer">
        <a href="{{ route('facture.etp.index') }}" class="text-gray-400 px-3 py-2 hover:text-gray-500 duration-150">
          <span>
            <i class="fa-solid fa-file-invoice"></i>
          </span>
          <span class="text-gray-400 ml-2">
            Facture
          </span>
        </a>
      </li>

    </ul>
  </div> --}}


    <div class="btn-group dropright">

        <button type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Formations internes"
            class="flex flex-col gap-1 w-12 h-12 relative justify-center hover:shadow-md duration-150 bg-white cursor-pointer rounded-xl top-3 group/sidebar items-center">
            <i
                class="fas fa-graduation-cap text-gray-400 group-hover/sidebar:text-gray-600 group-focus-within:text-purple-500 duration-150 cursor-pointer"></i>
        </button>

        <ul class="dropdown-menu w-max">
            <li class="hover:bg-gray-100 rounded-md duration-150 cursor-pointer">
                <a href="#" class="text-gray-400 px-3 py-2 hover:text-gray-500 duration-150">
                    <span>
                        <i class="fas fa-book-reader"></i>
                    </span>
                    <span class="text-gray-400 ml-2">
                        Formateur interne
                    </span>
                </a>
            </li>
            <li class="hover:bg-gray-100 rounded-md duration-150 cursor-pointer">
                <a href="#" class="text-gray-400 px-3 py-2 hover:text-gray-500 duration-150">
                    <span>
                        <i class="fas fa-book-open"></i>
                    </span>
                    <span class="text-gray-400 ml-2">
                        Catalogue interne
                    </span>
                </a>
            </li>
        </ul>

    </div>
</div>
{{-- <x-side-drawer /> --}}
