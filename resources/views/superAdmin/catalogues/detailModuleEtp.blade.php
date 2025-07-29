@extends('layouts.masterAdmin')

@section('content')
    <div class="flex flex-col w-full ">

        @if (Session::has('error'))
            <p class="alert alert-danger">{{ Session('error') }}</p>
        @elseif (Session::has('success'))
            <p class="alert alert-success">{{ Session('success') }}</p>
        @endif

        <div class="flex flex-col w-full max-w-screen-xl mx-auto">

            <div class="min-[350px]:block md:hidden lg:hidden xl:hidden w-full min-w-[450px] overflow-x-auto">
                @include('ETP.moduleInternes.components.detailSm')
            </div>

            <div class="min-[350px]:hidden md:block lg:hidden xl:hidden w-full">
                @include('ETP.moduleInternes.components.detailMd')
            </div>

            <div class="min-[350px]:hidden md:hidden lg:block w-full">
                <div class="w-full inline-flex items-center justify-between bg-[#5B345B] p-3">
                    <div class="flex flex-col">
                        <div class="inline-flex items-center gap-2">
                            <i class="fa solid fa-chevron-right text-xl text-white"></i>
                            <label
                                class="text-xl text-white font-semibold domaine_name_lg">{{ $module->domaine_name }}</label>
                        </div>
                    </div>
                    <div class="inline-flex items-center gap-2">
                        <p class="text-xl text-white font-normal">Ref. {{ $module->module_reference }}</p>
                    </div>
                </div>

                <div class="py-4 gap-4">
                    <div class="inline-flex items-start w-full gap-2">
                        <div class="bg-white rounded-md flex flex-col items-center justify-center gap-1 cursor-pointer">

                            <!-- Modal -->
                            @if (isset($module->module_image))
                                <img src="{{ $digitalOcean }}/img/modules/{{ $module->module_image }}" alt="image"
                                    class="object-center object-cover">
                            @else
                                <img src="{{ asset('img/modules/Logo_mark.svg') }}" alt="logo"
                                    class="object-center w-16 h-auto grayscale opacity-50">
                            @endif

                        </div>

                        <div class="w-full flex">
                            {{-- Titre + sous-titre --}}
                            <div class="flex flex-col gap-1 w-full">
                                <h1 class="text-xl text-gray-700 font-semibold uppercase">{{ $module->module_name }}</h1>
                                <h2 class="text-gray-500 font-normal">
                                    @if (isset($module->module_description))
                                        {{ $module->module_description }}
                                    @else
                                        {{ '--' }}
                                    @endif
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full flex flex-row gap-4">
                    <div class="w-1/4 flex flex-col gap-4">
                        <div
                            class="w-full border-[1px] border-gray-50 bg-gray-50 flex flex-col justify-center rounded-md shadow-sm gap-2 p-4">

                            <div class="inline-flex items-center gap-3">
                                <i class="fa-solid fa-clock text-xl text-gray-500"></i>
                                <div class="flex flex-col">
                                    <p class="text-base text-gray-500 font-medium">
                                        @if (isset($module->dureeH))
                                            {{ $module->dureeH }}h ({{ $module->dureeJ }}jours)
                                        @else
                                            n/a h (n/a jours)
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="inline-flex items-center gap-3">
                                <i class="fa-solid fa-users text-xl text-gray-500"></i>
                                <div class="flex flex-col">
                                    <p class="text-base text-gray-500 font-medium">
                                        @if (isset($module->minApprenant) || isset($module->maxApprenant))
                                            {{ $module->minApprenant }} à {{ $module->maxApprenant }} apprenants
                                        @else
                                            n/a à n/a apprenants
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="inline-flex items-center gap-3">
                                <i class="fa-solid fa-table-columns text-xl text-gray-500"></i>
                                <div class="flex flex-col">
                                    <p class="text-base text-gray-500 font-medium">En ligne / Présentielle</p>
                                </div>
                            </div>

                            <div class="flex flex-col">
                                <div class="inline-flex items-center gap-2">
                                    <h5 class="text-xl font-bold text-gray-700">
                                        @if (isset($module->module_price))
                                            Ar {{ number_format($module->module_price, 2, ',', ' ') }} HT
                                            {{-- {{ $module->module_price }} Ar --}}
                                        @else
                                            n/a Ar
                                        @endif
                                    </h5>
                                    <x-popover>
                                        <x-popover-button>
                                            <div data-serialtip="prix">
                                                <div
                                                    class="w-6 h-6 rounded-full flex items-center justify-center cursor-pointer hover:border-[#5B345B] group duration-150 border-[1px] border-gray-400">
                                                    <i
                                                        class="fa-solid fa-info text-sm text-gray-400 cursor-pointer group-hover:text-gray-700 duration-150"></i>
                                                </div>
                                            </div>
                                        </x-popover-button>
                                        <x-popover-content>
                                            <div data-serialtip-target="prix" class="serialtip-default">
                                                <div class="flex flex-col">
                                                    <h5 class="text-xl font-bold text-gray-700">
                                                        @if (isset($module->prixGroupe))
                                                            Ar {{ number_format($module->prixGroupe, 2, ',', ' ') }} HT
                                                            {{-- {{ $module->prixGroupe }} Ar --}}
                                                        @else
                                                            n/a Ar
                                                        @endif
                                                    </h5>
                                                    <p class="text-base text-gray-400">Prix de formation pour un groupe de
                                                        @if (isset($module->maxApprenant))
                                                            {{ $module->maxApprenant }}
                                                        @else
                                                            n/a
                                                        @endif personnes maximum.
                                                    </p>
                                                </div>
                                            </div>
                                        </x-popover-content>
                                    </x-popover>
                                </div>
                                <p class="text-base text-gray-400">Prix de formation pour une personne.
                                </p>
                            </div>
                        </div>

                        <div
                            class="w-full border-[1px] border-[#5B345B] bg-[#5B345B] rounded-md shadow-sm flex flex-col justify-center gap-3 p-4 group/side2">
                            <div class="flex flex-col gap-2">
                                <div class="inline-flex items-center justify-between">
                                    <div class="inline-flex items-center text-white gap-2">
                                        <i class="fa-solid fa-book-open text-xl"></i>
                                        <h5 class="text-xl font-semibold">Prérequis</h5>
                                    </div>
                                </div>
                                <ul class="flex flex-col gap-1 list-disc pl-4">
                                    <span class="get_all_prerequis"></span>
                                    <li class="prerequis-form text-base truncate h-0 text-white font-normal">
                                        <div class="inline-flex w-full items-center gap-2">
                                            <input id="prerequis_lg" type="text"
                                                class="prerequis_lg form-control form-control-sm outline-none border-b-[1px] w-full border-white p-2 bg-transparent placeholder:text-white placeholder:italic text-white text-base" />
                                            <div onclick="addPrerequis({{ $module->idModule }}, 'prerequis_lg' )"
                                                class="w-9 h-8 text-center flex justify-center cursor-pointer hover:bg-[#9B599B] duration-150 items-center rounded-md border-[1px] border-gray-400">
                                                <i class="fa-solid fa-plus text-sm"></i>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="flex flex-col gap-2">
                                <div class="inline-flex items-center justify-between">
                                    <div class="inline-flex items-center text-white gap-2">
                                        <i class="fa-solid fa-box-open text-xl"></i>
                                        <h5 class="text-xl font-semibold">Matériels utiles à la formation</h5>
                                    </div>
                                </div>
                                <ul class="flex flex-col gap-1 list-disc pl-4">
                                    <span class="get_all_prestation"></span>
                                    <li class="moyen-form text-base truncate h-0 text-white font-normal">
                                        <div class="inline-flex w-full items-center gap-2">
                                            <input id="prestation_name_lg" type="text"
                                                class="prestation_name_lg form-control form-control-sm outline-none border-b-[1px] w-full border-white p-2 bg-transparent placeholder:text-white placeholder:italic text-white text-base" />
                                            <div onclick="addPrestation({{ $module->idModule }}, 'prestation_name_lg' )"
                                                class="w-9 h-8 text-center flex justify-center cursor-pointer hover:bg-[#9B599B] duration-150 items-center rounded-md border-[1px] border-gray-400">
                                                <i class="fa-solid fa-plus text-sm"></i>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="flex flex-col w-full gap-2">
                                <div class="inline-flex items-center justify-between">
                                    <div class="inline-flex items-center text-white gap-2">
                                        <i class="fa-solid fa-users text-xl"></i>
                                        <h5 class="text-xl font-semibold">Public concerné</h5>
                                    </div>
                                </div>
                                <ul class="flex flex-col gap-1 list-disc w-full pl-4">
                                    <span class="get_all_cible"></span>
                                    <li class="cible-form text-base h-0 truncate text-white font-normal">
                                        <div class="inline-flex w-full items-center gap-2">
                                            <input id="cible_name_lg" type="text"
                                                class="form-control form-control-sm outline-none border-b-[1px] w-full border-white p-2 bg-transparent placeholder:text-white placeholder:italic text-white text-base"
                                                autofocus />
                                            <div onclick="addCible({{ $module->idModule }}, 'cible_name_lg')"
                                                class="w-9 h-8 text-center flex justify-center cursor-pointer hover:bg-[#9B599B] duration-150 items-center rounded-md border-[1px] border-gray-400">
                                                <i class="fa-solid fa-plus text-sm"></i>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        {{-- <x-rating url="{{ route('catalogueFormation.avis') }}" /> --}}
                    </div>
                    <div class="w-3/4 flex h-max flex-col gap-y-4">

                        <div class="w-full h-full bg-white flex flex-row p-4 group/right1">
                            <div class="flex flex-1 flex-col gap-2">
                                <div class="inline-flex items-center gap-4">
                                    <div class="inline-flex items-center text-[#9B599B] gap-2">
                                        <i class="fa-solid fa-bullseye text-xl"></i>
                                        <h5 class="text-xl font-semibold">Objectifs</h5>
                                    </div>
                                </div>
                                <ul class="flex flex-col gap-1 list-disc p-4">
                                    <span class="get_all_objectif"></span>
                                    <li class="objectif-form truncate h-0 text-lg text-gray-500 font-normal">
                                        <div class="inline-flex w-full items-center gap-2">
                                            <input id="objectif_name_lg" type="text" placeholder="Ajouter un objectif"
                                                class="form-control form-control-sm outline-none border-b-[1px] w-full border-gray-200 p-2 bg-transparent placeholder:text-gray-500 placeholder:italic text-gray-500 text-base" />
                                            <div onclick="addObjectif({{ $module->idModule }}, 'objectif_name_lg')"
                                                class="w-9 h-8 text-center flex justify-center cursor-pointer hover:bg-gray-200 duration-150 items-center rounded-md border-[1px] border-gray-500">
                                                <i class="fa-solid fa-plus text-sm"></i>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div
                            class="w-full h-full border-[1px] border-gray-100 bg-gray-100 rounded-md shadow-sm flex flex-col gap-3 p-4 group/right2">
                            <div class="inline-flex items-center gap-4">
                                <div class="inline-flex items-center text-gray-500 gap-2">
                                    <i class="fa-solid fa-chalkboard text-xl"></i>
                                    <h5 class="text-xl font-semibold">Programme pédagogique</h5>
                                </div>
                            </div>
                            <div class="programme-form max-w-screen-md mx-auto w-full truncate h-0 flex flex-col gap-4">
                                <form action="{{ route('etp.programmes.store', $module->idModule) }}" method="post">
                                    @csrf
                                    <x-input name="program_title" label="Titre du module" />
                                    <div class="flex flex-col">
                                        <label for="" class="text-base text-gray-500">Programme dans le
                                            module</label>
                                        <textarea name="program_description" class="program_description_textarea_lg hidden" cols="30" rows="10"></textarea>
                                        <div id="program_description_lg">
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div
                                class="programme-form-edit max-w-screen-md mx-auto w-full truncate h-0 flex flex-col gap-4">
                                <input type="hidden" class="idProgramme">
                                <x-input name="program_title_edit" screen="lg" label="Titre du module" />
                                <div class="flex flex-col">
                                    <label for="" class="text-base text-gray-500">Programme dans le module</label>
                                    <textarea name="program_description_edit" class="program_description_textarea_edit_lg hidden" cols="30"
                                        rows="10"></textarea>
                                    <div id="program_description_edit_lg" class="program_description_edit">
                                    </div>
                                </div>
                            </div>

                            <div id="get_all_program"
                                class="get_all_program grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom_style')
    <link rel="stylesheet" href="{{ asset('css/quill.snow.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cropper.css') }}">
    <style>
        img {
            display: block;
            max-width: 100%;
        }

        .previewMdl {
            text-align: center;
            overflow: hidden;
            width: 160px;
            height: 160px;
            margin: 10px;
            border: 1px solid red;
        }

        .modal-lg {
            max-width: 1000px;
        }
    </style>
