@extends('layouts.masterAdmin')

@section('content')

  <div class="container mx-auto p-6 bg-white">
      
    <h2 class="text-2xl font-bold mb-6 text-center">Liste des modules pour le centre de formation</h2>

    <button  class="bg-green-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-600 transition duration-200 mt-4 mb-10">
        <a href="/superAdmins/listeModulePromu">
            Voir les promus
        </a>
    </button>
    
    <div class="overflow-x-auto">

        <div class="mb-4">
            <input type="text" id="searchInput" placeholder="Rechercher une module..." class="w-full p-2 border rounded">
        </div>

        @if(session('success'))
            <div id="success-message" class="bg-green-500 text-white p-4 rounded-lg fixed top-0 right-0 m-4">
                {{ session('success') }}
            </div>

            <script>
                setTimeout(function() {
                    document.getElementById('success-message').style.display = 'none';
                    location.reload();
                }, 3000); 
            </script>
        @endif

        @if(session('error'))
            <div id="error-message" class="bg-red-500 text-white p-4 rounded-lg fixed top-0 right-0 m-4">
                {{ session('error') }}
            </div>

            <script>
                setTimeout(function() {
                    document.getElementById('error-message').style.display = 'none';
                    location.reload(); 
                }, 3000); 
            </script>
        @endif

        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-[#717981] text-white">
                <tr>
                    <th class="py-4 px-6 text-left">Titre Module</th>
                    <th class="py-4 px-6 text-left">Description</th>
                    <th class="py-4 px-6 text-left">Status</th>
                    <th class="py-4 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="moduleTableBody">
                @foreach ( $moduleCfp as $module )
                    <tr class="border-b hover:bg-gray-100 transition">
                        <td class="py-4 px-6">
                            {{ $module->moduleName }}
                        </td>
                        <td class="py-4 px-6 text-gray-800 font-medium w-[700px]">{{ $module->description ?? 'Aucune description' }}</td>
                        <td class="py-4 px-6">
                            <span class="{{ $module->is_active == 'Promu' ? 'text-green-600' : 'text-red-600' }} font-medium">
                                {{ $module->is_active }}
                            </span>
                            @if ($module->is_active == 'Non Promu')
                                <button 
                                    class="ml-2 bg-blue-500 text-white rounded-lg px-2 py-1 hover:bg-blue-600"
                                    onclick="promoteModule('{{ $module->idModule }}')">
                                    Promouvoir
                                </button>
                            @endif
                        </td>                        
                        <td class="py-4 px-6 text-center">
                            <a href="/formation/detail/{{ $module->idModule }}" title="Aperçu client">
                                <i class="fa-solid fa-eye text-2xl text-[#717981]"></i>
                            </a>
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

        var csrfToken = '{{ csrf_token() }}';
        console.log("Token CSRF:", csrfToken);

        function promoteModule(moduleId) {
            $.ajax({
                url: '/superAdmins/modulePromu/' + moduleId,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}' 
                },
                success: function(response) {
                    console.log("Module promu avec succès:", response);
                    alert(response.message);
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error("Erreur lors de la promotion du module:", error);
                    alert(xhr.responseJSON.error);
                    location.reload();
                }
            });
        }

    </script>
@endsection