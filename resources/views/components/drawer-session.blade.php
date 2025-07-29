@php
    $mois = [
        1 => 'Janvier',
        2 => 'Février',
        3 => 'Mars',
        4 => 'Avril',
        5 => 'Mai',
        6 => 'Juin',
        7 => 'Juillet',
        8 => 'Août',
        9 => 'Septembre',
        10 => 'Octobre',
        11 => 'Novembre',
        12 => 'Décembre',
    ];
    $storage ??= '';

@endphp

<!-- component -->
<div class="offcanvas offcanvas-end !w-[80em]" data-bs-backdrop="static" tabindex="-1" id="offcanvasSession"
    aria-labelledby="offcanvasSession">
    <div class="flex flex-col w-full gap-2">
        <div class="inline-flex items-center justify-between w-full px-4 py-2 bg-gray-100">
            <p class="text-lg font-medium text-gray-500" id="head_session">Ajouter des sessions</p>
            <a data-bs-toggle="offcanvas" href="#offcanvasSession"
                class="flex items-center justify-center w-10 h-10 duration-200 rounded-md cursor-pointer hover:bg-gray-200">
                <i class="text-gray-500 fa-solid fa-xmark"></i>
            </a>
        </div>
        <div class="w-full p-3 flex flex-col overflow-y-auto gap-2 h-[100vh] pb-6">
            {{-- Navigation --}}
            <div class="inline-flex min-w-[900px] overflow-x-scroll items-center gap-2 justify-between w-full">
                <div class="inline-flex items-center gap-2">
                    {{-- Week: --}}
                    <div
                        class="flex items-center justify-center px-3 py-2 bg-gray-200 rounded-md cursor-pointer w-max group/nav">
                        <a id="dp_today" onclick="">
                            {{-- <i class="fa-solid fa-chevron-left"></i> --}}
                            Aujourd'hui
                        </a>
                    </div>
                    <div
                        class="flex items-center justify-center w-8 h-8 bg-gray-200 rounded-full cursor-pointer group/nav">
                        <a id="dp_yesterday" onclick="">
                            <i class="fa-solid fa-chevron-left"></i>
                        </a>
                    </div>

                    <span class="flex items-center justify-center text-xl font-semibold text-gray-500">

                        <select id="monthSelectorWeek"
                            class="px-3 py-2 text-base border border-gray-500 rounded-md dropdown-content menu ">
                            @foreach ($mois as $id => $m)
                                <option value={{ $id }}>
                                    {{ $m }}
                                </option>
                            @endforeach

                        </select>

                    </span>

                    <div
                        class="flex items-center justify-center w-8 h-8 bg-gray-200 rounded-full cursor-pointer group/nav">
                        <a id="dp_tomorrow" onclick="">
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    </div>

                    <div class="flex items-center justify-center w-8 h-8 ml-32 space-x-4">
                        <!--Add buttons to initiate auth sequence and sign out-->
                        <button class="btn btn-primary" id="authorize_button" onclick="handleAuthClick()"
                            {{ $storage ? 'enabled' : 'disabled' }}>Sync
                            with
                            GOOGLE</button>
                        <button class="btn btn-danger" id="signout_button" onclick="handleSignoutClick()">Sign
                            Out</button>
                    </div>

                </div>


                <div class="inline-flex items-center gap-2">
                    <div class="inline-flex items-center justify-start w-full">
                        <x-btn-ghost>
                            <a data-bs-toggle="offcanvas" href="#offcanvasSession" class="hover:text-inherit">
                                Annuler
                            </a>
                        </x-btn-ghost>
                        <x-btn-primary onclick="location.reload()">Sauvegarder les modifications</x-btn-primary>
                    </div>
                </div>
            </div>
            <div class="w-full relative min-w-[900px] overflow-x-scroll">
                <div class="w-14 h-8 bg-gray-100 absolute top-[1px] left-[1px] z-10"></div>
                <div id="dp_session">
                </div>
            </div>
        </div>
    </div>
</div>
