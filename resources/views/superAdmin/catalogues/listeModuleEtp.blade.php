@extends('layouts.masterAdmin')

@section('content')
    <div class="w-full h-full container mt-4">
        @if (count($modules) <= 0)
            <div>
                Aucun module d'entreprise
            </div>
        @else
            {{-- Début tableau --}}
            <div class="w-full p-4 rounded-3xl bg-white">

                <div class="mb-4">
                    <input type="text" id="searchInput" placeholder="Rechercher une module d'entreprise..." class="w-full p-2 border rounded">
                </div>
                
                @if(session('success'))
                    <div class="alert alert-success" id="alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger" id="alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                <table class="table table-hover caption-top">
                    <caption class="text-2xl font-medium text-slate-800">
                        Liste des modules des entreprises
                    </caption>
                    <thead>
                        <tr>
                            <th id="sort-iteration" scope="col">
                                #
                            </th>
                            <th id="sort-moduleName" scope="col">
                                Nom du module
                            </th>
                            <th id="sort-description" scope="col">
                                Description du module
                            </th>
                            <th id="sort-nomDomaine" scope="col">
                                Domaine de formation
                            </th>
                            <th id="sort-customerName" scope="col">
                                Entreprise
                            </th>
                            <th id="sort-customerName" scope="col">
                                Nombre de projets liés
                            </th>
                            <th id="sort-customerName" scope="col">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody id="moduleTableBody">
                        @foreach ($modules as $module)
                            <tr>
                                <td>
                                    <div>
                                        {{ $loop->iteration }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $module->moduleName }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $module->description }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $module->nomDomaine }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $module->customerName }}
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div style="padding-right: 20px;">
                                        {{ $module->nombreProjets }}
                                    </div>
                                </td>
                                <td style="width: 100px;">
                                    <div>
                                        <a href="/superAdmins/etpDetail/modules/{{ $module->idModule }}"><i class="fa-solid fa-eye" title="Voir détail" style="margin-right: 15px"></i></a>
                                        <a href="/superAdmins/modules/entreprise/delete/{{ $module->idModule }}"><i class="fa-solid fa-trash-can" title="Supprimer définitivement"></i></a>
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
    {{-- Ajout du script de tri --}}
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
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase(); 
            const rows = document.querySelectorAll('#moduleTableBody tr'); 
            
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' '); 
                
                if (rowText.includes(searchValue)) {
                    row.style.display = ''; 
                } else {
                    row.style.display = 'none'; 
                }
            });
        });

        // Fonction pour masquer l'alerte après un délai
        function hideAlertAfterDelay(alertId, delay) {
            setTimeout(function() {
                const alert = document.getElementById(alertId);
                if (alert) {
                    alert.style.display = 'none'; // Masquer l'alerte
                }
            }, delay);
        }

        @if(session('success'))
            hideAlertAfterDelay('alert-success', 3000); 
        @endif

        @if(session('error'))
            hideAlertAfterDelay('alert-error', 3000); 
        @endif
    </script>
@endsection
