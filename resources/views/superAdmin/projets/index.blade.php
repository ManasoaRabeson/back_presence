@extends('layouts.masterAdmin')

@section('content')
    <div class="p-6 bg-white h-full">
        <div class="w-1/3 inline-flex items-center gap-2 px-3">
            <i class="fa-solid fa-tarp 2xl:text-3xl md:text-2xl text-gray-600"></i>
            <label class="2xl:text-2xl md:text-xl text-gray-500 font-semibold">Liste de tous les projets</label>
        </div>

        @if (count($projects) <= 0)
            <div>
                Aucun projet
            </div>
        @else
            <div class="w-full h-[64px] flex justify-center items-center">
                <button id="export-excel" class="mt-4 px-4 py-2 bg-green-500 text-white rounded">
                    Exporter en Excel
                </button>
                <input type="text" id="searchInput" class="form-control mt-4" placeholder="Rechercher..."
                    style="width: 400px; height: 40px; margin-left:30px;">
            </div>


            <div id="messageModal"
                style="display:none; position:fixed; top:20%; left:50%; transform:translate(-50%, -50%); background-color:rgba(0, 128, 0, 0.9); color:white; padding:20px 40px; border-radius:8px; z-index:1000; font-size:16px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);">
                <span id="modalMessage"></span>
            </div>

            <div id="confirmDeleteModal"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden z-50">
                <div class="bg-white rounded-lg p-6">
                    <h2 class="text-lg font-bold mb-4">Confirmer la suppression</h2>
                    <p>Êtes-vous sûr de vouloir supprimer ce projet ?</p>
                    <div class="mt-4 flex justify-end">
                        <button id="cancelDelete"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">Annuler</button>
                        @csrf
                        <button id="confirmDelete"
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Supprimer</button>
                    </div>
                </div>
            </div>

            <div class="table-responsive mt-10">

                <table id="projectTable" class="table table-striped table-bordered" style="margin-top: 40px;width: 2500px;">
                    <thead class="table-light">
                        <tr>
                            <th id="sort-iteration" class="text-center" onclick="sortTable(0)"># <i
                                    class="fa-solid fa-arrows-up-down text-xs ml-2 text-gray-500 text-rigth"></i></th>
                            <th id="sort-reference" class="text-center" onclick="sortTable(1)">Reference <i
                                    class="fa-solid fa-arrows-up-down text-xs ml-2 text-gray-500 text-rigth"></i></th>
                            <th id="sort-title" class="text-center" onclick="sortTable(2)">Titre <i
                                    class="fa-solid fa-arrows-up-down text-xs ml-2 text-gray-500 text-rigth"></i></th>
                            <th id="sort-projet-name" class="text-center" onclick="sortTable(3)">Nom du projet <i
                                    class="fa-solid fa-arrows-up-down text-xs ml-2 text-gray-500 text-rigth"></i></th>
                            <th id="sort-domaine" class="text-center" onclick="sortTable(4)">Domaine <i
                                    class="fa-solid fa-arrows-up-down text-xs ml-2 text-gray-500 text-rigth"></i></th>
                            <th id="sort-type-projet" class="text-center" onclick="sortTable(5)">Type de projet <i
                                    class="fa-solid fa-arrows-up-down text-xs ml-2 text-gray-500 text-rigth"></i></th>
                            <th id="sort-date-debut" class="text-center" onclick="sortTable(6)">Date début <i
                                    class="fa-solid fa-arrows-up-down text-xs ml-2 text-gray-500 text-rigth"></i></th>
                            <th id="sort-date-fin" class="text-center" onclick="sortTable(7)">Date de fin <i
                                    class="fa-solid fa-arrows-up-down text-xs ml-2 text-gray-500 text-rigth"></i></th>
                            <th id="sort-entreprise" class="text-center" onclick="sortTable(8)">Entreprise <i
                                    class="fa-solid fa-arrows-up-down text-xs ml-2 text-gray-500 text-rigth"></i></th>
                            <th id="sort-entreprise" class="text-center" onclick="sortTable(9)">CFP <i
                                    class="fa-solid fa-arrows-up-down text-xs ml-2 text-gray-500 text-rigth"></i></th>
                            <th id="sort-entreprise" class="text-center" onclick="sortTable(10)">Sous-traitant <i
                                    class="fa-solid fa-arrows-up-down text-xs ml-2 text-gray-500 text-rigth"></i></th>
                            <th id="sort-module" class="text-center" onclick="sortTable(11)">Module <i
                                    class="fa-solid fa-arrows-up-down text-xs ml-2 text-gray-500 text-rigth"></i></th>
                            <th id="sort-ville" class="text-center" onclick="sortTable(12)">Ville <i
                                    class="fa-solid fa-arrows-up-down text-xs ml-2 text-gray-500 text-rigth"></i></th>
                            <th id="sort-modalite" class="text-center" onclick="sortTable(13)">Modalité <i
                                    class="fa-solid fa-arrows-up-down text-xs ml-2 text-gray-500 text-rigth"></i></th>
                            <th id="sort-salle" class="text-center" onclick="sortTable(14)">Salle <i
                                    class="fa-solid fa-arrows-up-down text-xs ml-2 text-gray-500 text-rigth"></i></th>
                            <th id="sort-description" class="text-center" onclick="sortTable(15)">Description du projet <i
                                    class="fa-solid fa-arrows-up-down text-xs ml-2 text-gray-500 text-rigth"></i></th>
                            <th id="sort-description" class="text-center" onclick="sortTable(16)">Fonds <i
                                    class="fa-solid fa-arrows-up-down text-xs ml-2 text-gray-500 text-rigth"></i></th>
                            <th id="sort-description" class="text-center" onclick="sortTable(17)">Prix HT <i
                                    class="fa-solid fa-arrows-up-down text-xs ml-2 text-gray-500 text-rigth"></i></th>
                            <th id="sort-status" class="text-center" onclick="sortTable(18)">Status du projet <i
                                    class="fa-solid fa-arrows-up-down text-xs ml-2 text-gray-500 text-rigth"></i></th>
                            <th id="sort-status" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="projectTableBody">
                        @foreach ($projects as $project)
                            <tr class="align-middle">
                                <td class="text-left">{{ $loop->iteration }}</td>
                                <td class="text-left">{{ $project->project_reference }}</td>
                                <td class="text-left">{{ $project->project_title }}</td>
                                <td class="text-left">{{ $project->project_name }}</td>
                                <td class="text-left" style="width: 200px">{{ $project->domaine_name }}</td>
                                <td class="text-left">
                                    <a href="{{ route('superAdmins.projets.type', ['idTypeProjet' => $project->idTypeprojet]) }}"
                                        class="text-decoration-none">
                                        {{ $project->project_type }}
                                    </a>
                                </td>
                                <td class="text-left">{{ $project->dateDebut }}</td>
                                <td class="text-left">{{ $project->dateFin }}</td>
                                <td class="text-left" style="width: 150px">{{ $project->etp_name }}</td>
                                <td class="text-left">{{ $project->customerName }}</td>
                                <td class="text-left">{{ $project->sous_traitant }}</td>
                                <td class="text-left" style="width: 200px">{{ $project->module_name }}</td>
                                <td class="text-left">{{ $project->ville }}</td>
                                <td class="text-left">{{ $project->modalite }}</td>
                                <td class="text-left" style="width: 100px">{{ $project->salle_name }}</td>
                                <td class="text-left" style="width: 400px">{{ $project->project_description }}</td>
                                <td class="text-left" style="width: 100px">{{ $project->paiement }}</td>
                                <td class="text-right" style="width: 100px">{{ $project->total_ht }} Ar</td>
                                <td
                                    class="text-left 
                  @if ($project->project_status === 'En cours') text-warning 
                  @elseif ($project->project_status === 'Terminé') text-success 
                  @elseif ($project->project_status === 'En attente') text-secondary 
                  @else text-danger @endif">
                                    {{ $project->project_status }}
                                </td>
                                <td class="text-center">
                                    <button type="submit" class="text-red-500 hover:text-red-700 deleteDomaine"
                                        data-id="{{ $project->idProjet }}">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection

