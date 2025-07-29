@extends('layouts.masterAdmin')

@section('content')

  <div class="container mx-auto p-6 bg-white">
      
    <h2 class="text-2xl font-bold mb-6 text-center">Voici les modules promu actuellement</h2>

    <div class="overflow-x-auto">
        <form id="promotionForm" action="{{ route('superAdmins.updateRang') }}" method="POST">
            @csrf
            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead class="bg-[#717981] text-white">
                    <tr>
                        <th class="py-4 px-6 text-left">Titre Module</th>
                        <th class="py-4 px-6 text-left">Description</th>
                        <th class="py-4 px-6 text-center">Rang d'Apparition</th>
                        <th class="py-4 px-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $promu as $promotion )
                        <tr class="border-b hover:bg-gray-100 transition">
                            <input type="hidden" value="{{ $promotion->id }}" name="ids[]">
                            <td class="py-4 px-6">
                                {{ $promotion->moduleName }}
                            </td>
                            <td class="py-4 px-6 text-gray-800 font-medium w-[500px]">{{ $promotion->description ?? 'Aucune description' }}</td>
                            <td class="py-4 px-6 text-center">
                                <input type="number" class="w-16 text-center border border-1 border-gray-300 rounded py-2" name="rang_apparition[]" min="1" value="{{ $promotion->rang_affichage }}">
                            </td>                            
                            <td class="py-4 px-6 text-red-700 font-medium text-center"><a href="/superAdmins/detache/{{ $promotion->id }}" onclick="reloadPage()">Détaché</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="flex justify-end mt-4">
                <button class="bg-green-500 text-white py-2 px-6 rounded-lg hover:bg-green-600 transition duration-200">
                    Sauvegarder
                </button>
            </div>
        </form>
    </div>

  </div>

@endsection

@section('script')
    <script>
        document.getElementById('promotionForm').addEventListener('submit', function (event) {
            const rangInputs = document.querySelectorAll('input[name="rang_apparition[]"]');
            const rangValues = Array.from(rangInputs).map(input => input.value);
            const uniqueRangs = new Set(rangValues);

            if (uniqueRangs.size !== rangValues.length) {
                event.preventDefault(); // Prevent form submission
                alert('Les rangs d\'apparition doivent être différents.');
            }
        });

        function reloadPage() {
            setTimeout(function() {
                location.reload();
            }, 1000); 
        }
    </script>
@endsection