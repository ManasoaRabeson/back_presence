@extends('layouts.masterAdmin')

@section('content')
    <div class="container mx-auto p-6 bg-white">

        <h2 class="text-2xl font-bold mb-6 text-center">Création d'une publicité</h2>

        <button
            class="bg-green-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-600 transition duration-200 mt-4 mb-10">
            <a href="/superAdmins/listeModulePromu">
                Voir les promus
            </a>
        </button>

        <div class="overflow-x-auto">

            <div class="mb-4">
                <input type="text" id="searchInput" placeholder="Rechercher une module..." class="w-full p-2 border rounded">
            </div>

            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden mb-20">
                <thead class="bg-[#717981] text-white">
                    <tr>
                        <th class="py-4 px-6 text-left">Logo</th>
                        <th class="py-4 px-6 text-left">Nom du Centre</th>
                        <th class="py-4 px-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="moduleTableBody">
                    @foreach ($customers as $customer)
                        <tr class="border-b hover:bg-gray-100 transition">
                            <td class="py-4 px-6">
                                <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/entreprises/{{ $customer->logo }}"
                                    alt="Logo Cfp" class="w-20 h-20 rounded-lg">
                            </td>
                            <td class="py-4 px-6 text-gray-800 font-medium">{{ $customer->customerName }}</td>
                            <td class="py-4 px-6 text-center">
                                <a href="/superAdmins/moduleCfp/{{ $customer->idCustomer }}"><i
                                        class="fa-solid fa-eye text-2xl text-[#717981]"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

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
    </script>
@endsection
