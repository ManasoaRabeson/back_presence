@extends('layouts.masterAdmin')

@section('content')
    <div class="container mx-auto p-6 bg-white">
        <h2 class="text-2xl font-bold mb-6 text-center">Liste des Organismes</h2>

        <div id="message-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-[#c9dcc2] p-6 rounded-lg shadow-lg">
                <p id="modal-message" class="text-lg font-medium text-[#7ac35f]"></p>
            </div>
        </div>

        <div class="mb-6 text-center">
            <p for="" class="text-lg mb-4">Veuillez choisir 6 organismes au mieux</p>
            <label for="limit" class="mr-2 font-medium">Nombre maximum d'organismes à sélectionner ( 6 au mieux )
            </label>
            <input type="number" id="limit" min="1" max="{{ $customers->count() }}"
                class="p-2 border rounded-md w-20 text-center">
        </div>

        <div class="mb-6 text-center">
            <label for="search" class="mr-2 font-medium">Recherche :</label>
            <input type="text" id="search" class="p-2 border rounded-md" placeholder="Rechercher un organisme...">
        </div>

        <div id="message" class="text-red-500 font-medium text-center mb-6"></div>

        <div class="text-center mt-6 mb-10">
            <button id="validate-button" class="bg-[#1a5d1a] text-white px-4 py-2 rounded-lg">Mettre à jour</button>
        </div>

        <p class="text-xl mb-8">Il y a <strong>{{ $customers->count() }}</strong> organismes au total </p>

        <div id="organism-list" class="grid grid-cols-2 sm:grid-cols-8 gap-4">
            @foreach ($customers as $customer)
                <div class="flex flex-col items-center space-y-2 p-4 bg-gray-50 border rounded-lg cursor-pointer organism-item {{ in_array($customer->idCustomer, $selectedCustomers) ? 'selected' : '' }}"
                    data-id="{{ $customer->idCustomer }}">
                    <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/entreprises/{{ $customer->logo }}"
                        alt="Entreprise Logo" class="w-full h-24">
                    <div class="flex-1">
                        <p class="font-semibold text-lg">{{ $customer->customerName }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <input type="hidden" id="selected-organisms" name="selected-organisms">
    </div>

    <script>
        const limitInput = document.getElementById('limit');
        const searchInput = document.getElementById('search');
        const organismItems = document.querySelectorAll('.organism-item');
        const messageDiv = document.getElementById('message');
        const validateButton = document.getElementById('validate-button');
        const selectedOrganismsInput = document.getElementById('selected-organisms');

        const maxSelections = {{ $customers->count() }};

        function updateOrganismState() {
            const limit = parseInt(limitInput.value) || 0;
            const selectedCount = document.querySelectorAll('.selected').length;

            if (limit > maxSelections) {
                messageDiv.textContent = `Le chiffre entré doit être inférieur ou égal à ${maxSelections}.`;
                limitInput.value = '';
                return;
            } else {
                messageDiv.textContent = "";
            }

            organismItems.forEach((item) => {
                if (selectedCount >= limit && !item.classList.contains('selected')) {
                    item.classList.add('disabled');
                } else {
                    item.classList.remove('disabled');
                }
            });

            if (selectedCount >= limit) {
                messageDiv.textContent = "Vous avez atteint le nombre d'organismes à sélectionner.";
            }
        }

        limitInput.addEventListener('input', updateOrganismState);

        organismItems.forEach((item) => {
            item.addEventListener('click', function() {
                const limit = parseInt(limitInput.value) || 0;
                const selectedCount = document.querySelectorAll('.selected').length;

                if (!item.classList.contains('selected') && selectedCount < limit) {
                    item.classList.add('selected');
                } else if (item.classList.contains('selected')) {
                    item.classList.remove('selected');
                }

                updateOrganismState();
            });
        });

        searchInput.addEventListener('input', function() {
            const searchTerm = searchInput.value.toLowerCase();
            organismItems.forEach(item => {
                const customerName = item.querySelector('.font-semibold').textContent.toLowerCase();
                if (customerName.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        validateButton.addEventListener('click', function() {
            const selectedIds = Array.from(document.querySelectorAll('.organism-item.selected'))
                .map(item => item.getAttribute('data-id'));
            selectedOrganismsInput.value = selectedIds.join(',');

            fetch("{{ route('superAdmins.organismevalidate') }}?ids=" + selectedOrganismsInput.value)
                .then(response => response.json())
                .then(data => {
                    const messageModal = document.getElementById('message-modal');
                    const modalMessage = document.getElementById('modal-message');

                    if (data.success) {
                        modalMessage.textContent = data.message;
                        messageModal.classList.remove('hidden');
                    } else {
                        modalMessage.textContent = data.message;
                        messageModal.classList.remove('hidden');
                    }

                    setTimeout(() => {
                        messageModal.classList.add('hidden');
                    }, 2000);
                })
                .catch(error => {
                    const messageModal = document.getElementById('message-modal');
                    const modalMessage = document.getElementById('modal-message');
                    modalMessage.textContent = "Une erreur s'est produite lors de la validation.";
                    messageModal.classList.remove('hidden');

                    setTimeout(() => {
                        messageModal.classList.add('hidden');
                    }, 5000);
                });
        });
    </script>

    <style>
        .selected {
            background-color: #797b7e;
            border-width: 2px;
        }

        .disabled {
            pointer-events: none;
            opacity: 0.6;
        }
    </style>
@endsection
