@php
    $total ??= null;
@endphp
<div
    class="flex flex-col h-full gap-y-8 fixed z-40 w-[50px] items-center justify-start pt-36 shadow-sm bg-gray-50 backdrop-blur-md rounded-md">
    <div class="absolute z-10 items-center justify-center duration-300 rounded-md cursor-pointer group top-3 left-7">
        <a href="{{ url('home') }}" class="">
            <img src="{{ asset('img/logo/Logo_mark.svg') }}" alt="logo_lgcfp" class="w-5 h-auto">
        </a>
        <x-customHelper titre="FormaFusion">C'est un logiciel de gestion de la formation professionnelle.</x-customHelper>
    </div>
    <div class="btn-group dropright">
        <button type="button" data-bs-toggle="dropdown" aria-expanded="false"
            class="outline-none flex flex-col gap-1 w-12 h-12 relative rounded-xl justify-center hover:shadow-md duration-150 bg-[#a462a4] cursor-pointer top-3 group/sidebar items-center">
            <i class="text-white duration-150 cursor-pointer fa-solid fa-plus"></i>
        </button>
        <ul class="shadow-md dropdown-menu w-[220px] rounded-xl border-[1px] border-gray-200">
            <li id="openModalBtn"
                class="inline-flex items-center w-full gap-2 px-3 py-2 text-gray-500 duration-150 rounded-md cursor-pointer hover:bg-gray-100">
                <div class="w-[16px]">
                    <i class="fa-solid fa-tarp"></i>
                </div>
                <button type="button"
                    class="ml-2 text-gray-500 duration-150 rounded-md cursor-pointer hover:bg-gray-100">
                    Créer un projet
                </button>
            </li>
            <li id="openModalBtnClient"
                class="inline-flex items-center w-full gap-2 px-3 py-2 text-gray-500 duration-150 rounded-md cursor-pointer hover:bg-gray-100">
                <div class="w-[16px]">
                    <i class="fa-solid fa-handshake"></i>
                </div>
                <button type="button"
                    class="ml-2 text-gray-500 duration-150 rounded-md cursor-pointer hover:bg-gray-100">
                    Ajouter un client
                </button>
            </li>

            <li id="openModalBtnCours"
                class="inline-flex items-center w-full gap-2 px-3 py-2 text-gray-500 duration-150 rounded-md cursor-pointer hover:bg-gray-100">
                <div class="w-[16px]">
                    <i class="fa-solid fa-puzzle-piece"></i>
                </div>
                <button type="button"
                    class="ml-2 text-gray-500 duration-150 rounded-md cursor-pointer hover:bg-gray-100">
                    Ajouter un cours
                </button>
            </li>

            <li id="openModalBtnFormateur"
                class="inline-flex items-center w-full gap-2 px-3 py-2 text-gray-500 duration-150 rounded-md cursor-pointer hover:bg-gray-100">
                <div class="w-[16px]">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>
                <button type="button"
                    class="ml-2 text-gray-500 duration-150 rounded-md cursor-pointer hover:bg-gray-100">
                    Ajouter un formateur
                </button>
            </li>

            <li onclick="mainGetEtpApprs()" id="openModalBtnApprenant"
                class="inline-flex items-center w-full gap-2 px-3 py-2 text-gray-500 duration-150 rounded-md cursor-pointer hover:bg-gray-100">
                <div class="w-[16px]">
                    <i class="fa-solid fa-people-group"></i>
                </div>
                <button type="button"
                    class="ml-2 text-gray-500 duration-150 rounded-md cursor-pointer hover:bg-gray-100">
                    Ajouter un apprenant
                </button>
            </li>

            <li id="openModalBtnReferent"
                class="inline-flex items-center w-full gap-2 px-3 py-2 text-gray-500 duration-150 rounded-md cursor-pointer hover:bg-gray-100">
                <div class="w-[16px]">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <button type="button"
                    class="ml-2 text-gray-500 duration-150 rounded-md cursor-pointer hover:bg-gray-100">
                    Ajouter un référent
                </button>
            </li>

            <li onclick="mainLoadVille()" id="openModalBtnSalle"
                class="inline-flex items-center w-full gap-2 px-3 py-2 text-gray-500 duration-150 rounded-md cursor-pointer hover:bg-gray-100">
                <div class="w-[16px]">
                    <i class="fa-solid fa-door-closed"></i>
                </div>
                <button type="button"
                    class="ml-2 text-gray-500 duration-150 rounded-md cursor-pointer hover:bg-gray-100">
                    Ajouter une salle
                </button>
            </li>

            <li id="openModalBtnBankAccount"
                class="inline-flex items-center w-full gap-2 px-3 py-2 text-gray-500 duration-150 rounded-md cursor-pointer hover:bg-gray-100">
                <div class="w-[16px]">
                    <i class="fa-solid fa-money-check-dollar"></i>
                </div>
                <button type="button"
                    class="ml-2 text-gray-500 duration-150 rounded-md cursor-pointer hover:bg-gray-100">
                    Ajouter un compte bancaire
                </button>
            </li>

            <li id="openModalBtnParticulier"
                class="inline-flex items-center w-full gap-2 px-3 py-2 text-gray-500 duration-150 rounded-md cursor-pointer hover:bg-gray-100">
                <div class="w-[16px]">
                    <i class="fa-solid fa-handshake"></i>
                </div>
                <button type="button"
                    class="ml-2 text-gray-500 duration-150 rounded-md cursor-pointer hover:bg-gray-100">
                    Ajouter un particulier
                </button>
            </li>
        </ul>
    </div>
    <x-sidebar-menu description="Explorer vos projets" nb="{{ $total }}" id="projet" icon="folder-open"
        text="Projets" />
</div>
