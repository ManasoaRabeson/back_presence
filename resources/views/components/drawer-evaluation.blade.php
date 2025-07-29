@php
    $id ??= '';
    $idProjet ??= '';
    $idExaminer ??= '';
    $route ??= 'route("cfp.evaluation")';
    $method ??= 'POST';
@endphp
<div class="offcanvas offcanvas-end !w-[65em]" tabindex="-1" id="offcanvasEvaluation_{{ $id }}"
    aria-labelledby="offcanvasEvaluation">
    <div class="flex flex-col w-full">
        <div class="w-full px-4 py-2 inline-flex items-center justify-between bg-gray-100">
            <p class="text-lg text-gray-500 font-medium">Evaluation</p>
            <a data-bs-toggle="offcanvas" href="#offcanvasEvaluation_{{ $id }}"
                class="w-10 h-10 rounded-md hover:bg-gray-200 duration-200 cursor-pointer flex items-center justify-center">
                <i class="fa-solid fa-xmark text-gray-500"></i>
            </a>
        </div>
    </div>

    <div class="w-full flex flex-col gap-4 p-4 h-full overflow-y-auto">
        <form id="form_method_{{ $id }}" method="POST">
            @csrf
            <span class="_method_{{ $id }}"></span>
            <div class="inline-flex items-center w-full justify-between">
                <div class="inline-flex items-center gap-4">
                    <x-titre label="Fiche d'évaluation de " class="text-left w-full mb-4"></x-titre>
                    <span id="emp_eval_{{ $id }}"></span>
                </div>

                <div class="inline-flex items-center gap-2">
                    <div class="inline-flex items-center gap-2 justify-end">
                        <h4 class="examiner_eval_check_{{ $id }} text-gray-400 text-lg"></h4>
                        <span id="examiner_eval_{{ $id }}" class="text-gray-600 text-lg font-medium"></span>
                    </div>

                    <span id="modif_eval_{{ $id }}">
                    </span>
                </div>
            </div>
            <div class="flex flex-col gap-4">
                <span id="content_eval_{{ $id }}" class="flex flex-col gap-4">
                </span>

                <div class="p-4 rounded-md border-[1px] border-gray-200 border-dashed flex flex-col gap-2">
                    <div class="inline-flex items-center w-full gap-4">
                        <label class="text-xl font-semibold text-gray-700">En général</label>
                        <div class="inline-flex items-center gap-3">
                        </div>
                    </div>
                    <div class="inline-flex items-center gap-1 w-full">
                        <div class="w-[60%]">
                            <input type="hidden" name="idProjet" value="{{ $idProjet }}">
                            <input type="hidden" name="idEmploye" value="{{ $id }}">
                            <p class="text-base text-gray-700 pQuestion" data-val=1>APPRECIATION GENERALE DE LA
                                FORMATION</p>
                        </div>
                        <span class="general_{{ $id }}"></span>
                        <div class="flex flex-col gap-0 w-[40%]">
                            <div class="inline-flex items-center w-full justify-end gap-2">
                                <label class="text-gray-400 text-base font-semibold w-full text-right"></label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 rounded-md border-[1px] border-gray-200 border-dashed flex flex-col gap-2">
                    <div class="inline-flex items-center w-full gap-4">
                        <label class="text-xl font-semibold text-gray-700">Commentaires</label>
                        <div class="inline-flex items-center gap-3">
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <input type="hidden" name="idProjet" value="{{ $idProjet }}">
                        <input type="hidden" name="idEmploye" value="{{ $id }}">
                        <h6 class="text-lg font-normal text-gray-500">Qu'est-ce qui vous a paru le plus utile pendant
                            cette
                            formation
                            ?
                        </h6>
                        <span class="val_comment_{{ $id }}"></span>
                    </div>
                    <div class="flex flex-col">
                        <input type="hidden" name="idProjet" value="{{ $idProjet }}">
                        <input type="hidden" name="idEmploye" value="{{ $id }}">
                        <h6 class="text-lg font-normal text-gray-500">Qu'est-ce qui vous a paru le moins utile ?</h6>
                        <span class="com1_{{ $id }}"></span>
                    </div>
                    <div class="flex flex-col">
                        <input type="hidden" name="idProjet" value="{{ $idProjet }}">
                        <input type="hidden" name="idEmploye" value="{{ $id }}">
                        <h6 class="text-lg font-normal text-gray-500">Si vous avez deux changements à proposer pour
                            cette
                            formation,
                            lesquels serait-ils ?</h6>
                        <span class="com2_{{ $id }}"></span>
                    </div>
                </div>

                <span class="btn_submit_eval_{{ $id }}"></span>
            </div>
        </form>
    </div>
</div>
