@php
    $onClick ??= '';
    $idPaiement ??= '';
    $idCfp_inter ??= '';
    $idProjet ??= '';
    $sub ??= 'false';
@endphp
<div class="offcanvas offcanvas-end !w-[80em]" tabindex="-1" data-bs-backdrop="static" id="offcanvasFrais"
    aria-labelledby="offcanvasFrais">
    <div class="flex flex-col w-full">
        <div class="w-full px-4 py-2 inline-flex items-center justify-between bg-gray-100">
            <p class="text-lg text-gray-500 font-medium">Ajouter un frais</p>
            <a onclick="{{ $onClick }}" data-bs-toggle="offcanvas" href="#offcanvasFrais"
                class="w-10 h-10 rounded-md hover:bg-gray-200 duration-200 cursor-pointer flex items-center justify-center">
                <i class="fa-solid fa-xmark text-gray-500"></i>
            </a>
        </div>

        <div class="w-full overflow-x-auto p-4 h-[100vh] pb-3">
            <div class="flex flex-col gap-4 w-full h-full min-w-[650px] overflow-x-scroll">
                @if ($sub != 'true')
                    <div class="flex flex-col gap-2">
                        <h1 class="text-slate-700 text-lg font-semibold">Type de financement</h1>
                        <div class="inline-flex items-center gap-2">
                            @if (isset($paiements))
                                @foreach ($paiements as $pm)
                                    @if ($idPaiement == $pm->idPaiement)
                                        <div class="form-control">
                                            <label class="label cursor-pointer">
                                                <span class="label-text">{{ $pm->paiement }}</span>
                                                <input type="radio"
                                                    onclick="updateFinancement({{ $idProjet }}, {{ $pm->idPaiement }}, {{ $idCfp_inter }})"
                                                    name="paiement" class="radio checked:bg-[#A462A4]"
                                                    checked="checked" />
                                            </label>
                                        </div>
                                    @else
                                        <div class="form-control">
                                            <label class="label cursor-pointer">
                                                <span class="label-text">{{ $pm->paiement }}</span>
                                                <input type="radio"
                                                    onclick="updateFinancement({{ $idProjet }}, {{ $pm->idPaiement }}, {{ $idCfp_inter }})"
                                                    name="paiement" class="radio checked:bg-[#A462A4]" />
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endif
                <div class="flex h-full overflow-y-auto">
                    <div class="flex flex-col h-full items-start mr-4 w-1/2 border-r-[1px] border-gray-200">
                        <div class="flex items-center gap-3 px-4 py-2 bg-gray-50 rounded-lg shadow-sm">
                            <div class="w-1 h-8 bg-red-400 rounded"></div>
                            <label class="text-gray-700 text-lg font-semibold">Liste de tous les frais de ce
                                projet</label>
                        </div>

                        <div class="w-full h-full mt-2 bg-gray-50 overflow-y-auto">
                            <ul id="get_all_frais"
                                class="select-list list-none p-2 relative rounded w-full flex flex-row flex-wrap gap-2 justify-start items-start">
                            </ul>
                        </div>
                    </div>

                    <!-- Liste des Clients sélectionnés -->
                    <div class="flex flex-col items-start w-1/2 h-full">
                        <div class="inline-flex items-center w-max gap-3">
                            <div class="flex items-center gap-3 px-4 py-2 bg-gray-50 rounded-lg shadow-sm">
                                <div class="w-1 h-8 bg-green-400 rounded"></div>
                                <label class="text-gray-700 text-lg font-semibold">Les frais sélectionnés pour ce
                                    projet</label>
                            </div>
                        </div>

                        <div class="w-full h-full mt-2 overflow-y-auto">
                            <!-- Radio Buttons for VAT -->
                            <div class="flex items-center gap-6 mb-4 p-4 bg-gray-50 rounded-lg shadow-sm">
                                <!-- Radio button for 20% VAT -->
                                <label for="tvaRadio20" class="flex items-center cursor-pointer">
                                    <input type="radio" id="tvaRadio20" name="tvaRadio" value="20"
                                        class="form-radio accent-purple-600 cursor-pointer h-5 w-5 text-purple-600 focus:ring-purple-500 focus:outline-none">
                                    <span class="ml-3 text-gray-700 text-lg font-medium select-none label-text">TVA
                                        20%</span>
                                </label>

                                <!-- Radio button for 0% VAT -->
                                <label for="tvaRadio0" class="flex items-center cursor-pointer">
                                    <input type="radio" id="tvaRadio0" name="tvaRadio" value="0"
                                        class="form-radio accent-purple-600 cursor-pointer h-5 w-5 text-purple-600 focus:ring-purple-500 focus:outline-none">
                                    <span class="ml-3 text-gray-700 text-lg font-medium select-none label-text">TVA
                                        0%</span>
                                </label>
                            </div>

                            <ul id="get_frais_selected"
                                class="select-list list-none p-2 w-full flex flex-row flex-wrap gap-2">
                                <!-- List items for selected fees will be appended here -->
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="w-full inline-flex items-end justify-end pt-3 mb-14">
                    <x-btn-primary>
                        <a data-bs-toggle="offcanvas" href="#offcanvasFrais" onclick="{{ $onClick }}"
                            class="hover:text-inherit">
                            Fermer
                        </a>
                    </x-btn-primary>
                </div>
            </div>
        </div>
    </div>
</div>