@push('custom_style')
    <style>
        /* Stylisation du message d'information */
        .tooltip {
            display: none;
            position: absolute;
            z-index: 50;
            background-color: rgba(0, 0, 0, 0.8);
            color: #fff;
            padding: 0.5rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
        }
    </style>
@endpush

@section('script')
    {{-- <script src="{{ asset('js/FileSaver.min.js') }}"></script>
  <script src="{{ asset('js/xlsx.full.min.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log("projectTable", projectTable);

            document.getElementById('export-excel').addEventListener('click', function() {
                const table = document.querySelector('table');
                if (!table) {
                    console.error("Le tableau n'existe pas");
                    return;
                }

                const wb = XLSX.utils.table_to_book(table, {
                    sheet: "Tous les projets"
                });
                const wbout = XLSX.write(wb, {
                    bookType: 'xlsx',
                    type: 'binary'
                });

                function s2ab(s) {
                    const buf = new ArrayBuffer(s.length);
                    const view = new Uint8Array(buf);
                    for (let i = 0; i < s.length; i++) {
                        view[i] = s.charCodeAt(i) & 0xFF;
                    }
                    return buf;
                }

                saveAs(new Blob([s2ab(wbout)], {
                    type: "application/octet-stream"
                }), 'projects.xlsx');
            });
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let input = this.value.toLowerCase();
            let rows = document.querySelectorAll('#projectTable tbody tr');

            rows.forEach(row => {
                let match = false;
                row.querySelectorAll('td').forEach(cell => {
                    if (cell.textContent.toLowerCase().includes(input)) {
                        match = true;
                    }
                });
                row.style.display = match ? '' : 'none';
            });
        });

        // Fonction pour trier le tableau
        function sortTable(columnIndex) {
            let tbody = document.getElementById("projectTableBody");
            let rows = Array.from(tbody.rows);
            let ascending = true;

            if (tbody.getAttribute('data-sort-column') == columnIndex) {
                ascending = tbody.getAttribute('data-sort-order') == 'asc' ? false : true;
            }

            rows.sort((rowA, rowB) => {
                let cellA = rowA.cells[columnIndex].textContent.trim();
                let cellB = rowB.cells[columnIndex].textContent.trim();

                if (columnIndex === 0) {
                    cellA = parseInt(cellA);
                    cellB = parseInt(cellB);
                }

                if (columnIndex === 17) {
                    const parsePrice = (priceString) => {
                        if (priceString === "- - - Ar") {
                            return 0;
                        }

                        const numberString = priceString.replace(/\s/g, '').replace('Ar', '');
                        return parseInt(numberString, 10);
                    };

                    cellA = parsePrice(cellA);
                    cellB = parsePrice(cellB);
                }

                if (ascending) {
                    return cellA > cellB ? 1 : cellA < cellB ? -1 : 0;
                } else {
                    return cellA < cellB ? 1 : cellA > cellB ? -1 : 0;
                }
            });

            rows.forEach(row => tbody.appendChild(row));

            tbody.setAttribute('data-sort-column', columnIndex);
            tbody.setAttribute('data-sort-order', ascending ? 'asc' : 'desc');
        }

        document.addEventListener('DOMContentLoaded', () => {

            $('.deleteDomaine').on('click', function(event) {
                event.preventDefault();

                var domaineId = $(this).data('id');
                const csrfToken = $('input[name="_token"]').val();

                $('#confirmDeleteModal').removeClass('hidden');
                $('#confirmDelete').off('click').on('click', function() {
                    console.log("Projet à supprimer : " + domaineId);
                    console.log(csrfToken);
                    $.ajax({
                        type: 'POST',
                        url: '/superAdmins/domaineProject/' + domaineId,
                        data: {
                            _token: csrfToken,
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            showModalMessage(response.message, true);
                            console.log(response.message);
                            setTimeout(function() {
                                window.location.href =
                                '/superAdmins/projetlist';
                            }, 2000);
                        },
                        error: function(error) {
                            console.log('Error: ', error);
                            showModalMessage(error.responseJSON.message, false);
                        }
                    });
                    $('#confirmDeleteModal').addClass('hidden');
                });
            });

            $('#cancelDelete').on('click', function() {
                $('#confirmDeleteModal').addClass('hidden');
            });

            // Fonction pour afficher le message succés ou erreur 

            function showModalMessage(message, isSuccess) {
                var modal = $('#messageModal');
                var modalMessage = $('#modalMessage');

                modalMessage.text(message);
                if (isSuccess) {
                    modal.css('background-color', '#5cb85c');
                } else {
                    modal.css('background-color', '#e74c3c');
                }
                modal.fadeIn();
                setTimeout(function() {
                    modal.fadeOut();
                }, 2000);
            }
        });
    </script>
@endsection
