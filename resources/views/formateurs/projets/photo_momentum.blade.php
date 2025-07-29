@extends('layouts.masterForm')

@section('content')
    <style>
        .scroll::-webkit-scrollbar {
            display: none;
        }

        .scroll {
            -ms-overflow-style: none;
            overflow: -moz-scrollbars-none;
        }

        .infobulle {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            z-index: 1000;
        }
    </style>
    <div class="w-full flex h-full min-w-[650px] overflow-x-scroll flex-col bg-gray-100">
        <div class="flex flex-col gap-4 max-w-screen-xl mx-auto w-full h-[calc(100vh-30px)] p-4">
            <div class="flex flex-col w-full bg-white p-3 border-[1px] shadow-sm border-gray-200 rounded-lg">
                <div class="inline-flex items-center gap-3">
                    <i class="text-lg fa-solid fa-image"></i>
                    <h3 class="text-xl mb-1 font-semibold text-gray-700">Momentum (Galerie photo)</h3>
                </div>
                <div class="w-full min-h-screen bg-white grid grid-cols-4 grid-rows-2 gap-0.5">
                    @foreach ($images as $image)
                        <div class="relative bg-[#B09ECD] overflow-hidden group">
                            <img class="absolute opacity-90 w-full h-full object-cover"
                                src="{{ $digitalOcean . '/img/momentum/' . $image->idProjet . '/' . $image->nomImage }}"
                                alt="Project Image" />
                            <div
                                class="absolute inset-0 bg-[#B09ECD]/90 grid justify-center items-center transform translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-in-out">
                                <button
                                    class="uppercase font-thin tracking-wider border border-white text-white py-2 px-4 hover:bg-[#ae86c2] hover:text-[#B09ECD] hover:scale-105 transition-transform duration-300 ease-in-out"
                                    onclick="setImage('{{ $digitalOcean . '/img/momentum/' . $image->idProjet . '/' . $image->nomImage }}', '{{ $image->nomImage }}', {{ $image->idImages }}, {{ $image->idProjet }})">
                                    voir la photo
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{-- <div class="mt-4">
                    {{ $images->links('pagination::tailwind') }}
                </div> --}}
            </div>
        </div>
    </div>

    <!-- Modal Bootstrap -->
    <div id="imageModal" class="fixed top-0 left-0 w-full h-full bg-black/70 flex justify-center items-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg w-11/12 max-w-2xl p-4 relative">
            <button class="absolute top-2 right-2 text-gray-700 hover:text-red-500" onclick="closeModal()">✖</button>
            <h5 id="imageModalLabel" class="text-lg font-bold mb-4"></h5>
            <div class="text-center">
                <img id="modalImage" src="" alt="Image Preview" class="max-w-full h-auto mx-auto">
            </div>
            <div class="mt-4 flex justify-end space-x-2">
                <button id="downloadButton"
                    class="text-white bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:outline-none focus:ring-purple-300 font-bold rounded-lg text-sm px-5 py-2.5">
                    Télécharger <i class="fa-solid fa-download"></i>
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-bold rounded-lg text-sm px-5 py-2.5">
                        Supprimer <i class="fa-solid fa-trash-can"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script>
        function setImage(imageUrl, imageName, imageId, projectId) {
            // Mise à jour du contenu du modal
            document.getElementById('modalImage').src = imageUrl;
            document.getElementById('imageModalLabel').innerText = imageName;

            document.getElementById('downloadButton').onclick = function() {
                downloadImage(imageUrl);
            };

            const deleteForm = document.getElementById('deleteForm');
            deleteForm.onsubmit = function(event) {
                event.preventDefault();

                $.ajax({
                    url: `/projetsForm/deletephoto/${projectId}/${imageId}`,
                    method: 'DELETE',
                    datatype: 'json',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message, 'Succès', {
                                timeOut: 1500
                            });
                            location.reload();
                        } else {
                            toastr.info(response.message, 'Information', {
                                timeOut: 1500
                            });
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Erreur lors de la suppression', 'Erreur', {
                            timeOut: 1500
                        });
                    }
                });
            };

            // Afficher le modal
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeModal() {
            // Masquer le modal
            document.getElementById('imageModal').classList.add('hidden');
        }


        // Configuration de CORS
        async function downloadImage(imageUrl) {
            try {
                const response = await fetch(imageUrl);
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                a.download = imageUrl.split('/').pop();
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            } catch (error) {
                console.error('Erreur lors du téléchargement de l\'image:', error);
            }
        }
    </script>
@endsection
