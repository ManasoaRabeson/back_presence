<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forma-Fusion</title>
    <script src="{{ asset('js/jquery-3.6.1.min.js') }}"></script>
    <script src="{{ asset('js/tailwind.js') }}"></script>
    <script src="{{ asset('js/jquery.raty.js') }}"></script>
    <script src="{{ asset('js/global_js.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/jquery.raty.css') }}">
    <link rel="stylesheet" href="{{ asset('css/daisyUI.min.css') }}">

    <link rel="stylesheet" href="{{ asset('fonts/fontAwesome/css/all.min.css') }}">
</head>

<body>
    <div class="w-screen h-screen h-full flex flex-col bg-slate-100">
        <div class="flex flex-col h-full space-y-20">
            @include('layouts.navbars.landing')
            <div class="py-6">
                <div class="w-full h-full max-w-screen-xl mx-auto" id="search_results">
                    <div class="grid grid-cols-12 mx-6">
                        <div class="grid col-span-12 lg:col-span-3 w-full grid-cols-subgrid">
                            <ul class="space-y-4 h-full">
                                <li class="">
                                    <div
                                        class="flex flex-row items-center p-2 text-gray-400  bg-white rounded-md border-[1px] hover:border-gray-500 focus:border-gray-400 cursor-pointer duration-200">
                                        <i class="fa-solid fa-magnifying-glass" class="w-5 h-5 text-gray-400"></i>
                                        <input class="ml-3 w-full text-gray-400 bg-white outline-none " type="text"
                                            value="" placeholder="Chercher..." id="search">
                                    </div>
                                </li>
                                <li>
                                    <ul class="space-y-4">
                                        <li class="flex justify-between">
                                            <p class="text-xl font-bold">Filtrer par</p>
                                            <p id="reset" class="text-blue-500 hidden lg:block cursor-pointer">
                                                Réinitialiser </p>
                                            <p id="open-modal" class="block lg:hidden"> <i
                                                    class="fa-solid fa-bars-staggered fa-xl text-blue-500"></i></p>
                                        </li>
                                        <li id="filter" class="filter">
                                            <ul class="space-y-4">

                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="grid col-span-12 lg:col-span-9 grid-cols-subgrid h-max">
                            <div class="flex col-span-9 pl-1 lg:pl-6 mb-4 space-x-6 items-center h-max">
                                <div class="grid grid-cols-12">
                                    <div class="col-span-2 grid-cols-subgrid text-xl lg:text-base" id="filter_selected">

                                    </div>

                                    <div class="col-span-10 ml-2 grid-cols-subgrid">
                                        <div class="flex flex-wrap w-full gap-2" id="selected-items">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="grid col-span-12 grid-cols-1 lg:col-span-9 lg:grid-cols-2 xl:grid-cols-3 h-max gap-10 xl:pl-6 h-full mx-auto lg:mx-4"
                                id="project_list">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <span id="show-modal"></span>
        <div>
            @include('layouts.homeFooter')
        </div>
    </div>
