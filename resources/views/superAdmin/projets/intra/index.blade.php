@extends('layouts.masterAdmin')

@section('content')
    <div class="container w-full h-full mt-4">
        @if (count($projetstypes) <= 0)
            <div>
                Aucun projet {{ $type }}
            </div>
        @else
            {{-- Début tableau --}}
            <div class="w-full p-4 bg-white rounded-3xl">
                <table class="table table-hover table-striped caption-top">
                    <caption class="text-2xl font-medium text-slate-800">
                        Liste des projets {{ $type }}
                    </caption>
                    <thead>
                        <tr>
                            <th id="sort-iteration" scope="col">
                                #
                            </th>
                            <th id="sort-reference" scope="col">
                                Référence
                            </th>
                            <th id="sort-title" scope="col">
                                Titre
                            </th>
                            <th id="sort-projet-name" scope="col">
                                Nom du projet
                            </th>
                            <th id="sort-domaine" scope="col">
                                Domaine
                            </th>
                            <th id="sort-date-debut" scope="col">
                                Date début
                            </th>
                            <th id="sort-date-fin" scope="col">
                                Date de fin
                            </th>
                            <th id="sort-module" scope="col">
                                Module
                            </th>
                            <th id="sort-ville" scope="col">
                                Ville
                            </th>
                            <th id="sort-entreprise" scope="col">
                                Entreprise
                            </th>
                            <th id="sort-modalite" scope="col">
                                Modalité
                            </th>
                            <th id="sort-salle" scope="col">
                                Salle
                            </th>
                            <th id="sort-description" scope="col">
                                Description du projet
                            </th>
                            <th id="sort-status" scope="col">
                                Status du projet
                            </th>
                            <th id="sort-status" scope="col">
                                Action
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projetstypes as $projetstype)
                            <tr>
                                <td>
                                    <div>
                                        {{ $loop->iteration }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $projetstype->project_reference }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $projetstype->project_title }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $projetstype->project_name }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $projetstype->domaine_name }}
                                    </div>
                                </td>

                                <td>
                                    <div>
                                        {{ $projetstype->dateDebut }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $projetstype->dateFin }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $projetstype->module_name }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $projetstype->ville }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $projetstype->etp_name }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $projetstype->modalite }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $projetstype->salle_name }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $projetstype->project_description }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $projetstype->project_status }}
                                    </div>
                                </td>
                                <td>
                                    <div class="cursor-pointer">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const thElements = document.querySelectorAll('th');

            thElements.forEach(th => {
                const tooltip = th.querySelector('.tooltip');

                th.addEventListener('mouseenter', function() {
                    tooltip.classList.remove('hidden');
                });

                th.addEventListener('mouseleave', function() {
                    tooltip.classList.add('hidden');
                });

                th.addEventListener('click', function() {
                    const sortKey = th.id.replace('sort-', '');
                    const table = document.querySelector('table');
                    const tbody = table.querySelector('tbody');
                    const tableRows = Array.from(tbody.querySelectorAll('tr'));

                    tableRows.sort((a, b) => {
                        const cellA = a.querySelector(`td:nth-child(${th.cellIndex + 1})`)
                            .textContent.trim();
                        const cellB = b.querySelector(`td:nth-child(${th.cellIndex + 1})`)
                            .textContent.trim();

                        if (!isNaN(cellA) && !isNaN(cellB)) {
                            return parseInt(cellA) - parseInt(cellB);
                        } else {
                            return cellA.localeCompare(cellB);
                        }
                    });

                    // Inverser l'ordre si déjà trié en ascendant
                    if (th.classList.contains('asc')) {
                        tableRows.reverse();
                        th.classList.remove('asc');
                        th.classList.add('desc');
                    } else {
                        th.classList.add('asc');
                        th.classList.remove('desc');
                    }

                    // Réorganiser les lignes dans le tableau
                    tableRows.forEach(row => tbody.appendChild(row));
                });
            });
        });
    </script>
@endsection
