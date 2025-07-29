@extends($extends_containt)

@push('custom_style')
    <link rel="stylesheet" href="{{ asset('css/jquery.raty.css') }}">
    <style>
        .hidden {
            display: none;
        }
    </style>
@endpush

@section('content')
    <div class="w-full h-full overflow-y-scroll mb-6">
        <div class="flex flex-col h-full space-y-20">
            <div class="py-6 h-full">

                <div id="content" class="">
                    <div id="content" class="">
                        <div class="slide">
                            <div class="flex items-center space-x-4">
                                @if ($firstPublicite)
                                    <a href="/formation/detail/{{ $firstPublicite->idModule }}">
                                        <div
                                            class="bg-gradient-to-r from-blue-100 via-white to-purple-100 shadow-md rounded-xl overflow-hidden flex h-64 md:h-72 lg:h-80 max-w-screen-xl mx-auto mb-14 w-full">
                                            <div class="flex flex-col justify-between px-8 py-6 w-2/3 lg:w-1/2">
                                                <div class="flex items-center space-x-4">
                                                    <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/entreprises/{{ $firstPublicite->logo }}"
                                                        alt="Logo centre de formation" class="h-20 w-40" />
                                                    <p class="text-xl font-bold text-gray-900">
                                                        {{ $firstPublicite->customerName }}</p>
                                                </div>

                                                <div class="mt-8">
                                                    <p class="text-4xl font-extrabold text-gray-800 mb-2 tracking-wide">
                                                        {{ $firstPublicite->moduleName }}
                                                    </p>
                                                    <p class="text-sm text-gray-600 leading-relaxed">
                                                        {{ $firstPublicite->description ?? 'Aucune description' }}
                                                    </p>
                                                </div>

                                                <div
                                                    class="flex flex-col md:flex-row mt-4 space-y-2 md:space-y-0 md:space-x-8">
                                                    <div class="flex items-center text-lg font-medium text-gray-700">
                                                        <i class="fa-solid fa-phone mr-2"></i>
                                                        {{ $firstPublicite->customerPhone ?? 'Non renseigné' }}
                                                    </div>

                                                    <div class="flex items-center text-lg font-medium">
                                                        <i class="fa-solid fa-globe mr-2"></i>
                                                        <a href="https://example.com"
                                                            class="hover:underline">{{ $firstPublicite->customerEmail ?? 'Non renseigné' }}</a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="w-1/3 lg:w-1/2">
                                                <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/modules/{{ $firstPublicite->module_image }}"
                                                    alt="Photo de la formation"
                                                    class="h-full w-full rounded-l-lg md:rounded-none md:rounded-r-lg shadow-md" />
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            </div>
                        </div>
                        @foreach ($otherPublicites as $otherPublicite)
                            <div class="slide hidden">
                                <a href="/formation/detail/{{ $otherPublicite->idModule }}">
                                    <div class="flex items-center space-x-4">
                                        <div
                                            class="bg-gradient-to-r from-blue-100 via-white to-purple-100 shadow-md rounded-xl overflow-hidden flex h-64 md:h-72 lg:h-80 max-w-screen-xl mx-auto mb-14 w-full">
                                            <div class="flex flex-col justify-between px-8 py-6 w-2/3 lg:w-1/2">
                                                <div class="flex items-center space-x-4">
                                                    <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/entreprises/{{ $otherPublicite->logo }} "
                                                        alt="Logo centre de formation" class="h-20 w-40" />
                                                    <p class="text-xl font-bold text-gray-900">
                                                        {{ $otherPublicite->customerName }}</p>
                                                </div>

                                                <div class="mt-8">
                                                    <p class="text-4xl font-extrabold text-gray-800 mb-2 tracking-wide">
                                                        {{ $otherPublicite->moduleName }}
                                                    </p>
                                                    <p class="text-sm text-gray-600 leading-relaxed">
                                                        {{ $otherPublicite->description ?? 'Aucune description' }}
                                                    </p>
                                                </div>

                                                <div
                                                    class="flex flex-col md:flex-row mt-4 space-y-2 md:space-y-0 md:space-x-8">
                                                    <div class="flex items-center text-lg font-medium text-gray-700">
                                                        <i class="fa-solid fa-phone mr-2"></i>
                                                        {{ $otherPublicite->customerPhone ?? 'Non renseigné' }}
                                                    </div>

                                                    <div class="flex items-center text-lg font-medium">
                                                        <i class="fa-solid fa-globe mr-2"></i>
                                                        <a href="https://example.com"
                                                            class="hover:underline">{{ $otherPublicite->customerEmail ?? 'Non renseigné' }}</a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="w-1/3 lg:w-1/2">
                                                <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/modules/{{ $otherPublicite->module_image }}"
                                                    alt="Photo de la formation"
                                                    class="h-full w-full rounded-l-lg md:rounded-none md:rounded-r-lg shadow-md" />
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="w-full h-full max-w-screen-xl mx-auto" id="search_results">
                    <div class="grid grid-cols-1 lg:grid-cols-4 mx-6">
                        <div class="grid col-span-1 w-full grid-cols-subgrid">
                            <ul class="menu rounded-box h-full">
                                <label class="input input-bordered flex items-center gap-2">
                                    <input type="text" id="search" class="grow" value="{{ $course }}"
                                        placeholder="Trouver votre formation" />
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                        class="h-4 w-4 opacity-70">
                                        <path fill-rule="evenodd"
                                            d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </label>
                                <ul class="menu menu-sm rounded-box">
                                    <span class="flex justify-between mb-3">
                                        <p class="text-xl font-bold">Filtrer par</p>

                                        <p id="reset" class="text-blue-500 hidden lg:block cursor-pointer">
                                            Réinitialiser </p>
                                        <p id="open-modal" class="block lg:hidden"> <i
                                                class="fa-solid fa-bars-staggered fa-xl text-blue-500"></i></p>
                                    </span>
                                    <li class="w-full" id="sessions">
                                        <div class="flex items-center space-x-2">
                                            <input type="checkbox" class="checkbox" id="session_guaranteed">
                                            <label for="session_guaranteed" class="cursor-pointer">Sessions
                                                garanties
                                            </label>
                                        </div>
                                    </li>
                                </ul>
                                <ul id="filter" class="filter" class="menu">
                                </ul>
                            </ul>
                        </div>
                        <div class="grid col-span-1 lg:col-span-3 grid-cols-subgrid">
                            <div class="flex flex-col px-4">
                                <div class="inline-flex items-center gap-2 mb-4">
                                    <h2 class="text-xl lg:text-base w-max" id="filter_selected">
                                    </h2>

                                    <div class="inline-flex items-start flex-1 gap-2">
                                        <div class="gap-2 inline-flex items-start flex-wrap" id="selected-items">
                                        </div>
                                        <div class="flex gap-2" id="selecteds-guaranteed">
                                        </div>
                                    </div>
                                </div>
                                <span class="w-full" id="project_list">
                                </span>
                                <div class="inline-flex justify-center mt-12">
                                    <ul class="flex items-center" id="pagination">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <span id="show-modal"></span>
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/jquery.raty.js') }}"></script>
    <script src="{{ asset('js/global_js.js') }}"></script>
    <script>
        const slides = document.querySelectorAll('.slide');
        let currentIndex = 0;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.toggle('hidden', i !== index);
            });
        }

        function cycleSlides() {
            showSlide(currentIndex);
            currentIndex = (currentIndex + 1) % slides.length;
        }

        showSlide(currentIndex);
        setInterval(cycleSlides, 3000);
    </script>
    <script>
        function loadBsTooltip() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        };

        loadBsTooltip();
        $(document).ready(function() {
            $(document).ready(function() {
                $('.project-description').each(function() {
                    var $description = $(this).find('p[id^="description_"]');
                    var totalHeight = $description.height();
                    var lineHeight = parseFloat($description.css('line-height'));
                    var lineCount = Math.round(totalHeight / lineHeight);

                    var $noteDiv = $(this).find('div[id^="note_"]');

                    if (lineCount === 1) {
                        $noteDiv.after('<br><br>');
                    } else if (lineCount === 2) {
                        $noteDiv.after('<br>');
                    }
                });
            });

            $('#menu-button').on('click', function() {
                const dropdownMenu = $('#dropdown-menu');
                dropdownMenu.toggleClass('hidden');
                dropdownMenu.toggleClass('block');
            });

            window.addEventListener('click', function(event) {
                if (!event.target.closest('#menu-button') && !event.target.closest('#dropdown-menu')) {
                    $('#dropdown-menu').addClass('hidden')
                    $('#dropdown-menu').removeClass('block')
                }
            });

            $(document).on('click', '#reset', function() {
                reset();
                $('#session_guaranteed').prop('checked', false).trigger('change');
            });

            getProject();
        });

        function openDialog(html, box = "modal_content_master") {
            // Créer un nouvel élément dialog
            const dialog = $('<dialog class="du_modal"></dialog>');

            // Ajouter le contenu du modal
            dialog.html(html);

            // Ajouter le dialog au body
            var modal_content = $(`#${box}`)
            modal_content.html('');

            modal_content.append(dialog);

            // Ouvrir le modal
            dialog[0].showModal();

            // Écouter l'événement de fermeture
            dialog.find('.du_modal-action').on('click', function() {
                dialog[0].close();
                dialog.remove(); // Retirer le modal du DOM
            });
        }

        function toggleVisibility(list, toggleButton) {
            var listItems = list.find('li');
            var maxVisibleItems = 6;

            if (listItems.length > maxVisibleItems) {
                listItems.slice(maxVisibleItems).hide();

                var hiddenItemsCount = listItems.length - maxVisibleItems;

                toggleButton.text(`Afficher ${hiddenItemsCount} autres`);
                toggleButton.removeClass('hidden');

                toggleButton.click(function(event) {
                    event.preventDefault();

                    if (toggleButton.text().includes('autres')) {
                        listItems.show();
                        toggleButton.text('Voir moins');
                    } else {
                        listItems.slice(maxVisibleItems).hide();
                        toggleButton.text(`Afficher ${hiddenItemsCount} autres`);
                    }
                });
            }
        }

        function search() {
            $('#search').on('keyup', function() {
                let debounceTimer;
                clearTimeout(debounceTimer);

                let keySearch = $(this).val();

                debounceTimer = setTimeout(function() {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('searchJson.formation') }}",
                        data: {
                            course: keySearch,
                            place: 'all',
                            category: 'all'
                        },
                        beforeSend: function() {
                            let project_list = $('#project_list');
                            project_list.html('');
                            project_list.append(`<x-skeleton-module/>`);

                            let sessions = $('#sessions');
                            sessions.html('');
                            sessions.append(`<div class="flex animate-pulse">
                                <p class="h-3 w-40 bg-gray-200">
                            </div>`);

                            let filter = $('.filter');
                            filter.html('');
                            filter.append(`<x-project-filter-skeleton />`);
                        },
                        success: function(resul) {
                            let filter_selected = $(
                                '#filter_selected');
                            filter_selected.html('');
                            filter_selected.append(
                                `${resul.project_count} cours trouvé(s)`
                            );

                            let sessions = $('#sessions');
                            sessions.html('');
                            sessions.append(`<div class="flex items-center space-x-2">
                                                            <input type="checkbox" id="session_guaranteed">
                                                            <label for="session_guaranteed">Sessions garanties <span class="text-gray-500"> (${resul.session_guaranteeds}) </span>  </label>
                                                        </div>`);

                            let lien = "{{ route('filter.course.formation') }}";

                            if (resul.projects.length > 0) {
                                let project_list = $(
                                    '#project_list');
                                project_list.html('');
                                project_list.append(resul.projectHtml);

                                let filter = $('.filter');
                                filter.html('');
                                filter.append(resul.filterHtml);

                                $(document).on('click',
                                    '#open-modal',
                                    function() {
                                        html = `<div class='du_modal-box w-full'>
                                            <ul>
                                                ${resul.filterHtmlMobile}
                                            </ul>
                                        <div/>`;
                                        openDialog(html,
                                            'show-modal'
                                        );
                                    });
                                raty();

                                toggleVisibilityAll();

                                selectedDomaineIdsValue = [];
                                selectedVilleIdsValue = [];
                                selectedCfpIdsValue = [];
                                selectedDuringIdsValue = [];

                                selectItem(lien);

                                getProjectPaginateSearch(page = 1);
                            } else {
                                let project_list = $(
                                    '#project_list');

                                project_list.html('');
                                project_list.append(``);

                                let filter = $('.filter');
                                filter.html('');
                                filter.append(``);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                        }
                    });
                }, 800);

                $('.selected-domaine, .selected-ville, .selected-cfp, .selected-during')
                    .each(function() {
                        var selectedDiv = $(this);
                        var checkboxId = selectedDiv.data('checkbox-id');
                        var checkboxType = selectedDiv.data('checkbox-type');
                        var domaineIds = [];
                        var villeIds = [];
                        var cfpIds = [];
                        var duringIds = [];

                        $('#' + checkboxId).prop('checked', false);

                        selectedDiv.remove();

                        switch (checkboxType) {
                            case 'domaine':
                                domaineIds.splice(domaineIds.indexOf(checkboxId
                                    .split('_')[1]), 1);
                                break;
                            case 'ville':
                                villeIds.splice(villeIds.indexOf(checkboxId
                                    .split('_')[1]), 1);
                                break;
                            case 'cfp':
                                cfpIds.splice(cfpIds.indexOf(checkboxId.split(
                                    '_')[1]), 1);
                                break;
                            case 'during':
                                duringIds.splice(duringIds.indexOf(checkboxId
                                    .split('_')[1]), 1);
                                break;
                        }
                    });
            });
        }

        function reset() {
            $.ajax({
                type: "GET",
                url: "{{ route('searchJson.formation') }}",
                data: {
                    category: 'all',
                    course: null,
                    place: 'all',
                    cfp: null
                },
                beforeSend: function() {
                    let project_list = $('#project_list');
                    project_list.html('');
                    project_list.append(`<x-skeleton-module/>`);

                    let sessions = $('#sessions');
                    sessions.html('');
                    sessions.append(`<div class="flex animate-pulse">
                        <p class="h-4 text-slate-600 w-48">
                    </div>`);

                    let filter = $('.filter');
                    filter.html('');
                    filter.append(`<x-project-filter-skeleton />`);

                },
                success: function(res) {
                    let filter_selected = $('#filter_selected');
                    filter_selected.html('');
                    filter_selected.append(`${res.project_count} cours trouvé(s)`);

                    if (res.projects.length > 0) {
                        let project_list = $('#project_list');
                        project_list.html('');
                        project_list.append(res.projectHtml);

                        let sessions = $('#sessions');
                        sessions.html('');
                        sessions.append(`<div class="flex items-center space-x-2">
                                            <input type="checkbox" id="session_guaranteed">
                                            <label for="session_guaranteed">Sessions garanties <span class="text-gray-500"> (${res.session_guaranteeds}) </span>  </label>
                                        </div>`);

                        let filter = $('.filter');
                        filter.html('');
                        filter.append(res.filterHtml);

                        $(document).on('click', '#open-modal', function() {
                            html = `<div class='du_modal-box w-full'>
                                        <ul>
                                            ${res.filterHtmlMobile}
                                        </ul>
                                    <div/>`;
                            openDialog(html, 'show-modal');
                        });

                        raty();

                        toggleVisibilityAll();

                        let linq = "{{ route('filter.course.formation') }}";
                        selectItem(linq);
                        if (res.project_count > 21) {
                            getProjectPaginateReset(page = 1);
                        } else {
                            let paginationContainerFilter = $('#pagination');
                            paginationContainerFilter.html('');
                        }
                    } else {
                        let project_list = $('#project_list');

                        project_list.html('');
                        project_list.append(`<p> Aucun resultat</p>`);
                    }

                    search();

                    sessionGuaranteed();

                    removeAllSelected();
                },
                error: function(err) {
                    console.log(err);
                }
            });
            selectedDomaineIdsValue = [];
            selectedVilleIdsValue = [];
            selectedCfpIdsValue = [];
            selectedDuringIdsValue = [];

            $('#search').val('');
        }

        function filterBtn() {
            $('#filterDropdown').toggle();

            $('#filterDropdown button[type="submit"]').on('click', function(e) {
                e.preventDefault();

                let selectedTime = $('input[name="filter"]:checked').val();
                let startDate = $('#filterDropdown input[type="date"]').eq(0).val();
                let endDate = $('#filterDropdown input[type="date"]').eq(1).val();

                let domaineIds = sessionStorage.getItem("domaineIds");
                let villeIds = sessionStorage.getItem("villeIds");
                let cfpIds = sessionStorage.getItem("cfpIds");
                let duringIds = sessionStorage.getItem("duringIds");
                let valueSearch = $('#search').val();

                sessionStorage.setItem("startDate", startDate);
                sessionStorage.setItem("endDate", endDate);
                sessionStorage.setItem("selectedTime", selectedTime);

                $.ajax({
                    type: "get",
                    url: "{{ route('filter.courseGuaranteed') }}",
                    data: {
                        valueSearch: valueSearch,
                        domaineIds: domaineIds,
                        villeIds: villeIds,
                        cfpIds: cfpIds,
                        duringIds: duringIds,
                        selectedTime: selectedTime,
                        startDate: startDate,
                        endDate: endDate
                    },
                    beforeSend: function() {
                        let project_list = $('#project_list');
                        project_list.html('');
                        project_list.append(`<x-skeleton-guaranteed-project/>`);
                    },
                    success: function(respo) {
                        if (respo.project_count > 0) {
                            let project_list = $('#project_list');
                            project_list.html('');
                            project_list.append(respo.projectHtml);

                            let filter_selected = $('#filter_selected');
                            filter_selected.html('');
                            filter_selected.append(`${respo.project_count} cours trouvé(s)`);

                            getProjectGuaranteedPaginateFilter(page = 1);

                        } else {
                            let project_list = $('#project_list');
                            project_list.html('');
                            project_list.append(respo.projectHtml);

                            let filter_selected = $('#filter_selected');
                            filter_selected.html('');
                            filter_selected.append(`${respo.project_count} cours trouvé(s)`);
                        }
                    }
                });
            })
        }

        function getSelectedIds(idsArray) {
            return idsArray.join(',');
        }

        function updateSelectedItems(itemType, itemName, checkbox) {
            var selectedContainer = $('#selected-items');

            var existingItem = selectedContainer.find('.selected-' +
                itemType + '[data-name="' + itemName + '"]');

            if (existingItem.length === 0) {
                selectedContainer.append(
                    `<div class="selected-${itemType} badge badge-primary badge-outline w-max" data-name="${itemName}" data-checkbox-id="${checkbox.attr('id')}" data-checkbox-type="${itemType}">${itemName}<i class="ml-3 fa-solid fa-xmark remove-item"></i></div>`
                );
            } else {
                existingItem.remove();
            }
        }

        function updateSelectedItemsGuaranteed(itemType, itemName, checkbox) {
            var selectedContainer = $('#selecteds-guaranteed');

            var existingItem = selectedContainer.find('.selected-' +
                itemType + '[data-name="' + itemName + '"]');

            if (existingItem.length === 0) {
                selectedContainer.append(
                    `<div class="selected-${itemType} badge badge-primary badge-outline w-max" data-name="${itemName}" data-checkbox-id="${checkbox.attr('id')}" data-checkbox-type="${itemType}">${itemName}<i class="ml-3 fa-solid fa-xmark remove-item"></i></div>`
                );
            } else {
                existingItem.remove();
            }
        }

        function selectItem(linkSelectItem) {
            let linkSelect = linkSelectItem;
            let domaineIds = [];
            let villeIds = [];
            let cfpIds = [];
            let duringIds = [];
            let levelIds = [];

            function handleCheckboxChange(className, idArray, type) {
                $(className).on('change', function() {
                    let id = $(this).attr('id').split('_')[1];
                    let index = idArray.indexOf(id);

                    if ($(this).is(':checked')) {
                        if (index === -1) {
                            idArray.push(id);
                        }
                    } else {
                        if (index !== -1) {
                            idArray.splice(index, 1);
                        }
                    }

                    var name = $(this).val();
                    updateSelectedItems(type, name, $(this));

                    processSelectedIds(linkSelect);
                });
            }

            handleCheckboxChange('.domaine-checkbox', domaineIds, 'domaine');
            handleCheckboxChange('.ville-checkbox', villeIds, 'ville');
            handleCheckboxChange('.cfp-checkbox', cfpIds, 'cfp');
            handleCheckboxChange('.during-checkbox', duringIds, 'during');
            handleCheckboxChange('.level-checkbox', levelIds, 'level');

            function processSelectedIds(linkSelect) {
                let valueSearch = $('#search').val();
                let selectedDomaineIds = getSelectedIds(domaineIds);
                let selectedVilleIds = getSelectedIds(villeIds);
                let selectedCfpIds = getSelectedIds(cfpIds);
                let selectedDuringIds = getSelectedIds(duringIds);
                let selectedLevelIds = getSelectedIds(levelIds);

                sessionStorage.setItem("domaineIds", selectedDomaineIds);
                sessionStorage.setItem("villeIds", selectedVilleIds);
                sessionStorage.setItem("cfpIds", selectedCfpIds);
                sessionStorage.setItem("duringIds", selectedDuringIds);
                let selectedTime = sessionStorage.getItem("selectedTime");
                let startDate = sessionStorage.getItem("startDate");
                let endDate = sessionStorage.getItem("endDate");

                $.ajax({
                    type: "GET",
                    url: linkSelect,
                    data: {
                        valueSearch: valueSearch,
                        domaineIds: selectedDomaineIds,
                        villeIds: selectedVilleIds,
                        cfpIds: selectedCfpIds,
                        duringIds: selectedDuringIds,
                        selectedTime: selectedTime,
                        startDate: startDate,
                        endDate: endDate,
                        levelIds: selectedLevelIds
                    },
                    beforeSend: function() {
                        let project_list = $('#project_list');
                        project_list.html('');
                        project_list.append(`<x-skeleton-module/>`);
                    },
                    success: function(response) {
                        if (response.project_count > 0) {
                            selectedDomaineIdsValue = selectedDomaineIds;
                            selectedVilleIdsValue = selectedVilleIds;
                            selectedCfpIdsValue = selectedCfpIds;
                            selectedDuringIdsValue = selectedDuringIds;

                            let filter_selected = $('#filter_selected');
                            filter_selected.html('');
                            filter_selected.append(`${response.project_count} cours trouvé(s)`);

                            let project_list = $('#project_list');
                            project_list.html('');
                            project_list.append(response.projectHtml);

                            if (response.project_count > 21) {
                                getProjectPaginateFilter(page = 1);
                            } else {
                                let paginationContainerFilter = $('#pagination');
                                paginationContainerFilter.html('');
                            }

                            raty();
                        } else {
                            let project_list = $('#project_list');

                            project_list.html('');
                            project_list.append(`<p> Aucun resultat</p>`);

                            let filter_selected = $('#filter_selected');
                            filter_selected.html('');
                            filter_selected.append(`0 cours trouvé(s)`);
                        }
                    },
                    error: function(erreur) {
                        console.log(erreur);
                    }
                });
            }

            $('#selected-items').on('click', '.remove-item', function() {
                var selectedDiv = $(this).closest(
                    '.selected-domaine, .selected-ville, .selected-cfp, .selected-during, .selected-level');
                var checkboxId = selectedDiv.data('checkbox-id');
                var checkboxType = selectedDiv.data('checkbox-type');

                $('#' + checkboxId).prop('checked', false);

                selectedDiv.remove();

                switch (checkboxType) {
                    case 'domaine':
                        domaineIds.splice(domaineIds.indexOf(checkboxId.split('_')[1]), 1);
                        break;
                    case 'ville':
                        villeIds.splice(villeIds.indexOf(checkboxId.split('_')[1]), 1);
                        break;
                    case 'cfp':
                        cfpIds.splice(cfpIds.indexOf(checkboxId.split('_')[1]), 1);
                        break;
                    case 'during':
                        duringIds.splice(duringIds.indexOf(checkboxId.split('_')[1]), 1);
                        break;
                    case 'level':
                        levelIds.splice(levelIds.indexOf(checkboxId.split('_')[1]), 1);
                        break;
                }

                selectedDomaineIdsValue = domaineIds;
                selectedVilleIdsValue = villeIds;
                selectedCfpIdsValue = cfpIds;
                selectedDuringIdsValue = duringIds;
                selectedLevelIdsValue = levelIds;
                processSelectedIds(linkSelect);
            });

        }

        function selectItemGuaranteed(linkSelectItem) {
            let linkSelect = linkSelectItem;
            let domaineIdsGuaranteed = [];
            let villeIdsGuaranteed = [];
            let cfpIdsGuaranteed = [];
            let duringIdsGuaranteed = [];
            let levelIdsGuaranteed = [];

            function handleCheckboxChange(className, idArray, type) {
                $(className).on('change', function() {
                    let id = $(this).attr('id').split('_')[1];
                    let index = idArray.indexOf(id);

                    if ($(this).is(':checked')) {
                        if (index === -1) {
                            idArray.push(id);
                        }
                    } else {
                        if (index !== -1) {
                            idArray.splice(index, 1);
                        }
                    }

                    var name = $(this).val();
                    updateSelectedItemsGuaranteed(type, name, $(this));

                    processSelectedIdsGuaranteed(linkSelect);
                });
            }

            handleCheckboxChange('.domaine-checkbox', domaineIdsGuaranteed, 'domaine');
            handleCheckboxChange('.ville-checkbox', villeIdsGuaranteed, 'ville');
            handleCheckboxChange('.cfp-checkbox', cfpIdsGuaranteed, 'cfp');
            handleCheckboxChange('.during-checkbox', duringIdsGuaranteed, 'during');
            handleCheckboxChange('.level-checkbox', levelIdsGuaranteed, 'level');

            function processSelectedIdsGuaranteed(linkSelect) {
                let valueSearch = $('#search').val();
                let selectedDomaineIds = getSelectedIds(domaineIdsGuaranteed);
                let selectedVilleIds = getSelectedIds(villeIdsGuaranteed);
                let selectedCfpIds = getSelectedIds(cfpIdsGuaranteed);
                let selectedDuringIds = getSelectedIds(duringIdsGuaranteed);
                let selectedLevelIds = getSelectedIds(levelIdsGuaranteed);

                sessionStorage.setItem("domaineIds", selectedDomaineIds);
                sessionStorage.setItem("villeIds", selectedVilleIds);
                sessionStorage.setItem("cfpIds", selectedCfpIds);
                sessionStorage.setItem("duringIds", selectedDuringIds);
                sessionStorage.setItem("levelIds", selectedLevelIds);
                let selectedTime = sessionStorage.getItem("selectedTime");
                let startDate = sessionStorage.getItem("startDate");
                let endDate = sessionStorage.getItem("endDate");

                $.ajax({
                    type: "GET",
                    url: linkSelect,
                    data: {
                        valueSearch: valueSearch,
                        domaineIds: selectedDomaineIds,
                        villeIds: selectedVilleIds,
                        cfpIds: selectedCfpIds,
                        duringIds: selectedDuringIds,
                        selectedTime: selectedTime,
                        startDate: startDate,
                        endDate: endDate,
                        levelIds: selectedLevelIds
                    },
                    beforeSend: function() {
                        let project_list = $('#project_list');
                        project_list.html('');
                        project_list.append(`<x-skeleton-guaranteed-project/>`);
                    },
                    success: function(response) {
                        if (response.project_count > 0) {
                            let filter_selected = $(
                                '#filter_selected');
                            filter_selected.html('');
                            filter_selected.append(`${response.project_count} cours trouvé(s)`);

                            let project_list = $('#project_list');
                            project_list.html('');
                            project_list.append(response.projectHtml);

                            raty();

                            if (response.project_count > 10) {
                                getProjectGuaranteedPaginateFilter(page = 1);
                            } else {
                                let paginationContainerFilter = $('#pagination');
                                paginationContainerFilter.html('');
                            }
                        } else {
                            let project_list = $('#project_list');

                            project_list.html('');
                            project_list.append(`<p> Aucun resultat</p>`);

                            let filter_selected = $('#filter_selected');
                            filter_selected.html('');
                            filter_selected.append(`0 cours trouvé(s)`);
                        }

                    },
                    error: function(erreur) {
                        console.log(erreur);
                    }
                });
            }

            $('#selecteds-guaranteed').on('click', '.remove-item', function() {
                var selectedDiv = $(this).closest(
                    '.selected-domaine, .selected-ville, .selected-cfp, .selected-during, .selected-level');
                var checkboxId = selectedDiv.data('checkbox-id');
                var checkboxType = selectedDiv.data('checkbox-type');

                $('#' + checkboxId).prop('checked', false);

                selectedDiv.remove();

                switch (checkboxType) {
                    case 'domaine':
                        domaineIdsGuaranteed.splice(domaineIdsGuaranteed.indexOf(checkboxId.split('_')[1]), 1);
                        break;
                    case 'ville':
                        villeIdsGuaranteed.splice(villeIdsGuaranteed.indexOf(checkboxId.split('_')[1]), 1);
                        break;
                    case 'cfp':
                        cfpIdsGuaranteed.splice(cfpIdsGuaranteed.indexOf(checkboxId.split('_')[1]), 1);
                        break;
                    case 'during':
                        duringIdsGuaranteed.splice(duringIdsGuaranteed.indexOf(checkboxId.split('_')[1]), 1);
                        break;
                    case 'level':
                        levelIdsGuaranteed.splice(levelIdsGuaranteed.indexOf(checkboxId.split('_')[1]), 1);
                        break;
                }

                processSelectedIdsGuaranteed(linkSelect);
            });
        }

        function sessionGuaranteed() {
            $('#session_guaranteed').change(function() {
                let key = $('#search').val();
                if ($(this).is(':checked')) {
                    $.ajax({
                        type: "get",
                        url: "{{ route('sessionGuaranteed.formation') }}",
                        data: {
                            valueSearch: key
                        },
                        beforeSend: function() {
                            let project_list = $('#project_list');
                            project_list.html('');
                            project_list.append(`<x-skeleton-guaranteed-project/>`);


                            let filter = $('.filter');
                            filter.html('');
                            filter.append(`<x-project-filter-skeleton />`);
                        },
                        success: function(resp) {
                            console.log(resp.projects);

                            let project_list = $('#project_list');
                            project_list.html('');
                            project_list.append(resp.projectsHtml);

                            let filter = $('.filter');
                            filter.html('');
                            filter.append(resp.filterHtml);

                            let filter_selected = $('#filter_selected');
                            filter_selected.html('');
                            filter_selected.append(`${resp.project_count} cours trouvé(s)`);

                            let liens = "{{ route('filter.courseGuaranteed') }}";
                            sessionStorage.setItem("domaineIds", "");
                            sessionStorage.setItem("villeIds", "");
                            sessionStorage.setItem("cfpIds", "");
                            sessionStorage.setItem("duringIds", "");
                            sessionStorage.setItem("selectedTime", "");
                            sessionStorage.setItem("startDate", "");
                            sessionStorage.setItem("endDate", "");

                            toggleVisibilityAll();
                            raty();
                            selectItemGuaranteed(liens);

                            if (resp.project_count > 10) {
                                getProjectGuaranteedPaginate(page = 1);
                            } else {
                                let paginationContainerFilter = $('#pagination');
                                paginationContainerFilter.html('');
                            }
                        }
                    });
                } else {
                    reset();
                }
            });
        }

        function getProject() {
            $.ajax({
                type: "GET",
                url: "{{ route('searchJson.formation') }}",
                data: {
                    category: '{{ $category }}',
                    course: '{{ $course }}',
                    place: '{{ $place }}',
                    cfp: '{{ $cfp }}'
                },
                beforeSend: function() {
                    let project_list = $('#project_list');
                    project_list.html('');
                    project_list.append(`<x-skeleton-module/>`);

                    let sessions = $('#sessions');
                    sessions.html('');
                    sessions.append(`<div class="flex animate-pulse">
                        <p class="h-4 text-slate-600 w-48">
                    </div>`);

                    let filter = $('.filter');
                    filter.html('');
                    filter.append(`<x-project-filter-skeleton />`);

                },
                success: function(res) {
                    let filter_selected = $('#filter_selected');
                    filter_selected.html('');
                    filter_selected.append(`${res.project_count} cours trouvé(s)`);

                    if (res.projects.length > 0) {
                        let project_list = $('#project_list');
                        project_list.html('');
                        project_list.append(res.projectHtml);

                        let sessions = $('#sessions');
                        sessions.html('');
                        sessions.append(`<div class="flex items-center space-x-2">
                                                    <input type="checkbox" id="session_guaranteed">
                                                    <label for="session_guaranteed">Sessions garanties <span class="text-gray-500"> (${res.session_guaranteeds}) </span>  </label>
                                                </div>`);

                        let filter = $('.filter');
                        filter.html('');
                        filter.append(res.filterHtml);

                        $(document).on('click', '#open-modal', function() {
                            html = `<div class='du_modal-box w-full'>
                                <ul>
                                    ${res.filterHtmlMobile}
                                </ul>
                            <div/>`;
                            openDialog(html, 'show-modal');
                        });

                        raty();
                        let linq = "{{ route('filter.course.formation') }}";

                        selectItem(linq);

                        toggleVisibilityAll();

                        if (res.project_count > 21) {
                            getProjectPaginate(page = 1);
                        } else {
                            let paginationContainerFilter = $('#pagination');
                            paginationContainerFilter.html('');
                        }

                    } else {
                        let project_list = $('#project_list');

                        project_list.html('');
                        project_list.append(`<p> Aucun resultat</p>`);
                    }
                    search();

                    sessionGuaranteed();
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }

        function getProjectPaginate(page = 1) {
            $.ajax({
                type: "GET",
                url: "{{ route('searchJson.formation') }}",
                data: {
                    category: '{{ $category }}',
                    course: '{{ $course }}',
                    place: '{{ $place }}',
                    cfp: '{{ $cfp }}',
                    page: page
                },
                beforeSend: function() {
                    let project_list = $('#project_list');
                    project_list.html('');
                    project_list.append(`<x-skeleton-module/>`);
                },
                success: function(res) {
                    if (res.projects.length > 0) {
                        let project_list = $('#project_list');
                        project_list.html('');
                        project_list.append(res.projectHtml);

                        let paginationContainer = $('#pagination');
                        paginationContainer.html('');

                        let prevButton =
                            `<li class="px-2"><button aria-disabled="${res.current_page == 1 ? 'true' : 'false'}" 
                            class="w-9 h-9 flex items-center justify-center rounded-md border ${res.current_page == 1 ? 'disabled' : ''}" id="prevPage" ${res.current_page == 1 ? 'disabled' : ''}>
                            <span><svg width="8" height="15" viewBox="0 0 8 15" class="fill-current stroke-current"><path d="M7.12979 1.91389L7.1299 1.914L7.1344 1.90875C7.31476 1.69833 7.31528 1.36878 7.1047 1.15819C7.01062 1.06412 6.86296 1.00488 6.73613 1.00488C6.57736 1.00488 6.4537 1.07206 6.34569 1.18007L6.34564 1.18001L6.34229 1.18358L0.830207 7.06752C0.830152 7.06757 0.830098 7.06763 0.830043 7.06769C0.402311 7.52078 0.406126 8.26524 0.827473 8.73615L0.827439 8.73618L0.829982 8.73889L6.34248 14.6014L6.34243 14.6014L6.34569 14.6047C6.546 14.805 6.88221 14.8491 7.1047 14.6266C7.30447 14.4268 7.34883 14.0918 7.12833 13.8693L1.62078 8.01209C1.55579 7.93114 1.56859 7.82519 1.61408 7.7797L1.61413 7.77975L1.61729 7.77639L7.12979 1.91389Z" stroke-width="0.3"></path></svg></span></button></li>`;
                        paginationContainer.append(prevButton);

                        const currentPage = res.current_page;
                        const lastPage = res.last_page;

                        pagination(currentPage, paginationContainer, lastPage);

                        let nextButton =
                            `<li class="px-2"><button aria-disabled="${res.current_page == res.last_page ? 'true' : 'false'}" 
                            class="w-9 h-9 flex items-center justify-center rounded-md border ${res.current_page == res.last_page ? 'disabled' : ''}" id="nextPage" ${res.current_page == res.last_page ? 'disabled' : ''}>
                            <span><svg width="8" height="15" viewBox="0 0 8 15" class="fill-current stroke-current"><path d="M0.870212 13.0861L0.870097 13.086L0.865602 13.0912C0.685237 13.3017 0.684716 13.6312 0.895299 13.8418C0.989374 13.9359 1.13704 13.9951 1.26387 13.9951C1.42264 13.9951 1.5463 13.9279 1.65431 13.8199L1.65436 13.82L1.65771 13.8164L7.16979 7.93248C7.16985 7.93243 7.1699 7.93237 7.16996 7.93231C7.59769 7.47923 7.59387 6.73477 7.17253 6.26385L7.17256 6.26382L7.17002 6.26111L1.65752 0.398611L1.65757 0.398563L1.65431 0.395299C1.454 0.194997 1.11779 0.150934 0.895299 0.373424C0.695526 0.573197 0.651169 0.908167 0.871667 1.13067L6.37922 6.98791C6.4442 7.06886 6.43141 7.17481 6.38592 7.2203L6.38587 7.22025L6.38271 7.22361L0.870212 13.0861Z" stroke-width="0.3"></path></svg></span></button></li>`;
                        paginationContainer.append(nextButton);

                        $('.page-link').on('click', function(e) {
                            e.preventDefault();
                            let page = $(this).data('page');
                            getProjectPaginate(page);
                        });

                        $('#prevPage').on('click', function() {
                            if (res.current_page > 1) {
                                getProjectPaginate(res.current_page - 1);
                            }
                        });

                        $('#nextPage').on('click', function() {
                            if (res.current_page < res.last_page) {
                                getProjectPaginate(res.current_page + 1);
                            }
                        });

                        $(document).on('click', '#open-modal', function() {
                            html = `<div class='du_modal-box w-full'>
                                <ul>
                                    ${res.filterHtmlMobile}
                                </ul>
                            <div/>`;
                            openDialog(html, 'show-modal');
                        });

                        raty();
                    } else {
                        let project_list = $('#project_list');

                        project_list.html('');
                        project_list.append(`<p> Aucun resultat</p>`);
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }

        function getProjectPaginateSearch(page = 1) {
            $.ajax({
                type: "GET",
                url: "{{ route('searchJson.formation') }}",
                data: {
                    category: 'all',
                    course: $('#search').val(),
                    place: 'all',
                    cfp: null,
                    page: page
                },
                beforeSend: function() {
                    let project_list = $('#project_list');
                    project_list.html('');
                    project_list.append(`<x-skeleton-module/>`);
                },
                success: function(res) {
                    if (res.projects.length > 0) {
                        let project_list = $('#project_list');
                        project_list.html('');
                        project_list.append(res.projectHtml);

                        let paginationContainer = $('#pagination');
                        paginationContainer.html('');

                        let prevButton =
                            `<li class="px-2"><button aria-disabled="${res.current_page == 1 ? 'true' : 'false'}" 
                            class="w-9 h-9 flex items-center justify-center rounded-md border ${res.current_page == 1 ? 'disabled' : ''}" id="prevPage" ${res.current_page == 1 ? 'disabled' : ''}>
                            <span><svg width="8" height="15" viewBox="0 0 8 15" class="fill-current stroke-current"><path d="M7.12979 1.91389L7.1299 1.914L7.1344 1.90875C7.31476 1.69833 7.31528 1.36878 7.1047 1.15819C7.01062 1.06412 6.86296 1.00488 6.73613 1.00488C6.57736 1.00488 6.4537 1.07206 6.34569 1.18007L6.34564 1.18001L6.34229 1.18358L0.830207 7.06752C0.830152 7.06757 0.830098 7.06763 0.830043 7.06769C0.402311 7.52078 0.406126 8.26524 0.827473 8.73615L0.827439 8.73618L0.829982 8.73889L6.34248 14.6014L6.34243 14.6014L6.34569 14.6047C6.546 14.805 6.88221 14.8491 7.1047 14.6266C7.30447 14.4268 7.34883 14.0918 7.12833 13.8693L1.62078 8.01209C1.55579 7.93114 1.56859 7.82519 1.61408 7.7797L1.61413 7.77975L1.61729 7.77639L7.12979 1.91389Z" stroke-width="0.3"></path></svg></span></button></li>`;
                        paginationContainer.append(prevButton);

                        const currentPage = res.current_page;
                        const lastPage = res.last_page;

                        pagination(currentPage, paginationContainer, lastPage);

                        let nextButton =
                            `<li class="px-2"><button aria-disabled="${res.current_page == res.last_page ? 'true' : 'false'}" 
                            class="w-9 h-9 flex items-center justify-center rounded-md border ${res.current_page == res.last_page ? 'disabled' : ''}" id="nextPage" ${res.current_page == res.last_page ? 'disabled' : ''}>
                            <span><svg width="8" height="15" viewBox="0 0 8 15" class="fill-current stroke-current"><path d="M0.870212 13.0861L0.870097 13.086L0.865602 13.0912C0.685237 13.3017 0.684716 13.6312 0.895299 13.8418C0.989374 13.9359 1.13704 13.9951 1.26387 13.9951C1.42264 13.9951 1.5463 13.9279 1.65431 13.8199L1.65436 13.82L1.65771 13.8164L7.16979 7.93248C7.16985 7.93243 7.1699 7.93237 7.16996 7.93231C7.59769 7.47923 7.59387 6.73477 7.17253 6.26385L7.17256 6.26382L7.17002 6.26111L1.65752 0.398611L1.65757 0.398563L1.65431 0.395299C1.454 0.194997 1.11779 0.150934 0.895299 0.373424C0.695526 0.573197 0.651169 0.908167 0.871667 1.13067L6.37922 6.98791C6.4442 7.06886 6.43141 7.17481 6.38592 7.2203L6.38587 7.22025L6.38271 7.22361L0.870212 13.0861Z" stroke-width="0.3"></path></svg></span></button></li>`;
                        paginationContainer.append(nextButton);

                        $('.page-link').on('click', function(e) {
                            e.preventDefault();
                            let page = $(this).data('page');
                            getProjectPaginate(page);
                        });

                        $('#prevPage').on('click', function() {
                            if (res.current_page > 1) {
                                getProjectPaginate(res.current_page - 1);
                            }
                        });

                        $('#nextPage').on('click', function() {
                            if (res.current_page < res.last_page) {
                                getProjectPaginate(res.current_page + 1);
                            }
                        });

                        $(document).on('click', '#open-modal', function() {
                            html = `<div class='du_modal-box w-full'>
                                <ul>
                                    ${res.filterHtmlMobile}
                                </ul>
                            <div/>`;
                            openDialog(html, 'show-modal');
                        });

                        raty();
                    } else {
                        let project_list = $('#project_list');

                        project_list.html('');
                        project_list.append(`<p> Aucun resultat</p>`);
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }

        function getProjectGuaranteedPaginate(page = 1) {
            $.ajax({
                type: "GET",
                url: "{{ route('sessionGuaranteed.formation') }}",
                data: {
                    valueSearch: $('#search').val(),
                    page: page
                },
                beforeSend: function() {
                    let project_list = $('#project_list');
                    project_list.html('');
                    project_list.append(`<x-skeleton-guaranteed-project/>`);
                },
                success: function(res) {
                    if (res.projects.length > 0) {
                        let project_list = $('#project_list');
                        project_list.html('');
                        project_list.append(res.projectsHtml);

                        let paginationContainer = $('#pagination');
                        paginationContainer.html('');

                        let prevButton =
                            `<li class="px-2"><button aria-disabled="${res.current_page == 1 ? 'true' : 'false'}" 
                            class="w-9 h-9 flex items-center justify-center rounded-md border ${res.current_page == 1 ? 'disabled' : ''}" id="prevPage" ${res.current_page == 1 ? 'disabled' : ''}>
                            <span><svg width="8" height="15" viewBox="0 0 8 15" class="fill-current stroke-current"><path d="M7.12979 1.91389L7.1299 1.914L7.1344 1.90875C7.31476 1.69833 7.31528 1.36878 7.1047 1.15819C7.01062 1.06412 6.86296 1.00488 6.73613 1.00488C6.57736 1.00488 6.4537 1.07206 6.34569 1.18007L6.34564 1.18001L6.34229 1.18358L0.830207 7.06752C0.830152 7.06757 0.830098 7.06763 0.830043 7.06769C0.402311 7.52078 0.406126 8.26524 0.827473 8.73615L0.827439 8.73618L0.829982 8.73889L6.34248 14.6014L6.34243 14.6014L6.34569 14.6047C6.546 14.805 6.88221 14.8491 7.1047 14.6266C7.30447 14.4268 7.34883 14.0918 7.12833 13.8693L1.62078 8.01209C1.55579 7.93114 1.56859 7.82519 1.61408 7.7797L1.61413 7.77975L1.61729 7.77639L7.12979 1.91389Z" stroke-width="0.3"></path></svg></span></button></li>`;
                        paginationContainer.append(prevButton);

                        const currentPage = res.current_page;
                        const lastPage = res.last_page;

                        pagination(currentPage, paginationContainer, lastPage);

                        let nextButton =
                            `<li class="px-2"><button aria-disabled="${res.current_page == res.last_page ? 'true' : 'false'}" 
                            class="w-9 h-9 flex items-center justify-center rounded-md border ${res.current_page == res.last_page ? 'disabled' : ''}" id="nextPage" ${res.current_page == res.last_page ? 'disabled' : ''}>
                            <span><svg width="8" height="15" viewBox="0 0 8 15" class="fill-current stroke-current"><path d="M0.870212 13.0861L0.870097 13.086L0.865602 13.0912C0.685237 13.3017 0.684716 13.6312 0.895299 13.8418C0.989374 13.9359 1.13704 13.9951 1.26387 13.9951C1.42264 13.9951 1.5463 13.9279 1.65431 13.8199L1.65436 13.82L1.65771 13.8164L7.16979 7.93248C7.16985 7.93243 7.1699 7.93237 7.16996 7.93231C7.59769 7.47923 7.59387 6.73477 7.17253 6.26385L7.17256 6.26382L7.17002 6.26111L1.65752 0.398611L1.65757 0.398563L1.65431 0.395299C1.454 0.194997 1.11779 0.150934 0.895299 0.373424C0.695526 0.573197 0.651169 0.908167 0.871667 1.13067L6.37922 6.98791C6.4442 7.06886 6.43141 7.17481 6.38592 7.2203L6.38587 7.22025L6.38271 7.22361L0.870212 13.0861Z" stroke-width="0.3"></path></svg></span></button></li>`;
                        paginationContainer.append(nextButton);

                        $('.page-link').on('click', function(e) {
                            e.preventDefault();
                            let page = $(this).data('page');
                            getProjectGuaranteedPaginate(page);
                        });

                        $('#prevPage').on('click', function() {
                            if (res.current_page > 1) {
                                getProjectGuaranteedPaginate(res.current_page - 1);
                            }
                        });

                        $('#nextPage').on('click', function() {
                            if (res.current_page < res.last_page) {
                                getProjectGuaranteedPaginate(res.current_page + 1);
                            }
                        });

                        $(document).on('click', '#open-modal', function() {
                            html = `<div class='du_modal-box w-full'>
                                <ul>
                                    ${res.filterHtmlMobile}
                                </ul>
                            <div/>`;
                            openDialog(html, 'show-modal');
                        });

                        raty();
                    } else {
                        let project_list = $('#project_list');

                        project_list.html('');
                        project_list.append(`<p> Aucun resultat</p>`);
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }

        function getProjectGuaranteedPaginateFilter(page = 1) {
            let selectedDomaineIds = sessionStorage.getItem("domaineIds");
            let selectedVilleIds = sessionStorage.getItem("villeIds");
            let selectedCfpIds = sessionStorage.getItem("cfpIds");
            let selectedDuringIds = sessionStorage.getItem("duringIds");
            let selectedTime = sessionStorage.getItem("selectedTime");
            let startDate = sessionStorage.getItem("startDate");
            let endDate = sessionStorage.getItem("endDate");
            $.ajax({
                type: "GET",
                url: "{{ route('filter.courseGuaranteed') }}",
                data: {
                    valueSearch: $('#search').val(),
                    domaineIds: selectedDomaineIds,
                    villeIds: selectedVilleIds,
                    cfpIds: selectedCfpIds,
                    duringIds: selectedDuringIds,
                    selectedTime: selectedTime,
                    startDate: startDate,
                    endDate: endDate,
                    page: page
                },
                beforeSend: function() {
                    let project_list = $('#project_list');
                    project_list.html('');
                    project_list.append(`<x-skeleton-guaranteed-project/>`);
                },
                success: function(res) {
                    if (res.project_count > 0) {
                        let project_list = $('#project_list');
                        project_list.html('');
                        project_list.append(res.projectHtml);

                        let paginationContainer = $('#pagination');
                        paginationContainer.html('');

                        let prevButton =
                            `<li class="px-2"><button aria-disabled="${res.current_page == 1 ? 'true' : 'false'}" 
                            class="w-9 h-9 flex items-center justify-center rounded-md border ${res.current_page == 1 ? 'disabled' : ''}" id="prevPage" ${res.current_page == 1 ? 'disabled' : ''}>
                            <span><svg width="8" height="15" viewBox="0 0 8 15" class="fill-current stroke-current"><path d="M7.12979 1.91389L7.1299 1.914L7.1344 1.90875C7.31476 1.69833 7.31528 1.36878 7.1047 1.15819C7.01062 1.06412 6.86296 1.00488 6.73613 1.00488C6.57736 1.00488 6.4537 1.07206 6.34569 1.18007L6.34564 1.18001L6.34229 1.18358L0.830207 7.06752C0.830152 7.06757 0.830098 7.06763 0.830043 7.06769C0.402311 7.52078 0.406126 8.26524 0.827473 8.73615L0.827439 8.73618L0.829982 8.73889L6.34248 14.6014L6.34243 14.6014L6.34569 14.6047C6.546 14.805 6.88221 14.8491 7.1047 14.6266C7.30447 14.4268 7.34883 14.0918 7.12833 13.8693L1.62078 8.01209C1.55579 7.93114 1.56859 7.82519 1.61408 7.7797L1.61413 7.77975L1.61729 7.77639L7.12979 1.91389Z" stroke-width="0.3"></path></svg></span></button></li>`;
                        paginationContainer.append(prevButton);

                        const currentPage = res.current_page;
                        const lastPage = res.last_page;

                        pagination(currentPage, paginationContainer, lastPage);

                        let nextButton =
                            `<li class="px-2"><button aria-disabled="${res.current_page == res.last_page ? 'true' : 'false'}" 
                            class="w-9 h-9 flex items-center justify-center rounded-md border ${res.current_page == res.last_page ? 'disabled' : ''}" id="nextPage" ${res.current_page == res.last_page ? 'disabled' : ''}>
                            <span><svg width="8" height="15" viewBox="0 0 8 15" class="fill-current stroke-current"><path d="M0.870212 13.0861L0.870097 13.086L0.865602 13.0912C0.685237 13.3017 0.684716 13.6312 0.895299 13.8418C0.989374 13.9359 1.13704 13.9951 1.26387 13.9951C1.42264 13.9951 1.5463 13.9279 1.65431 13.8199L1.65436 13.82L1.65771 13.8164L7.16979 7.93248C7.16985 7.93243 7.1699 7.93237 7.16996 7.93231C7.59769 7.47923 7.59387 6.73477 7.17253 6.26385L7.17256 6.26382L7.17002 6.26111L1.65752 0.398611L1.65757 0.398563L1.65431 0.395299C1.454 0.194997 1.11779 0.150934 0.895299 0.373424C0.695526 0.573197 0.651169 0.908167 0.871667 1.13067L6.37922 6.98791C6.4442 7.06886 6.43141 7.17481 6.38592 7.2203L6.38587 7.22025L6.38271 7.22361L0.870212 13.0861Z" stroke-width="0.3"></path></svg></span></button></li>`;
                        paginationContainer.append(nextButton);

                        $('.page-link').on('click', function(e) {
                            e.preventDefault();
                            let page = $(this).data('page');
                            getProjectGuaranteedPaginateFilter(page);
                        });

                        $('#prevPage').on('click', function() {
                            if (res.current_page > 1) {
                                getProjectGuaranteedPaginateFilter(res.current_page - 1);
                            }
                        });

                        $('#nextPage').on('click', function() {
                            if (res.current_page < res.last_page) {
                                getProjectGuaranteedPaginateFilter(res.current_page + 1);
                            }
                        });

                        $(document).on('click', '#open-modal', function() {
                            html = `<div class='du_modal-box w-full'>
                                <ul>
                                    ${res.filterHtmlMobile}
                                </ul>
                            <div/>`;
                            openDialog(html, 'show-modal');
                        });

                        raty();
                    } else {
                        let project_list = $('#project_list');

                        project_list.html('');
                        project_list.append(`<p> Aucun resultat</p>`);
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }

        function getProjectPaginateReset(page = 1) {
            $.ajax({
                type: "GET",
                url: "{{ route('searchJson.formation') }}",
                data: {
                    category: 'all',
                    course: null,
                    place: 'all',
                    cfp: null,
                    page: page
                },
                beforeSend: function() {
                    let project_list = $('#project_list');
                    project_list.html('');
                    project_list.append(`<x-skeleton-module/>`);
                },
                success: function(res) {
                    if (res.projects.length > 0) {
                        let project_list = $('#project_list');
                        project_list.html('');
                        project_list.append(res.projectHtml);

                        let paginationContainer = $('#pagination');
                        paginationContainer.html('');

                        let prevButton =
                            `<li class="px-2"><button aria-disabled="${res.current_page == 1 ? 'true' : 'false'}" 
                            class="w-9 h-9 flex items-center justify-center rounded-md border ${res.current_page == 1 ? 'disabled' : ''}" id="prevPage" ${res.current_page == 1 ? 'disabled' : ''}>
                            <span><svg width="8" height="15" viewBox="0 0 8 15" class="fill-current stroke-current"><path d="M7.12979 1.91389L7.1299 1.914L7.1344 1.90875C7.31476 1.69833 7.31528 1.36878 7.1047 1.15819C7.01062 1.06412 6.86296 1.00488 6.73613 1.00488C6.57736 1.00488 6.4537 1.07206 6.34569 1.18007L6.34564 1.18001L6.34229 1.18358L0.830207 7.06752C0.830152 7.06757 0.830098 7.06763 0.830043 7.06769C0.402311 7.52078 0.406126 8.26524 0.827473 8.73615L0.827439 8.73618L0.829982 8.73889L6.34248 14.6014L6.34243 14.6014L6.34569 14.6047C6.546 14.805 6.88221 14.8491 7.1047 14.6266C7.30447 14.4268 7.34883 14.0918 7.12833 13.8693L1.62078 8.01209C1.55579 7.93114 1.56859 7.82519 1.61408 7.7797L1.61413 7.77975L1.61729 7.77639L7.12979 1.91389Z" stroke-width="0.3"></path></svg></span></button></li>`;
                        paginationContainer.append(prevButton);

                        const currentPage = res.current_page;
                        const lastPage = res.last_page;

                        pagination(currentPage, paginationContainer, lastPage);

                        let nextButton =
                            `<li class="px-2"><button aria-disabled="${res.current_page == res.last_page ? 'true' : 'false'}" 
                            class="w-9 h-9 flex items-center justify-center rounded-md border ${res.current_page == res.last_page ? 'disabled' : ''}" id="nextPage" ${res.current_page == res.last_page ? 'disabled' : ''}>
                            <span><svg width="8" height="15" viewBox="0 0 8 15" class="fill-current stroke-current"><path d="M0.870212 13.0861L0.870097 13.086L0.865602 13.0912C0.685237 13.3017 0.684716 13.6312 0.895299 13.8418C0.989374 13.9359 1.13704 13.9951 1.26387 13.9951C1.42264 13.9951 1.5463 13.9279 1.65431 13.8199L1.65436 13.82L1.65771 13.8164L7.16979 7.93248C7.16985 7.93243 7.1699 7.93237 7.16996 7.93231C7.59769 7.47923 7.59387 6.73477 7.17253 6.26385L7.17256 6.26382L7.17002 6.26111L1.65752 0.398611L1.65757 0.398563L1.65431 0.395299C1.454 0.194997 1.11779 0.150934 0.895299 0.373424C0.695526 0.573197 0.651169 0.908167 0.871667 1.13067L6.37922 6.98791C6.4442 7.06886 6.43141 7.17481 6.38592 7.2203L6.38587 7.22025L6.38271 7.22361L0.870212 13.0861Z" stroke-width="0.3"></path></svg></span></button></li>`;
                        paginationContainer.append(nextButton);

                        $('.page-link').on('click', function(e) {
                            e.preventDefault();
                            let page = $(this).data('page');
                            getProjectPaginateReset(page);
                        });

                        $('#prevPage').on('click', function() {
                            if (res.current_page > 1) {
                                getProjectPaginateReset(res.current_page - 1);
                            }
                        });

                        $('#nextPage').on('click', function() {
                            if (res.current_page < res.last_page) {
                                getProjectPaginateReset(res.current_page + 1);
                            }
                        });

                        $(document).on('click', '#open-modal', function() {
                            html = `<div class='du_modal-box w-full'>
                                <ul>
                                    ${res.filterHtmlMobile}
                                </ul>
                            <div/>`;
                            openDialog(html, 'show-modal');
                        });

                        raty();
                    } else {
                        let project_list = $('#project_list');

                        project_list.html('');
                        project_list.append(`<p> Aucun resultat</p>`);
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }

        function getProjectPaginateFilter(page = 1) {
            $.ajax({
                type: "GET",
                url: "{{ route('filter.course.formation') }}",
                data: {
                    valueSearch: valueSearchValue,
                    domaineIds: selectedDomaineIdsValue,
                    villeIds: selectedVilleIdsValue,
                    cfpIds: selectedCfpIdsValue,
                    duringIds: selectedDuringIdsValue,
                    page: page
                },
                beforeSend: function() {
                    let project_list = $('#project_list');
                    project_list.html('');
                    project_list.append(`<x-skeleton-module/>`);
                },
                success: function(resu) {
                    if (resu.projects.length > 0) {
                        let project_list = $('#project_list');
                        project_list.html('');
                        project_list.append(resu.projectHtml);

                        let paginationContainerFilter = $('#pagination');
                        paginationContainerFilter.html('');

                        let prevButton =
                            `<li class="px-2"><button aria-disabled="${resu.current_page == 1 ? 'true' : 'false'}" 
                            class="w-9 h-9 flex items-center justify-center rounded-md border ${resu.current_page == 1 ? 'disabled' : ''}" id="prevPage" ${resu.current_page == 1 ? 'disabled' : ''}>
                            <span><svg width="8" height="15" viewBox="0 0 8 15" class="fill-current stroke-current"><path d="M7.12979 1.91389L7.1299 1.914L7.1344 1.90875C7.31476 1.69833 7.31528 1.36878 7.1047 1.15819C7.01062 1.06412 6.86296 1.00488 6.73613 1.00488C6.57736 1.00488 6.4537 1.07206 6.34569 1.18007L6.34564 1.18001L6.34229 1.18358L0.830207 7.06752C0.830152 7.06757 0.830098 7.06763 0.830043 7.06769C0.402311 7.52078 0.406126 8.26524 0.827473 8.73615L0.827439 8.73618L0.829982 8.73889L6.34248 14.6014L6.34243 14.6014L6.34569 14.6047C6.546 14.805 6.88221 14.8491 7.1047 14.6266C7.30447 14.4268 7.34883 14.0918 7.12833 13.8693L1.62078 8.01209C1.55579 7.93114 1.56859 7.82519 1.61408 7.7797L1.61413 7.77975L1.61729 7.77639L7.12979 1.91389Z" stroke-width="0.3"></path></svg></span></button></li>`;
                        paginationContainerFilter.append(prevButton);

                        const currentPage = resu.current_page;
                        const lastPage = resu.last_page;

                        pagination(currentPage, paginationContainerFilter, lastPage);

                        let nextButton =
                            `<li class="px-2"><button aria-disabled="${resu.current_page == resu.last_page ? 'true' : 'false'}" 
                            class="w-9 h-9 flex items-center justify-center rounded-md border ${resu.current_page == resu.last_page ? 'disabled' : ''}" id="nextPage" ${resu.current_page == resu.last_page ? 'disabled' : ''}>
                            <span><svg width="8" height="15" viewBox="0 0 8 15" class="fill-current stroke-current"><path d="M0.870212 13.0861L0.870097 13.086L0.865602 13.0912C0.685237 13.3017 0.684716 13.6312 0.895299 13.8418C0.989374 13.9359 1.13704 13.9951 1.26387 13.9951C1.42264 13.9951 1.5463 13.9279 1.65431 13.8199L1.65436 13.82L1.65771 13.8164L7.16979 7.93248C7.16985 7.93243 7.1699 7.93237 7.16996 7.93231C7.59769 7.47923 7.59387 6.73477 7.17253 6.26385L7.17256 6.26382L7.17002 6.26111L1.65752 0.398611L1.65757 0.398563L1.65431 0.395299C1.454 0.194997 1.11779 0.150934 0.895299 0.373424C0.695526 0.573197 0.651169 0.908167 0.871667 1.13067L6.37922 6.98791C6.4442 7.06886 6.43141 7.17481 6.38592 7.2203L6.38587 7.22025L6.38271 7.22361L0.870212 13.0861Z" stroke-width="0.3"></path></svg></span></button></li>`;
                        paginationContainerFilter.append(nextButton);

                        $('.page-link').on('click', function(e) {
                            e.preventDefault();
                            let page = $(this).data('page');
                            getProjectPaginateFilter(page);
                        });

                        $('#prevPage').on('click', function() {
                            if (resu.current_page > 1) {
                                getProjectPaginateFilter(resu.current_page - 1);
                            }
                        });

                        $('#nextPage').on('click', function() {
                            if (resu.current_page < resu.last_page) {
                                getProjectPaginateFilter(resu.current_page + 1);
                            }
                        });

                        $(document).on('click', '#open-modal', function() {
                            html = `<div class='du_modal-box w-full'>
                                <ul>
                                    ${resu.filterHtmlMobile}
                                </ul>
                            <div/>`;
                            openDialog(html, 'show-modal');
                        });

                        raty();

                    } else {
                        let project_list = $('#project_list');

                        project_list.html('');
                        project_list.append(`<p> Aucun resultat</p>`);
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }

        function removeAllSelected() {
            var selectedContainer = $('#selected-items');
            selectedContainer.html('');
            var selectedContainerGuaranteed = $('#selecteds-guaranteed');
            selectedContainerGuaranteed.html('');
            let selectedDomaineIdsValue = [];
            let selectedVilleIdsValue = [];
            let selectedCfpIdsValue = [];
            let selectedDuringIdsValue = [];
        }

        function raty() {
            $('.raty_notation').each(function() {
                var average = $(this).data('average');
                var elementId = $(this).attr('id');
                ratyNotation(elementId, average);
            });
        }

        function toggleVisibilityAll() {
            var villeList = $('#villes');
            var toggleVisibilityVille = $('#toggleVille');

            var domaineList = $('#domaines');
            var toggleVisibilityDomaine = $('#toggleDomaine');

            var cfpList = $('#cfps');
            var toggleVisibilityCfp = $('#toggleCfp');

            toggleVisibility(villeList, toggleVisibilityVille);
            toggleVisibility(domaineList, toggleVisibilityDomaine);
            toggleVisibility(cfpList, toggleVisibilityCfp);
        }

        function pagination(currentPage, paginationContainer, lastPage) {
            const ellipsis = '...';
            if (lastPage > 10) {
                let start = [];
                let end = [];
                let middle = [];

                end = Array.from({
                    length: 5
                }, (_, i) => lastPage - 4 + i);

                if (currentPage <= 5) {
                    start = Array.from({
                        length: 5
                    }, (_, i) => i + 1);
                    middle = [];

                    start.forEach(i => {
                        let activeClass = (i === currentPage) ? 'border-sky-500 text-sky-500' :
                            'hover:border-sky-500 hover:text-sky-500';
                        paginationContainer.append(`
                            <li class="px-2">
                                <button class="w-9 h-9 rounded-md border ${activeClass} page-link" data-page="${i}">
                                    ${i}
                                </button>
                            </li>
                        `);
                    });

                    paginationContainer.append(`
                        <li class="px-2">
                            <span class="text-gray-500">${ellipsis}</span>
                        </li>
                    `);

                } else if (currentPage > 5 && currentPage < lastPage - 4) {
                    start = Array.from({
                        length: 5
                    }, (_, i) => currentPage - 4 + i);

                    start.forEach(i => {
                        let activeClass = (i === currentPage) ? 'border-sky-500 text-sky-500' :
                            'hover:border-sky-500 hover:text-sky-500';
                        paginationContainer.append(`
                            <li class="px-2">
                                <button class="w-9 h-9 rounded-md border ${activeClass} page-link" data-page="${i}">
                                    ${i}
                                </button>
                            </li>
                        `);
                    });

                    paginationContainer.append(`
                        <li class="px-2">
                            <span class="text-gray-500">${ellipsis}</span>
                        </li>
                    `);

                } else if (currentPage >= lastPage - 4) {
                    start = Array.from({
                        length: 5
                    }, (_, i) => i + 1);
                    middle = [];

                    start.forEach(i => {
                        let activeClass = (i === currentPage) ? 'border-sky-500 text-sky-500' :
                            'hover:border-sky-500 hover:text-sky-500';
                        paginationContainer.append(`
                            <li class="px-2">
                                <button class="w-9 h-9 rounded-md border ${activeClass} page-link" data-page="${i}">
                                    ${i}
                                </button>
                            </li>
                        `);
                    });

                    paginationContainer.append(`
                        <li class="px-2">
                            <span class="text-gray-500">${ellipsis}</span>
                        </li>
                    `);
                }

                end.forEach(i => {
                    let activeClass = (i === currentPage) ? 'border-sky-500 text-sky-500' :
                        'hover:border-sky-500 hover:text-sky-500';
                    paginationContainer.append(`
                        <li class="px-2">
                            <button class="w-9 h-9 rounded-md border ${activeClass} page-link" data-page="${i}">
                                ${i}
                            </button>
                        </li>
                    `);
                });

            } else {
                for (let i = 1; i <= lastPage; i++) {
                    let activeClass = (i === currentPage) ? 'border-sky-500 text-sky-500' :
                        'hover:border-sky-500 hover:text-sky-500';
                    paginationContainer.append(`
                        <li class="px-2">
                            <button class="w-9 h-9 rounded-md border ${activeClass} page-link" data-page="${i}">
                                ${i}
                            </button>
                        </li>
                    `);
                }
            }
        }

        let valueSearchValue = $('#search').val();
        let selectedDomaineIdsValue = [];
        let selectedVilleIdsValue = [];
        let selectedCfpIdsValue = [];
        let selectedDuringIdsValue = [];
    </script>
@endsection