</body>
<script>
    $(document).ready(function() {

        $('.raty_notation').each(function() {
            var average = $(this).data('average');
            var elementId = $(this).attr('id');
            ratyNotation(elementId, average);
        });

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
        });

        function reset() {
            $.ajax({
                type: "GET",
                url: "{{ route('searchJsonOnlyKey.formation') }}",
                data: {
                    course: ''
                },
                success: function(result) {
                    let filter_selected = $(
                        '#filter_selected');
                    filter_selected.html('');
                    filter_selected.append(
                        `${result.project_count} cours trouvé(s)`
                    );


                    if (result.projects.length >
                        0) {
                        let project_list = $('#project_list');
                        project_list.html('');
                        project_list.append(result.projectHtml);

                        let filter = $('.filter');
                        filter.html('');
                        filter.append(result.filterHtml);

                        $(document).ready(
                            function() {
                                function updateSelectedItems(
                                    itemType,
                                    itemName,
                                    checkbox) {
                                    var selectedContainer = $('#selected-items');

                                    var existingItem = selectedContainer.find('.selected-' +
                                        itemType + '[data-name="' + itemName + '"]');

                                    if (existingItem.length === 0) {
                                        selectedContainer
                                            .append(
                                                '<div class="rounded rounded-xl border-2 bg-white cursor-pointer px-2 py-1 selected-' +
                                                itemType + '" data-name="' + itemName +
                                                '" data-checkbox-id="' + checkbox.attr('id') +
                                                '" data-checkbox-type="' + itemType + '">' +
                                                '<span class="mr-2">' + itemName + '</span>' +
                                                '<span><i class="fa-solid fa-xmark remove-item"></i></span>' +
                                                '</div>'
                                            );
                                    } else {
                                        existingItem.remove();
                                    }
                                }

                                function handleCheckboxChange(
                                    className,
                                    idArray,
                                    type) {
                                    $(className)
                                        .on('change',
                                            function() {
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

                                                processSelectedIds();
                                            });
                                }

                                let domaineIds = [];
                                let villeIds = [];
                                let cfpIds = [];
                                let duringIds = [];

                                handleCheckboxChange('.domaine-checkbox', domaineIds,
                                    'domaine');
                                handleCheckboxChange('.ville-checkbox', villeIds, 'ville');
                                handleCheckboxChange('.cfp-checkbox', cfpIds, 'cfp');
                                handleCheckboxChange('.during-checkbox', duringIds, 'during');

                                function getSelectedIds(idsArray) {
                                    return idsArray.join(',');
                                }

                                function processSelectedIds() {
                                    let selectedDomaineIds = getSelectedIds(domaineIds);
                                    let selectedVilleIds = getSelectedIds(villeIds);
                                    let selectedCfpIds = getSelectedIds(cfpIds);
                                    let selectedDuring = getSelectedIds(duringIds);
                                    let valueSearch = $("#search").val();

                                    $.ajax({
                                        type: "GET",
                                        url: "{{ route('filter.course.formation') }}",
                                        data: {
                                            valueSearch: valueSearch,
                                            domaineIds: selectedDomaineIds,
                                            villeIds: selectedVilleIds,
                                            cfpIds: selectedCfpIds,
                                            duringIds: selectedDuring
                                        },
                                        success: function(results) {

                                            if (results.project_count > 0) {
                                                let filter_selected = $(
                                                    '#filter_selected');
                                                filter_selected.html('');
                                                filter_selected.append(
                                                    `${results.project_count} cours trouvé(s)`
                                                );

                                                let project_list = $(
                                                    '#project_list');
                                                project_list.html('');
                                                project_list.append(results
                                                    .projectHtml);
                                            } else {
                                                let project_list = $(
                                                    '#project_list');

                                                project_list.html('');
                                                project_list.append(
                                                    `<p> Aucun resultat</p>`);
                                            }
                                        },
                                        error: function(
                                            erreur
                                        ) {
                                            console
                                                .log(
                                                    erreur
                                                );
                                        }
                                    });

                                }

                                $('#selected-items')
                                    .on('click', '.remove-item', function() {
                                        var selectedDiv = $(this).closest(
                                            '.selected-domaine, .selected-ville, .selected-cfp'
                                        );
                                        var checkboxId = selectedDiv.data('checkbox-id');
                                        var checkboxType = selectedDiv.data(
                                            'checkbox-type');

                                        $('#' + checkboxId).prop('checked', false);

                                        selectedDiv.remove();

                                        switch (checkboxType) {
                                            case 'domaine':
                                                domaineIds.splice(domaineIds.indexOf(
                                                    checkboxId.split('_')[1]), 1);
                                                break;
                                            case 'ville':
                                                villeIds.splice(villeIds.indexOf(checkboxId
                                                    .split('_')[1]), 1);
                                                break;
                                            case 'cfp':
                                                cfpIds.splice(cfpIds.indexOf(checkboxId
                                                    .split('_')[1]), 1);
                                                break;
                                        }

                                        // Call processSelectedIds to update the server or UI
                                        processSelectedIds
                                            ();
                                    });
                            });
                    } else {
                        let project_list = $('#project_list');

                        project_list.html('');
                        project_list.append(``);

                        let filter = $('.filter');
                        filter.html('');
                        filter.append(``);
                    }
                    //
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });

            $('#search').val('');

            $('.selected-domaine, .selected-ville, .selected-cfp, .selected-during').each(function() {
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
                }
            });
        }

        function getProject() {
            $.ajax({
                type: "GET",
                url: "{{ route('searchJson.formation') }}",
                data: {
                    category: 'all',
                    course: '',
                    place: 'all'
                },
                success: function(res) {
                    let filter_selected = $('#filter_selected');
                    filter_selected.html('');
                    filter_selected.append(`${res.project_count} cours trouvé(s)`);

                    if (res.projects.length > 0) {
                        let project_list = $('#project_list');

                        project_list.html('');
                        project_list.append(res.projectHtml);

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

                        $(document).ready(function() {
                            selectItem();
                        });
                    } else {
                        let project_list = $('#project_list');

                        project_list.html('');
                        project_list.append(`<p> Aucun resultat</p>`);
                    }

                    let debounceTimer;

                    $('#search').on('keyup', function() {
                        clearTimeout(debounceTimer);

                        let keySearch = $(this).val();

                        debounceTimer = setTimeout(function() {
                            $.ajax({
                                type: "GET",
                                url: "{{ route('searchJsonOnlyKey.formation') }}",
                                data: {
                                    course: keySearch
                                },
                                success: function(result) {
                                    let filter_selected = $(
                                        '#filter_selected');
                                    filter_selected.html('');
                                    filter_selected.append(
                                        `${result.project_count} cours trouvé(s)`
                                    );

                                    if (result.projects.length > 0) {
                                        let project_list = $(
                                            '#project_list');
                                        project_list.html('');
                                        project_list.append(result
                                            .projectHtml);

                                        let filter = $('.filter');
                                        filter.html('');
                                        filter.append(result
                                            .filterHtml);

                                        $(document).on('click',
                                            '#open-modal',
                                            function() {
                                                html = `<div class='du_modal-box w-full'>
                                                    <ul>
                                                        ${result.filterHtmlMobile}
                                                    </ul>
                                                <div/>`;
                                                openDialog(html,
                                                    'show-modal'
                                                );
                                            });

                                        $(document).ready(
                                            function() {
                                                selectItem();
                                            });
                                    } else {
                                        let project_list = $(
                                            '#project_list');

                                        project_list.html('');
                                        project_list.append(``);

                                        let filter = $('.filter');
                                        filter.html('');
                                        filter.append(``);
                                    }
                                    //
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

                },
                error: function(err) {
                    console.log(err);

                }
            });
        }
        getProject();

        function selectItem() {
            function updateSelectedItems(itemType, itemName, checkbox) {
                var selectedContainer = $('#selected-items');

                var existingItem = selectedContainer.find('.selected-' +
                    itemType + '[data-name="' + itemName + '"]');

                if (existingItem.length === 0) {
                    selectedContainer.append(
                        '<div class="rounded rounded-xl border-2 bg-white cursor-pointer px-2 py-1 selected-' +
                        itemType + '" data-name="' + itemName +
                        '" data-checkbox-id="' + checkbox.attr('id') +
                        '" data-checkbox-type="' + itemType + '">' +
                        '<span class="mr-2">' + itemName + '</span>' +
                        '<span><i class="fa-solid fa-xmark remove-item"></i></span>' +
                        '</div>'
                    );
                } else {
                    existingItem.remove();
                }
            }

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

                    processSelectedIds();
                });
            }

            let domaineIds = [];
            let villeIds = [];
            let cfpIds = [];
            let duringIds = [];

            handleCheckboxChange('.domaine-checkbox', domaineIds,
                'domaine');
            handleCheckboxChange('.ville-checkbox', villeIds, 'ville');
            handleCheckboxChange('.cfp-checkbox', cfpIds, 'cfp');
            handleCheckboxChange('.during-checkbox', duringIds, 'during');

            function getSelectedIds(idsArray) {
                return idsArray.join(',');
            }

            function processSelectedIds() {
                let valueSearch = $('#search').val();
                let selectedDomaineIds = getSelectedIds(domaineIds);
                let selectedVilleIds = getSelectedIds(villeIds);
                let selectedCfpIds = getSelectedIds(cfpIds);
                let selectedDuringIds = getSelectedIds(duringIds);

                $.ajax({
                    type: "GET",
                    url: "{{ route('filter.course.formation') }}",
                    data: {
                        valueSearch: valueSearch,
                        domaineIds: selectedDomaineIds,
                        villeIds: selectedVilleIds,
                        cfpIds: selectedCfpIds,
                        duringIds: selectedDuringIds
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
                    '.selected-domaine, .selected-ville, .selected-cfp, .selected-during');
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
                }

                // Call processSelectedIds to update the server or UI
                processSelectedIds();
            });
        }

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
            var maxVisibleItems = 5;

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

        var villeList = $('#villes');
        var toggleVisibilityVille = $('#toggleVille');

        var domaineList = $('#domaines');
        var toggleVisibilityDomaine = $('#toggleDomaine');

        var cfpList = $('#cfps');
        var toggleVisibilityCfp = $('#toggleCfp');

        toggleVisibility(villeList, toggleVisibilityVille);
        toggleVisibility(domaineList, toggleVisibilityDomaine);
        toggleVisibility(cfpList, toggleVisibilityCfp);

    });
</script>

</html>
