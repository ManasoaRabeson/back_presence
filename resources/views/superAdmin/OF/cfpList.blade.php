@extends('layouts.masterAdmin')

@section('content')
    <div class="w-full h-full container mt-4">
        @if (count($cfps) <= 0)
            <div>
                Aucun centre de formation professionnelle
            </div>
        @else
            {{-- Début tableau --}}
            <div class="w-full p-4 rounded-3xl bg-white">

                <div class="mb-4">
                    <input type="text" id="searchInput" placeholder="Rechercher un centre de formation..."
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
                    <caption class="text-2xl font-medium text-slate-800">Liste des Centres de formation</caption>
                    <thead>
                        <tr>
                            <th id="sort-iteration" scope="col">
                                #
                            </th>
                            <th scope="col">
                                Date d'inscription
                            </th>
                            <th scope="col">
                                Nom
                            </th>
                            <th scope="col">
                                Description
                            </th>
                            <th scope="col">
                                Nif
                            </th>
                            <th scope="col">
                                Stat
                            </th>
                            <th scope="col">
                                Adresse
                            </th>
                            <th scope="col">
                                Phone
                            </th>
                            <th scope="col">
                                Email
                            </th>
                            <th scope="col">
                                Apprenants
                            </th>
                            <th scope="col">
                                Projets
                            </th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="moduleTableBody">
                        @foreach ($cfps as $cfp)
                            <tr>
                                <td>
                                    <div class="">
                                        {{ $loop->iteration }}
                                    </div>
                                </td>
                                <td>
                                    <div style="width: 150px;">
                                        {{ $cfp->created_at ? \Carbon\Carbon::parse($cfp->created_at)->format('d F Y à H:i:s') : 'Non défini' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="">
                                        {{ $cfp->customerName }}
                                    </div>
                                </td>
                                <td>
                                    <div class="">
                                        {{ $cfp->description }}
                                    </div>
                                </td>
                                <td>
                                    <div class="">
                                        {{ $cfp->nif ?? 'Non défini' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="">
                                        {{ $cfp->stat ?? 'Non défini' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="">
                                        {{ $cfp->customer_addr_lot }}
                                    </div>
                                </td>
                                <td>
                                    <div class="">
                                        {{ $cfp->customerPhone }}
                                    </div>
                                </td>
                                <td>
                                    <div class="">
                                        {{ $cfp->customerEmail }}
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div class="">
                                        {{ $cfp->nbr_apprenants }}
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div class="">
                                        {{ $cfp->nbr_projets }}
                                    </div>
                                </td>
                                <td class="text-right">
                                    @if ($cfp->isActive == 1)
                                        <span class="inline-flex items-center gap-1">
                                            <button title="Modifier" class="btn btn-ghost btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#edit_cfp{{ $cfp->idCfp }}">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <button title="Supprimer" class="btn btn-ghost btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#delete_cfp{{ $cfp->idCfp }}">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                            <button title="Bloquer" class="btn btn-ghost btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#bloc_cfp{{ $cfp->idCfp }}">
                                                <i class="fa-solid fa-lock"></i>
                                            </button>
                                        </span>

                                        <!-- Modal supprimer-->
                                        <div class="modal fade" id="delete_cfp{{ $cfp->idCfp }}" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">
                                                            Confirmez-vous
                                                            la suppression ?</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Si vous confirmez, le centre de formation
                                                            {{ $cfp->customerName }} sera supprimé</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-ghost" data-bs-dismiss="modal">NON</button>
                                                        <button onclick="trashCfp({{ $cfp->idCfp }})"
                                                            class="btn btn-primary">SUPPRIMER</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Bloquer-->
                                        <div class="modal fade" id="bloc_cfp{{ $cfp->idCfp }}" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">
                                                            Confirmez-vous
                                                            cet action?</h1>
                                                        <button class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Si vous confirmez, le centre de formation
                                                            {{ $cfp->customerName }} sera bloqué</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-ghost" data-bs-dismiss="modal">NON</button>
                                                        <button onclick="blocCfp({{ $cfp->idCfp }})"
                                                            class="btn btn-primary">BLOQUER</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Modifier-->
                                        <div class="modal fade" id="edit_cfp{{ $cfp->idCfp }}" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('customers.update', ['id' => $cfp->idCfp]) }}"
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
                                                                    class="form-control" value="{{ $cfp->nif }}"
                                                                    required>
                                                            </div>
                                                            <div class="flex form-group mt-3">
                                                                <label for="stat" class="mr-4 w-14 mt-2">STAT
                                                                    :</label>
                                                                <input type="text" name="stat" id="stat"
                                                                    class="form-control" value="{{ $cfp->stat }}"
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
                                    @elseif ($cfp->isActive == 0)
                                        <a href="#" onclick="unblock({{ $cfp->idCfp }})"
                                            class="btn btn-outline btn-error btn-sm">Débloquer</a>
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
        function blocCfp(idCfp) {
            $.ajax({
                type: "patch",
                url: "/superAdmins/cfp/" + idCfp + "/update",
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
                        toastr.error("Erreur inconnue !", 'Erreur', {
                            timeOut: 1500
                        });
                    }
                },
                error: function() {
                    console.log("Erreur");
                }
            });
        }

        function unblock(idCfp) {
            $.ajax({
                type: "patch",
                url: "/superAdmins/cfp/" + idCfp + "/unblock",
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
                        toastr.error("Erreur inconnue !", 'Erreur', {
                            timeOut: 1500
                        });
                    }
                },
                error: function() {
                    console.log("Erreur");
                }
            })
        }

        function trashCfp(idCfp) {
            $.ajax({
                type: "patch",
                url: "/superAdmins/cfp/" + idCfp + "/trash",
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
                        toastr.error("Erreur inconnue !", 'Erreur', {
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
