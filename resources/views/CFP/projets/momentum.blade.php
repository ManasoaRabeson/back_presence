@extends('layouts.master')

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
        <div class="flex flex-col gap-4 max-w-screen-xl mx-auto w-full h-[calc(100vh-30px)] mt-[41px] p-4">
            <div class="flex flex-col w-full bg-white p-3 border-[1px] shadow-sm border-gray-200 rounded-lg">
                <div class="inline-flex items-center gap-3">
                    <i class="text-lg fa-solid fa-image"></i>
                    <h3 class="text-xl font-semibold text-gray-700">Momentum (Galerie photo)</h3>
                </div>
                <div class="grid grid-cols-1 gap-4 mt-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    @foreach ($images as $image)
                        <div class="w-full h-64 bg-center bg-cover rounded-lg shadow-md cursor-pointer" {{-- style="background-image: url('{{ $image->url }}');" data-bs-toggle="modal" data-bs-target="#imageModal" --}}
                            style="background-image: url('{{ $digitalOcean . '/img/momentum/' . $image->idProjet . '/' . $image->nomImage }}');"
                            data-bs-toggle="modal" data-bs-target="#imageModal"
                            onclick="setImage('{{ $digitalOcean . '/img/momentum/' . $image->idProjet . '/' . $image->nomImage }}', '{{ $image->nomImage }}', {{ $image->idImages }}, {{ $image->idProjet }})">
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    {{ $images->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Bootstrap -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="text-center modal-body">
                    <img id="modalImage" src="" alt="Image Preview" style="max-width: 100%; height: auto;">
                </div>
                <div class="modal-footer">
                    <button id="downloadButton"
                        class="text-white bg-gradient-to-r from-purple-500 via-purple-600 to-purple-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-purple-300 font-bold rounded-lg text-sm px-5 py-2.5 text-center">
                        Télécharger <i class="fa-solid fa-download"></i>
                    </button>

                    <!-- Formulaire de suppression -->
                    <form id="deleteForm" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="text-white bg-gradient-to-r from-red-500 via-red-600 to-red-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 font-bold rounded-lg text-sm px-5 py-2.5 text-center">
                            Supprimer <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script>
        function setImage(imageUrl, imageName, imageId, projectId) {
            document.getElementById('modalImage').src = imageUrl;
            document.getElementById('imageModalLabel').innerText = imageName;

            console.log("url + " + imageUrl);
            console.log("nom + " + imageName);
            console.log("id + " + imageId);
            console.log("idProjet + " + projectId);


            document.getElementById('downloadButton').onclick = function() {
                downloadImage(imageUrl);
            };

            const deleteForm = document.getElementById('deleteForm');
            deleteForm.onsubmit = function(event) {
                event.preventDefault();

                $.ajax({
                    url: `/cfp/projets/deletephoto/${projectId}/${imageId}`,
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
                        toastr.erreur(response.message, 'Erreur', {
                            timeOut: 1500
                        });
                    }
                });
            };
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
