@extends('layouts.masterAdmin')

@section('content')
    <div class="container mx-auto p-6 bg-white h-full">

        <h2 class="text-2xl font-bold mb-6 text-center">Liste des domaines de formations</h2>

        <div class="w-full mt-6">
            <!-- Bouton Ajouter -->
            <div class="mb-4 flex justify-between items-center">
                <input type="text" id="searchInput" placeholder="Rechercher un formateur ..."
                    class="w-full p-2 border rounded">
                <button id="openDrawerBtn" class="px-4 py-2 bg-[#a462a4] text-white rounded-lg hover:bg-[#a462a4]">
                    Ajouter
                </button>
            </div>

            <div id="messageModal"
                style="display:none; position:fixed; top:20%; left:50%; transform:translate(-50%, -50%); background-color:rgba(0, 128, 0, 0.9); color:white; padding:20px 40px; border-radius:8px; z-index:1000; font-size:16px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);">
                <span id="modalMessage"></span>
            </div>

            <div id="confirmDeleteModal"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden z-50">
                <div class="bg-white rounded-lg p-6">
                    <h2 class="text-lg font-bold mb-4">Confirmer la suppression</h2>
                    <p>Êtes-vous sûr de vouloir supprimer ce domaine ?</p>
                    <div class="mt-4 flex justify-end">
                        <button id="cancelDelete"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">Annuler</button>
                        @csrf
                        <button id="confirmDelete"
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Supprimer</button>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto mb-20">
                <table class="min-w-full bg-white border border-gray-300 rounded-lg mb-20">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="py-3 px-8 pl-20 text-base text-gray-700" onclick="sortTable(0)">
                                #
                                <i class="fa-solid fa-arrows-up-down text-xs ml-2 text-gray-500 text-rigth"></i>
                            </th>
                            <th class="py-3 px-6 text-left text-base" onclick="sortTable(1)">
                                Nom du Domaine
                                <i class="fa-solid fa-arrows-up-down text-xs ml-2 text-gray-500 text-rigth"></i>
                            </th>
                            <th class="py-3 px-6 text-left text-base text-gray-700" onclick="sortTable(2)">
                                Nombre de Modules
                                <i class="fa-solid fa-arrows-up-down text-xs ml-2 text-gray-500 text-rigth"></i>
                            </th>
                            <th class="py-3 px-6 text-left text-base text-gray-700">Action</th>
                        </tr>
                    </thead>
                    <tbody id="domainTableBody">
                        @foreach ($domaines as $d)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-6 pl-20">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="py-3 px-6">
                                    <a href="/formation/category/{{ $d->idDomaine }}" class="text-gray-700">
                                        {{ $d->nomDomaine }}
                                    </a>
                                </td>
                                <td class="py-3 px-6 pl-20">
                                    {{ $d->nbrModules }}
                                </td>
                                <td class="py-3 px-6">
                                    <button type="submit" class="text-red-500 hover:text-red-700 deleteDomaine"
                                        data-id="{{ $d->idDomaine }}">
                                        <i class="fa-solid fa-trash-can"></i> Supprimer
                                    </button>
                                    <button id="openDrawerBtnModif_{{ $d->idDomaine }}" data-id="{{ $d->idDomaine }}"
                                        data-nom="{{ $d->nomDomaine }}" class="text-green-500 hover:text-green-700 ml-4">
                                        <i class="fa-solid fa-pen"></i> Modifier
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

        <!-- Drawer pour ajouter un domaine -->
        <div id="drawer"
            class="fixed top-0 right-0 w-[400px] h-full bg-white shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out z-50">
            <div class="bg-[#f3f4f6] text-[#7a808d] p-4 flex justify-between items-center">
                <span class="text-lg">Ajouter un domaine de formation</span>
                <button id="closeDrawerBtn" class="text-white">
                    <i class="fas fa-times text-[#7a808d]"></i>
                </button>
            </div>
            <div class="p-4">
                <form id="addDomainForm" class="space-y-4">
                    @csrf
                    <div>
                        <label for="domaineName" class="block text-gray-700 font-medium mb-1">Nom du Domaine</label>
                        <input type="text" id="domaineName" name="domaineName" placeholder="Saisir le nom du domaine..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#a462a4]"
                            required>
                    </div>
                    <button type="submit" class="w-full bg-[#a462a4] text-white py-2 rounded-md hover:bg-[#a462a4]">
                        Enregistrer
                    </button>
                </form>
            </div>
        </div>

        <!-- Drawer pour modifier un domaine -->
        <div id="drawerModif"
            class="fixed top-0 right-0 w-[400px] h-full bg-white shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out z-50">
            <div class="bg-[#f3f4f6] text-[#7a808d] p-4 flex justify-between items-center">
                <span class="text-lg">Modifier un domaine de formation</span>
                <button id="closeDrawerBtnModif" class="text-white">
                    <i class="fas fa-times text-[#7a808d]"></i>
                </button>
            </div>
            <div class="p-4">
                <form id="editDomainForm" class="space-y-4">
                    @csrf
                    <div>
                        <label for="editDomaineName" class="block text-gray-700 font-medium mb-1">Nom du Domaine</label>
                        <input type="text" id="editDomaineName" name="editDomaineName"
                            placeholder="Saisir le nom du domaine à modifier..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#4CAF50]"
                            required>
                    </div>
                    <input type="hidden" id="editDomaineId" name="editDomaineId">

                    <button type="submit" class="w-full bg-[#4CAF50] text-white py-2 rounded-md hover:bg-[#45a049]">
                        Sauvegarder les modifications
                    </button>
                </form>
            </div>
        </div>


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            document.getElementById('searchInput').addEventListener('keyup', function() {
                const searchValue = this.value.toLowerCase();
                const rows = document.querySelectorAll('#domainTableBody tr');

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

            document.addEventListener('DOMContentLoaded', () => {

                $('.deleteDomaine').on('click', function(event) {
                    event.preventDefault();

                    var domaineId = $(this).data('id');
                    const csrfToken = $('input[name="_token"]').val();

                    $('#confirmDeleteModal').removeClass('hidden');

                    $('#confirmDelete').off('click').on('click', function() {
                        console.log("Domaine à supprimer : " + domaineId);
                        $.ajax({
                            type: 'DELETE',
                            url: '/superAdmins/domaineDelete/' + domaineId,
                            data: {
                                _token: csrfToken
                            },
                            success: function(response) {
                                showModalMessage(response.message, true);
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            },
                            error: function(error) {
                                console.log('Error: ', error);
                                showModalMessage(error.responseJSON.message, false);
                            }
                        })
                        $('#confirmDeleteModal').addClass('hidden');
                    });
                });

                $('#cancelDelete').on('click', function() {
                    $('#confirmDeleteModal').addClass('hidden');
                });

                // Gestion du drawer
                const openDrawerBtn = document.getElementById('openDrawerBtn');
                const closeDrawerBtn = document.getElementById('closeDrawerBtn');
                const drawer = document.getElementById('drawer');

                openDrawerBtn.addEventListener('click', () => {
                    drawer.classList.remove('translate-x-full');
                });

                closeDrawerBtn.addEventListener('click', () => {
                    drawer.classList.add('translate-x-full');
                });

                // Soumettre le formulaire avec AJAX
                document.getElementById('addDomainForm').addEventListener('submit', function(event) {
                    event.preventDefault();

                    const csrfToken = $('input[name="_token"]').val();
                    const domaineName = document.getElementById('domaineName').value;

                    console.log('Domaine Name:', domaineName, "Token:", csrfToken);

                    $.ajax({
                        type: "POST",
                        url: "/superAdmins/domaineInsert",
                        data: {
                            _token: csrfToken,
                            domaineName: domaineName
                        },
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success: function(response) {
                            console.log(response.message);
                            console.log('Message:', response.message);
                            console.log('Domaine Name:', response.domaineName);
                            showModalMessage(response.message, true);
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        },
                        error: function(error) {
                            console.log('Status Code:', error.status);
                            console.log('Error Details:', error.responseText);
                            showModalMessage("Une erreur s'est produite: " + error.responseText,
                                false);
                        }
                    });

                });

                // Modifier le formulaire avec AJAX
                document.getElementById('editDomainForm').addEventListener('submit', function(event) {
                    event.preventDefault();

                    const csrfToken = $('input[name="_token"]').val();
                    const domaineId = document.getElementById('editDomaineId').value;
                    const domaineName = document.getElementById('editDomaineName').value;

                    console.log('Domaine ID:', domaineId, 'Domaine Name:', domaineName, 'Token:', csrfToken);

                    $.ajax({
                        type: "GET",
                        url: "/superAdmins/domaineUpdate",
                        data: {
                            _token: csrfToken,
                            idDomaine: domaineId,
                            domaineName: domaineName
                        },
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success: function(response) {
                            console.log(response.message);
                            showModalMessage(response.message, true);
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        },
                        error: function(error) {
                            console.log('Status Code:', error.status);
                            console.log('Error Details:', error.responseText);
                            showModalMessage("Une erreur s'est produite : " + error.responseText,
                                false);
                        }
                    });
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

            document.getElementById('searchInput').addEventListener('keyup', function() {
                const searchValue = this.value.toLowerCase();
                const rows = document.querySelectorAll('#domainTable tr');

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

            // Drawer pour la modification
            @foreach ($domaines as $d)
                document.getElementById('openDrawerBtnModif_{{ $d->idDomaine }}').addEventListener('click', function() {
                    var idDomaine = this.getAttribute('data-id');
                    var nomDomaine = this.getAttribute('data-nom');

                    document.getElementById('editDomaineId').value = idDomaine;
                    document.getElementById('editDomaineName').value = nomDomaine;

                    document.getElementById('drawerModif').classList.remove('translate-x-full');
                    document.getElementById('drawerModif').classList.add('translate-x-0');
                });
            @endforeach

            // Fermer le drawer de modification
            document.getElementById('closeDrawerBtnModif').addEventListener('click', function() {
                document.getElementById('drawerModif').classList.remove('translate-x-0');
                document.getElementById('drawerModif').classList.add('translate-x-full');
            });

            // Fonction pour trier le tableau
            function sortTable(columnIndex) {
                let tbody = document.getElementById("domainTableBody");
                let rows = Array.from(tbody.rows);
                let ascending = true;

                if (tbody.getAttribute('data-sort-column') == columnIndex) {
                    ascending = tbody.getAttribute('data-sort-order') == 'asc' ? false : true;
                }

                rows.sort((rowA, rowB) => {
                    let cellA = rowA.cells[columnIndex].textContent.trim();
                    let cellB = rowB.cells[columnIndex].textContent.trim();

                    if (columnIndex === 2 || columnIndex === 0) {
                        cellA = parseInt(cellA);
                        cellB = parseInt(cellB);
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
        </script>

    </div>
@endsection
