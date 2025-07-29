@extends('layouts.masterAdmin')

@section('content')
    <div class="w-full h-full container mt-4">
        @if (count($referents) <= 0)
            <div>
                Aucun référent de centre de formation professionnelle
            </div>
        @else
            {{-- Début tableau --}}
            <div class="w-full p-4 rounded-3xl bg-white">

                <div class="mb-4">
                    <input type="text" id="searchInput" placeholder="Rechercher un référent..." class="w-full p-2 border rounded">
                </div>

                <!-- Afficher le message de succès -->
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
                    <caption class="text-2xl font-medium text-slate-800">Liste des référents des centres de formation
                    </caption>
                    <thead>
                        <tr>
                            <th id="sort-iteration" scope="col">
                                #
                            </th>
                            <th id="sort-name" scope="col">
                                Nom
                            </th>
                            <th id="sort-firstName" scope="col">
                                Prénom
                            </th>
                            <th id="sort-phone" scope="col">
                                Phone
                            </th>
                            <th id="sort-email" scope="col">
                                Email
                            </th>
                            <th id="sort-user_addr_quartier" scope="col">
                                Centre de formation
                            </th>
                            <th id="sort-user_addr_quartier" scope="col">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody id="moduleTableBody">
                        @foreach ($referents as $referent)
                            <tr>
                                <td>
                                    <div>
                                        {{ $loop->iteration }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $referent->name }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $referent->firstName }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $referent->phone }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $referent->email }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $referent->customerName }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <a href="/superAdmins/referents/cfp/delete/{{ $referent->id }}"><i class="fa-solid fa-trash-can" title="Supprimer en tant que référent"></i></a>
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