@endpush

@section('script')
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/quill.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/cropper.js') }}"></script>
    <script>
        // Wysiwyg QUILLjs récupération des données
        const optionsModulePrograms = {
            debug: 'info',
            modules: {
                toolbar: true,
            },
            placeholder: 'Ajouter votre programme...',
            theme: 'snow',
        };

        const quill = new Quill('#program_description', optionsModulePrograms);

        quill.on('text-change', function() {
            var justHtml = quill.root.innerHTML;
            $('.program_description_textarea').val(justHtml);
        });

        const quill_lg = new Quill('#program_description_lg', optionsModulePrograms);

        quill_lg.on('text-change', function() {
            var justHtml = quill_lg.root.innerHTML;
            $('.program_description_textarea_lg').val(justHtml);
        });

        $(document).ready(function() {
            getObjectif({{ $module->idModule }});
            getPrestation({{ $module->idModule }});
            getCible({{ $module->idModule }});
            getProgram({{ $module->idModule }});

            var owl = $('.owl-carousel');
            owl.owlCarousel({
                items: 3,
                loop: true,
                margin: 10,
                autoplay: true,
                autoplayTimeout: 3000,
                autoplayHoverPause: true,
            });
            $('.play').on('click', function() {
                owl.trigger('play.owl.autoplay', [1000])
            })
            $('.stop').on('click', function() {
                owl.trigger('stop.owl.autoplay')
            })

            $('.btn-programme').click(function(e) {
                e.preventDefault();
                $('.programme-form').toggleClass('h-max', 'h-0');
            });

            $('.btn-cible').click(function(e) {
                e.preventDefault();
                $('.cible-form').toggleClass('h-max', 'h-0');
            });

            $('.btn-moyen').click(function(e) {
                e.preventDefault();
                $('.moyen-form').toggleClass('h-max', 'h-0');
            });

            $('.btn-objectif').click(function(e) {
                e.preventDefault();
                $('.objectif-form').toggleClass('h-max', 'h-0');
            });
        });


        function getObjectif(idModule) {
            $.ajax({
                type: "get",
                url: "/superAdmins/etp/modules/" + idModule + "/objectifs",
                dataType: "json",
                success: function(res) {
                    var get_all_objectif = $('.get_all_objectif');
                    get_all_objectif.html('');

                    if (res.objectifs.length <= 0) {
                        get_all_objectif.append(`<li class="text-lg font-normal text-gray-500">--</li>`);
                    } else {
                        $.each(res.objectifs, function(key, val) {
                            get_all_objectif.append(
                                `<li class="text-lg font-normal text-gray-500"><div class="inline-flex items-center justify-between w-full text-lg">` +
                                val.objectif +
                                ` </li></div>`);
                        });
                    }
                }
            });
        }


        function getPrestation(idModule) {
            $.ajax({
                type: "get",
                url: "/superAdmins/etp/modules/" + idModule + "/prestations",
                dataType: "json",
                success: function(res) {
                    var get_all_prestation = $('.get_all_prestation');
                    get_all_prestation.html('');

                    if (res.prestations.length <= 0) {
                        get_all_prestation.append(`<li class="text-base font-normal text-white">--</li>`);
                    } else {
                        $.each(res.prestations, function(key, val) {
                            get_all_prestation.append(
                                `<li class="text-base font-normal text-white"><div class="inline-flex items-center justify-between w-full text-lg">` +
                                val.prestation_name +
                                ` </li></div>`);
                        });
                    }
                }
            });
        }


        function getCible(idModule) {
            $.ajax({
                type: "get",
                url: "/superAdmins/etp/modules/" + idModule + "/cibles",
                dataType: "json",
                success: function(res) {
                    var get_all_cible = $('.get_all_cible');
                    get_all_cible.html('');

                    if (res.cibles.length <= 0) {
                        get_all_cible.append(`<li class="text-base font-normal text-white">--</li>`);
                    } else {
                        $.each(res.cibles, function(key, val) {
                            get_all_cible.append(
                                `<li class="w-full text-base font-normal text-white"> <div class="inline-flex items-center justify-between w-full text-lg">` +
                                val
                                .cible +
                                ` <i style="cursor: pointer;" onclick='deleteCible(` + val.idCible +
                                `)' class="justify-end text-sm fa-solid fa-xmark"></i></li></div>`);
                        });
                    }
                }
            });
        }

        function getProgram(idModule) {
            $.ajax({
                type: "get",
                url: "/superAdmins/etp/programmes/" + idModule,
                dataType: "json",
                success: function(res) {
                    var get_all_program = $('.get_all_program');
                    var i = 1;
                    get_all_program.html('');

                    if (res.programmes.length <= 0) {
                        get_all_program.append(`<h3>--</h3>`);
                    } else {
                        $.each(res.programmes, function(key, val) {
                            get_all_program.append(
                                `<div class="flex flex-col w-full gap-2">
                                <div class="inline-flex items-center gap-6">
                                    <h4 class="text-xl font-bold text-gray-700">Module ` + i++ + `</h4>
                                    <i onclick="showUpdateprogram(` + val.idProgramme + `)" class="text-sm text-gray-500 fa-solid fa-pen btn-programme-edit"></i>
                                    <i style='cursor: pointer;' onclick="deleteProgram(` + val.idProgramme + `)" class="text-sm text-gray-500 fa-solid fa-trash-can"></i>
                                </div>
                                <h5 class="text-[#A462A4] text-xl font-semibold">` + val.program_title + `</h5>
                                <div>` + val.program_description + `</div>
                                <hr class="sm:block md:hidden border-[1px] border-gray-400 my-2">
                                </div>`);
                        });
                    }
                }
            });
        }
    </script>

    <script>
        //var idApprenant = $('#id_apprenant_hidden').val();
        var $modal = $('#cropmdl');
        var image = document.getElementById('imagemdl');
        var cropper;

        $("body").on("change", ".logoFileMdl", function(e) {
            // e.stopPropagation();

            var files = e.target.files;
            console.log('events==>', e.target);
            console.log('Files-->', files);
            var done = function(url) {
                image.src = url;
                $modal.modal('show');
            };

            var reader;
            var file;
            var url;

            if (files && files.length > 0) {
                file = files[0];

                if (URL) {
                    done(URL.createObjectURL(file));
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function(e) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });

        $modal.on('shown.bs.modal', function() {
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 3,
                preview: '.previewMdl'
            });
        }).on('hidden.bs.modal', function() {
            cropper.destroy();
            cropper = null;
        });

        function getPrerequis(idModule) {
            $.ajax({
                type: "get",
                url: "/etp/modules/" + idModule + "/prerequis",
                dataType: "json",
                success: function(res) {
                    var get_all_prerequis = $('.get_all_prerequis');
                    get_all_prerequis.html('');

                    if (res.prerequis.length <= 0) {
                        get_all_prerequis.append(`<li class="text-base font-normal text-white">--</li>`);
                    } else {
                        $.each(res.prerequis, function(key, val) {
                            get_all_prerequis.append(
                                `<li class="text-base font-normal text-white"><div class="inline-flex items-center justify-between w-full text-lg">` +
                                val.prerequis_name +
                                ` </li></div>`);
                        });
                    }
                }
            });
        }
    </script>
@endsection
