@php
    $id ??= '';
    $nom ??= '';
    $prenom ??= '';
    $idProjet ??= '';
@endphp

<div class="offcanvas offcanvas-end !w-[90%]" tabindex="-1" id="offcanvasPresence" aria-labelledby="offcanvasPresence">
    <div class="inline-flex items-center justify-between w-full px-4 py-2 bg-gray-100">
        <p class="text-lg font-medium text-gray-500">Fiche de présence</p>
        <a data-bs-toggle="offcanvas" href="#offcanvasPresence"
            class="flex items-center justify-center w-10 h-10 duration-200 rounded-md cursor-pointer hover:bg-gray-200">
            <i class="text-gray-500 fa-solid fa-xmark"></i>
        </a>
    </div>
    <div class="flex flex-col justify-center w-full gap-4 px-4 py-4 mx-auto">
        <form action="{{ route('emargements.cfp.store') }}" method="POST">
            @csrf

            <div class="flex items-center justify-between w-full">

                <div class="inline-flex items-center gap-x-6">
                    <div class="inline-flex items-center gap-2">
                        <i class="text-xl text-green-500 fa-solid fa-check"></i>
                        <label class="text-gray-400 md:text-base">Présent</label>
                        <label id="present-global" class="font-semibold text-gray-600 md:text-xl"></label>
                    </div>

                    <div class="inline-flex items-center gap-2">
                        <i class="text-xl text-yellow-500 fa-solid fa-exclamation"></i>
                        <label class="text-gray-400 md:text-base">Partiellement</label>
                        <label id="partiel-global" class="font-semibold text-gray-600 md:text-xl"></label>
                    </div>

                    <div class="inline-flex items-center gap-2">
                        <i class="text-xl text-red-400 fa-solid fa-xmark"></i>
                        <label class="text-gray-400 md:text-base">Absent</label>
                        <label id="absent-global" class="font-semibold text-gray-600 md:text-xl"></label>
                    </div>
                </div>
                <div class="inline-flex items-center justify-end w-1/3 gap-4">
                    <div class="inline-flex items-center gap-2">
                        <div class="w-4 h-4 bg-gray-500 rounded-md"></div>
                        <label class="text-base font-normal text-gray-400 2xl:text-base">Non défini</label>
                    </div>
                    <div class="inline-flex items-center gap-2">
                        <div class="w-4 h-4 bg-green-400 rounded-md"></div>
                        <label class="text-base font-normal text-gray-400 2xl:text-base">Présent</label>
                    </div>
                    <div class="inline-flex items-center gap-2">
                        <div class="w-4 h-4 bg-yellow-400 rounded-md"></div>
                        <label class="text-base font-normal text-gray-400 2xl:text-base">Partiellement</label>
                    </div>
                    <div class="inline-flex items-center gap-2">
                        <div class="w-4 h-4 bg-red-400 rounded-md"></div>
                        <label class="text-base font-normal text-gray-400 2xl:text-base">Absent</label>
                    </div>
                </div>
            </div>
            <div class="inline-flex items-center gap-3 p-3">
                <div class="">
                    <label class="label cursor-pointer gap-2">
                        <input type="checkbox"name="checkall" id="checkall"
                            onclick="checkallAppr('checkbox_appr', 'checkall', 'icon_check')" class="checkbox" />
                        <span class="">Tout selectionner</span>
                    </label>
                </div>

                <button type="button" onclick="confirmChecking(3, {{ $idProjet }})"
                    class="btn btn-sm btn-outline btn-success">Présent</button>
                <button type="button" onclick="confirmChecking(1, {{ $idProjet }})"
                    class="btn btn-sm btn-outline btn-error">Absent</button>
                <button type="button" onclick="confirmChecking(2, {{ $idProjet }})"
                    class="btn btn-sm btn-outline btn-warning">Paritellement</button>
            </div>
            {{-- getAllApprPresence --}}
            <div class="h-[50rem]">
                <div class="overflow-x-auto h-full overflow-y-scroll">
                    <table class="table table-bordered getAllApprPresence h-full">
                    </table>
                </div>
            </div>
        </form>
    </div>
</div>
