@extends('layouts.masterAdmin')

@section('content')
    <div class="w-full h-full container mt-4">
        @if (count($etps) <= 0)
            <div>
                Aucune entreprise
            </div>
        @else
            {{-- Début tableau --}}
            <div class="w-full p-4 rounded-3xl bg-white">

                <div class="mb-4">
                    <input type="text" id="searchInput" placeholder="Rechercher une entreprise..."
                        class="w-full p-2 border rounded">
                </div>


                <!-- Afficher le message de succès -->
                @if (session('success'))
                    <div class="alert alert-success" id="alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger" id="alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                <table class="table table-hover caption-top">
                    <caption class="text-2xl font-medium text-slate-800">Liste des entreprises</caption>
                    <thead>
                        <tr>
                            <th id="sort-iteration" scope="col">
                                #
                            </th>
                            <th id="sort-name" scope="col">
                                Date d'inscription
                            </th>
                            <th id="sort-name" scope="col">
                                Nom
                            </th>
                            <th id="sort-description" scope="col">
                                Description
                            </th>
                            <th id="sort-address" scope="col">
                                Nif
                            </th>
                            <th id="sort-address" scope="col">
                                Stat
                            </th>
                            <th id="sort-address" scope="col">
                                Adresse
                            </th>
                            <th id="sort-phone" scope="col">
                                Phone
                            </th>
                            <th id="sort-email" scope="col">
                                Email
                            </th>
                            <th id="sort-email" scope="col">
                                Apprenants
                            </th>
                            <th id="sort-email" scope="col">
                                Projets
                            </th>
                            <th id="sort-email" scope="col" class="text-right">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody id="moduleTableBody">
                        @foreach ($etps as $index => $etp)
                            <tr>
                                <td>
                                    <div>
                                        {{ $loop->iteration }}
                                    </div>
                                </td>
                                <td>
                                    <div style="width: 150px;">
                                        {{ $etp->created_at ? \Carbon\Carbon::parse($etp->created_at)->format('d F Y à H:i:s') : 'Non défini' }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $etp->customerName }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $etp->description }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $etp->nif ?? 'Non défini' }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $etp->stat ?? 'Non défini' }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $etp->customer_addr_lot }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $etp->customerPhone }}
                                    </div>
                                </td>
                                <td id="sort-email" scope="col">
                                    <div>
                                        {{ $etp->customerEmail }}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-right">
                                        {{ $etp->nbr_apprenants }}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-right">
                                        {{ $etp->nbr_projets }}
                                    </div>
                                </td>
                                <td class="text-right">
                                    @if ($etp->isActive == 1)
                                        <span class="inline-flex items-center gap-1">
                                            <button title="Modifier" class="btn btn-ghost btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#edit_etp{{ $etp->idEtp }}">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <button title="Supprimer" class="btn btn-ghost btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#delete_etp{{ $etp->idEtp }}">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                            <button title="Bloquer" class="btn btn-ghost btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#bloc_etp{{ $etp->idEtp }}">
                                                <i class="fa-solid fa-lock"></i>
                                            </button>
                                        </span>

                                        <!-- Modal Supprimer-->
                                        <div class="modal fade" id="delete_etp{{ $etp->idEtp }}" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmez-vous
                                                            la suppression ?</h1>
                                                        <button class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Si vous confirmez, l'entreprise {{ $etp->customerName }} sera
                                                            supprimé</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-ghost" data-bs-dismiss="modal">NON</button>
                                                        <button onclick="trashEtp({{ $etp->idEtp }})"
                                                            class="btn btn-primary">OUI</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Bloquer-->
                                        <div class="modal fade" id="bloc_etp{{ $etp->idEtp }}" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmez-vous
                                                            cet action?</h1>
                                                        <button class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Si vous confirmez, l'entreprise {{ $etp->customerName }} sera
                                                            bloqué</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-ghost" data-bs-dismiss="modal">NON</button>
                                                        <button onclick="blockEtp({{ $etp->idEtp }})"
                                                            class="btn btn-primary">OUI</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Modifier-->
                                        <div class="modal fade" id="edit_etp{{ $etp->idEtp }}" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('customers.update', ['id' => $etp->idEtp]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Modifier les
                                                                informations</h5>
                                                            <button class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="flex form-group">
                                                                <p for="nif" class="mr-4 w-14 mt-2">NIF :</p>
                                                                <input type="text" name="nif" id="nif"
                                                                    class="form-control" value="{{ $etp->nif }}"
                                                                    required>
                                                            </div>
                                                            <div class="flex form-group mt-3">
                                                                <label for="stat" class="mr-4 w-14 mt-2">STAT
                                                                    :</label>
                                                                <input type="text" name="stat" id="stat"
                                                                    class="form-control" value="{{ $etp->stat }}"
                                                                    required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <p class="btn btn-primary" data-bs-dismiss="modal">Annuler</p>
                                                            <button type="submit"
                                                                class="border borer-1 border-slate-700 px-4 py-3 rounded-lg hover:bg-slate-700 hover:text-white">Enregistrer</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($etp->isActive == 0)
                                        <a href="#" onclick="unblockEtp({{ $etp->idEtp }})"
                                            class="btn btn-error btn-outline btn-sm">Débloquer</a>
                                    @endif
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
    </script>
    {{-- Ajout du script de tri --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const thElements = document.querySelectorAll('th');

            thElements.forEach(th => {
                const tooltip = th.querySelector('.tooltip');

                th.addEventListener('mouseenter', function() {
                    if (tooltip) {
                        tooltip.classList.remove('hidden');
                    }
                });

                th.addEventListener('mouseleave', function() {
                    if (tooltip) {
                        tooltip.classList.add('hidden');
                    }
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
        function blockEtp(idEtp) {
            $.ajax({
                type: "patch",
                url: "/superAdmins/entreprises/" + idEtp + "/update",
                dataType: "json",
                beforeSend: function() {

                },
                complete: function() {

                },
                success: function(res) {
                    if (res.success) {
                        toastr.success(res.success, 'Succès', {
                            timeOut: 1500
                        });
                        location.reload();
                    } else {
                        toatsr.error("Erreur inconnue !", 'Erreur', {
                            timeOut: 1500
                        });
                    }
                },
                error: function() {
                    console.log("Erreur");
                }
            });
        }

        function unblockEtp(idEtp) {
            $.ajax({
                type: "patch",
                url: "/superAdmins/entreprises/" + idEtp + "/unblock",
                dataType: "json",
                beforeSend: function() {

                },
                complete: function() {

                },
                success: function(res) {
                    if (res.success) {
                        toastr.success(res.success, 'Succès', {
                            timeOut: 1500
                        });
                        location.reload();
                    } else {
                        toatsr.error("Erreur inconnue !", 'Erreur', {
                            timeOut: 1500
                        });
                    }
                },
                error: function() {
                    console.log("Erreur");
                }
            });
        }

        function trashEtp(idEtp) {
            $.ajax({
                type: "patch",
                url: "/superAdmins/entreprises/" + idEtp + "/trash",
                dataType: "json",
                beforeSend: function() {

                },
                complete: function() {

                },
                success: function(res) {
                    if (res.success) {
                        toastr.success(res.success, 'Succès', {
                            timeOut: 1500
                        });
                        location.reload();
                    } else {
                        toatsr.error("Erreur inconnue !", 'Erreur', {
                            timeOut: 1500
                        });
                    }
                },
                error: function() {
                    console.log("Erreur");
                }
            });
        }

        @if (session('success'))
            hideAlertAfterDelay('alert-success', 3000);
        @endif

        @if (session('error'))
            hideAlertAfterDelay('alert-error', 3000);
        @endif
    </script>
@endsection
