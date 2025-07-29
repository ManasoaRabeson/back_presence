@php
    $click ??= '';
@endphp
@if ($click === 'ca')
    <div class="dropdown dropdown-bottom dropdown-end">
        <button
            class="text-gray-700 flex dropdown-item mt-1 rounded-md items-center px-2 py-1 scale-105 hover:bg-gray-50 focus:bg-[#A462A4] focus:text-white transition duration-150 outline-none"
            type="button">
            <i class="mr-2 fa-solid fa-money-bill-trend-up"></i> Chiffre d'affaire
            {{-- <a class="dropdown-item text-gray-700 font-semibold px-2 py-1 flex items-center rounded-md hover:bg-gray-50 focus:bg-[#A462A4] focus:text-white transition duration-150 uppercase"
                href="{{ route('reporting.ca.cfp') }}">
            </a> --}}
        </button>

        <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-max p-2 shadow">
            <li>
                <a href="{{ route('reporting.caProjet') }}" class="text-slate-600 hover:text-slate-500">
                    <div class="w-[16px]">
                        <i class="fa-solid fa-tarp"></i>
                    </div>
                    Projet
                </a>
            </li>
            <li>
                <a href="{{ route('reporting.caModule') }}"class="text-slate-600 hover:text-slate-500">
                    <div class="w-[16px]">
                        <i class="fa-solid fa-puzzle-piece"></i>
                    </div>
                    Cours
                </a>
            </li>
            <li>
                <a href="{{ route('reporting.caCustomer') }}" class="text-slate-600 hover:text-slate-500">
                    <div class="w-[16px]">
                        <i class="fa-solid fa-handshake"></i>
                    </div>
                    Clients
                </a>
            </li>
            <li>
                <a href="{{ route('reporting.caMonth') }}"class="text-slate-600 hover:text-slate-500">
                    <div class="w-[16px]">
                        <i class="fa-solid fa-calendar-days"></i>
                    </div>
                    Mois
                </a>
            </li>
            <li>
                <a href="{{ route('reporting.caFolder') }}" class="text-slate-600 hover:text-slate-500">
                    <div class="w-[16px]">
                        <i class="fa-solid fa-folder"></i>
                    </div>
                    Dossier
                </a>
            </li>
            <li>
                <a href="{{ route('reporting.caReference') }}"class="text-slate-600 hover:text-slate-500">
                    <div class="w-[16px]">
                        <i class="fa-solid fa-hashtag"></i>
                    </div>
                    Réference
                </a>
            </li>
            <li>
                <a href="{{ route('reporting.caPlace') }}"class="text-slate-600 hover:text-slate-500">
                    <div class="w-[16px]">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    Ville
                </a>
            </li>
        </ul>
    </div>
@endif
