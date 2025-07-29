@extends('layouts.masterAdmin')

@section('content')
    <div class="flex flex-col w-full h-[90vh]">
        <div class="container bg-white rounded-3xl">

            <div class="mb-4">
                <input type="text" id="searchInput" placeholder="Rechercher ..." class="w-full p-2 mt-8 border rounded">
            </div>

            <x-table titre="Liste des abonnements" class="table">
                <thead>
                    <x-tr>
                        <x-th>Société</x-th>
                        <x-th>Société email</x-th>
                        <x-th>Plan Name</x-th>
                        <x-th>Plan Price</x-th>
                        <x-th>Start Date</x-th>
                        <x-th>End Date</x-th>
                        <x-th class="text-right">Action</x-th>
                    </x-tr>
                </thead>
                <tbody id="moduleTableBody">
                    @if ($subscriptions->isEmpty())
                        <x-td>Aucun abonnement effectué</x-td>
                    @else
                        @foreach ($subscriptions as $subscription)
                            <x-tr onclick="displayPayment({{ $subscription->subscriber_id }})">
                                <x-td>{{ $subscription->customer->customerName }}</x-td>
                                <x-td>{{ $subscription->customer->customerEmail }}</x-td>
                                <x-td>{{ $subscription->plan->name }}</x-td>
                                <x-td>{{ number_format($subscription->plan->price, 0, ',', ' ') }}
                                    {{ $subscription->plan->currency }}</x-td>
                                <x-td>{{ \Carbon\Carbon::parse($subscription->starts_at)->translatedFormat('d F Y') }}</x-td>
                                <x-td>{{ \Carbon\Carbon::parse($subscription->ends_at)->translatedFormat('d F Y') }}</x-td>
                                <x-td class="text-right">
                                    @if ($subscription->plan->id == 1)
                                        <button disabled class="px-3 py-1 text-white bg-gray-400 rounded-md">
                                            Annuler l'abonnement
                                        </button>
                                    @else
                                        <form action="{{ route('subscriptions.change', $subscription->id) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 text-white bg-gray-700 rounded-md">
                                                Annuler l'abonnement
                                            </button>
                                        </form>
                                    @endif
                                </x-td>
                            </x-tr>
                            <tr id="paymentShow_{{ $subscription->subscriber_id }}" class="w-full">
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </x-table>
        </div>
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

        function formatDate(dateString) {
            const mois = [
                "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
                "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
            ];

            let date = new Date(dateString);
            let jour = date.getDate();
            let moisNom = mois[date.getMonth()];
            let annee = date.getFullYear();

            return `${jour} ${moisNom} ${annee}`;
        }

        function displayPayment(user_id) {
            let container = $(`#paymentShow_${user_id}`);

            // Vérifie si le contenu est déjà affiché
            if (container.children().length > 0) {
                container.slideToggle(); // Cache ou affiche le contenu
                return;
            }

            $.ajax({
                url: `/abonnement/su/payment/${user_id}`,
                type: 'GET',
                success: function(response) {
                    let tableContent = `
                <td colspan="7">
                    <div class="flex flex-col w-full gap-2">
                        <div class="text-[#A462A4] font-semibold text-lg">Hitorique d'abonnement</div>
                        <table class="w-full table-auto">
                            <thead>
                                <tr class="border-b-2">
                                    <th class="p-2 text-gray-500">#</th>
                                    <th class="p-2 text-gray-500">N° order</th>
                                    <th class="p-2 text-gray-500">Date de Paiement</th>
                                    <th class="p-2 text-gray-500">Mode de Paiement</th>
                                    <th class="p-2 text-gray-500">Type d'abonnement</th>
                                    <th class="p-2 text-gray-500">Prix Total</th>
                                </tr>
                            </thead>
                            <tbody>`;

                    response.forEach((payment, index) => {
                        tableContent += `
                        <tr class="border-b-2">
                            <td class="p-2 text-gray-500">${index + 1}</td>
                            <td class="p-2 text-gray-500">${payment.id_order}</td>
                            <td class="p-2 text-gray-500">${formatDate(payment.payment_date)}</td>
                            <td class="p-2 text-gray-500">${payment.payment_method || 'N/A'}</td>
                            <td class="p-2 text-gray-500">${payment.subscription_name}</td>
                            <td class="p-2 text-gray-500">${payment.total_price ? Math.round(payment.total_price).toLocaleString('fr-FR') + ' ar' : 'N/A'}</td>
                        </tr>`;
                    });

                    tableContent += `
                            </tbody>
                        </table>
                    </div>
                </td>`;

                    container.hide().html(tableContent)
                        .slideDown(); // Ajoute le contenu et affiche avec animation
                },
                error: function(xhr) {
                    alert('Erreur. Veuillez réessayer.');
                }
            });
        }
    </script>
@endsection
