@extends('layouts.masterAdmin')

@section('content')
    <div class="w-full h-full container mt-4">
        @if (count($autres) <= 0)
            <div>
                Aucun projet à {{ $paiement }}
            </div>
        @else
            {{-- Début tableau --}}
            <div class="w-full p-4 rounded-3xl bg-white">
                <table class="table table-hover table-striped caption-top">
                    <caption class="text-2xl font-medium text-slate-800">
                        Liste des projets à <span
                            class="uppercase text-2xl font-medium text-slate-800">{{ $paiement }}</span>
                    </caption>
                    <thead>
                        <tr>
                            <th id="sort-iteration" scope="col">
                                #
                            </th>
                            <th id="sort-reference" scope="col">
                                Reference
                            </th>
                            <th id="sort-title" scope="col">
                                Titre
                            </th>
                            <th id="sort-projet-name" scope="col">
                                Nom du projet
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($autres as $autre)
                            <tr>
                                <td>
                                    <div>
                                        {{ $loop->iteration }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $autre->project_reference }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $autre->project_title }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $autre->project_status }}
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
