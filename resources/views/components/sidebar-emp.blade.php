<div
    class="flex flex-col h-full gap-2 fixed z-40 w-[50px] items-center justify-start pt-36 shadow-sm bg-gray-50 backdrop-blur-md rounded-md">
    <div class="group absolute z-10 top-3 left-7 justify-center items-center rounded-md duration-300 cursor-pointer">
        <a href="{{ url('homeEmp') }}" class="">
            <img src="{{ asset('img/logo/Logo_mark.svg') }}" alt="logo_lgcfp" class="w-5 h-auto">
        </a>
        <x-customHelper titre="FormaFusion">C'est un logiciel de gestion de la formation professionnelle.</x-customHelper>
    </div>
    <x-sidebar-menu description="Explorer vos projets" id="projet" nb="{{ $total }}" icon="folder-open"
        text="Projets" />
    <x-sidebar-menu description="Liste de vos sessions à venir" nb="64" id="session" icon="calendar-days"
        text="Sessions" />

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
