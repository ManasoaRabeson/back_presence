@extends('layouts.masterEtp')

@push('custom_style')
    <style>
        img {
            display: block;
            max-width: 100%;
        }

        .preview {
            text-align: center;
            overflow: hidden;
            width: 160px;
            height: 160px;
            margin: 10px;
            border: 1px solid red;
        }

        .modal-lg {
            max-width: 1000px;
        }

        .toggle {
            border-radius: 4px;
        }

        .toggle-handle {
            border-radius: 2px;
        }
    </style>
@endpush

@section('content')
    <div class="flex flex-col w-full h-full overflow-y-scroll">
        <div class="w-full h-full max-w-screen-xl mx-auto">
            <h1>Listes de batches</h1>
            <button type="button" class="bg-blue-400 px-3 py-2 mt-2 rounded rounded-xl" data-bs-toggle="modal"
                data-bs-target="#newBatch"> Nouveau batch </button>
            <div class="" id="listBatch">

            </div>

            <!-- New Modal -->
            <div class="modal fade" id="newBatch" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Nouveau batch</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="new_modal">
                                <div class="mb-3">
                                    <label for="emp_matricule" class="form-label">Nom</label>
                                    <input type="text" name="name" id="name" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-default bg-secondary">Ajouter</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Edit Modal --}}
            <span class="edit_modal"></span>
        </div>
    </div>
@endsection

@push('custom_style')
    <link rel="stylesheet" href="{{ asset('css/bootstrap5-toggle.min.css') }}">
@endpush

@section('script')
    <script>
        let name = $('#name');
        $(document).ready(function() {
            getBatch();
        });

        $('#new_modal').submit(function(e) {
            e.preventDefault();
            saveBatch(name.val());
            $('#newBatch').modal('hide');
            name.val('');
        });

        function showDeleteModal(id) {
            var delete_modal = $('.edit_modal');

            console.log('ok');


            delete_modal.empty();
            delete_modal.append(`
                    <div id="deleteConfirmationModal` + id + `" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body p-0">
                                    <form>
                                        <div class="p-5 text-center">
                                            <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                                            <div class="text-3xl mt-5">Vous etes sure?</div>
                                            <div class="text-slate-500 mt-2">
                                                Voulez-vous vraiment supprimer cette enregistrement?

                                                Cette operation est irréversible.
                                            </div>
                                        </div>
                                        <div class="space-x-6 pb-8 text-center">
                                            <button type="button" data-bs-dismiss="modal" class="bg-gray-500 p-2 rounded rounded-xl">Annuler</button>
                                            <button type="button" onclick="deleteBatch(${id})" class="bg-red-500 p-2 rounded rounded-xl ">Supprimer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>`);

            $('#deleteConfirmationModal' + id).modal('show');
        }

        function showEditModal(id) {
            var edit_modal = $('.edit_modal');

            $.ajax({
                type: "get",
                url: "/etp/batch/" + id,
                dataType: "json",
                success: function(response) {
                    console.log(response);
                    edit_modal.empty();

                    edit_modal.append(`<div class="modal fade" id="editBatch` + id + `" tabindex="-1" aria-labelledby="exampleModalLabel"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Modification de batch</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="emp_matricule" class="form-label">Nom</label>
                                                                <input type="text" name="name_batch" id="name_batch"  class="form-control" value="${response.batch.name}">
                                                            </div>
                                                            <button type="button" onclick="updateBatch(${id})"
                                                                class="btn btn-default bg-secondary">Modifier</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>`);

                    $('#editBatch' + id).modal('show');
                }
            });


        }

        function saveBatch(name) {
            $.ajax({
                type: "post",
                url: "/etp/batch/create",
                data: {
                    name: name,
                    customer_id: {{ $idCustomer }}
                },
                success: function(response) {
                    toastr.success(response.message, 'Success', {
                        timeOut: 1500
                    });
                    getBatch();
                }
            });
        }

        function updateBatch(id) {
            let name = $('#name_batch').val();
            $.ajax({
                type: "put",
                url: "/etp/batch/update/" + id,
                data: {
                    name: name,
                    customer_id: {{ $idCustomer }}
                },
                success: function(response) {
                    toastr.success(response.message, 'Success', {
                        timeOut: 1500
                    });
                    getBatch();
                },
                error: function(error) {
                    toastr.error(response.error, 'Erreur inconnue', {
                        timeOut: 1500
                    });
                }
            });
        }

        function deleteBatch(id) {
            $.ajax({
                type: "delete",
                url: "/etp/batch/delete/" + id,
                success: function(response) {
                    toastr.success(response.message, 'Success', {
                        timeOut: 1500
                    });
                    getBatch();
                    $('#deleteConfirmationModal' + id).modal('hide');
                },
                error: function(xhr, status, error) {
                    const errorMessage = xhr.responseJSON ? xhr.responseJSON.error : 'Unknown error';

                    toastr.error(errorMessage, 'Erreur inconnue', {
                        timeOut: 1500
                    });

                    $('#deleteConfirmationModal' + id).modal('hide')
                }
            });
        }

        function getBatch() {
            let listBatch = $('#listBatch');

            $.ajax({
                type: "get",
                url: "/etp/batch/getAll",
                success: function(response) {
                    listBatch.html('');
                    listBatch.append(response.results);
                }
            });
        }

        function __global_drawer(__offcanvas, element) {
            let __global_drawer = $('#drawer_content_detail');
            __global_drawer.html('');

            switch (__offcanvas) {
                case 'offcanvasApprenant':

                    __global_drawer.append(`<x-drawer-apprenant></x-drawer-apprenant>`);
                    __global_drawer.ready(function() {

                        var select_appr_project = $('#select_appr_project');
                        var all_apprenant_selected = $('#all_apprenant_selected');

                        let id = $(element).data("id");
                        getApprenant(id);
                        getApprenantAdded(id);

                        $('.list').hide();
                    });
                    break;

                default:
                    break;
            }

            let offcanvasId = $('#' + __offcanvas)
            var bsOffcanvas = new bootstrap.Offcanvas(offcanvasId);
            bsOffcanvas.show();
        }

        function getApprenant(id) {
            $.ajax({
                type: "get",
                url: "/etp/batch_learner/non_participant/" + id,
                success: function(response) {
                    var select_appr_project = $('#select_appr_project');
                    select_appr_project.html('')
                    $.each(response.employes, function(index, employe) {
                        select_appr_project.append(`<li class="list list_` + employe.idCustomer + ` grid grid-cols-5 w-full px-3 py-2 border-[1px] border-gray-100 cursor-pointer hover:bg-gray-50 duration-200 rounded-md !bg-white">
                            <div class="col-span-4">
                                <div class="inline-flex items-center gap-2">
                                    <span id="photo_appr_` + employe.idEmploye + `"></span>
                                    <div class="flex flex-col gap-0">
                                        <p class="text-base font-normal text-gray-700">` + employe.name + ` ` + employe
                            .firstName + `</p>
                                    </div>
                                </div>
                            </div>
                            <div class="grid items-center justify-center w-full col-span-1">
                                <div onclick="manageApprenantInterAdd(` + id + `, ` + employe.idEmploye + `)" 
                                    class="flex items-center justify-center w-10 h-10 duration-150 bg-green-100 rounded-full cursor-pointer icon hover:bg-green-50 group/icon">
                                    <i class="text-sm text-green-500 duration-150 fa-solid fa-plus group-hover/icon:text-green-600"></i>
                                </div>
                            </div>
                        </li>`);

                        var photo_appr = $('#photo_appr_' + employe.idEmploye);
                        photo_appr.html('');

                        if (employe.photo == "" || employe.photo == null) {
                            if (employe.firstName != null) {
                                photo_appr.append(
                                    `<div class="flex items-center justify-center w-10 h-10 mr-4 text-gray-500 uppercase bg-gray-200 rounded-full">` +
                                    employe.firstName[0] + `</div>`);
                            } else {
                                photo_appr.append(
                                    `<div class="flex items-center justify-center w-10 h-10 mr-4 text-gray-500 uppercase bg-gray-200 rounded-full">` +
                                    employe.name[0] + `</div>`);
                            }
                        } else {
                            photo_appr.append(
                                `<img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/employes/` +
                                employe.photo +
                                `" alt="Avatar" class="object-cover w-10 h-10 mr-4 rounded-full">`);
                        }
                    });
                },
                error: function(xhr) {
                    console.log("ERReur");
                }
            });
        }

        function getApprenantAdded(id) {
            $.ajax({
                type: "get",
                url: "/etp/batch_learner/participant/" + id,
                success: function(res) {
                    var all_apprenant_selected = $('#all_apprenant_selected');
                    all_apprenant_selected.html('');
                    $.each(res.employes, function(index, emp) {
                        all_apprenant_selected.append(`<li class="list list_` + emp.idCustomer + ` grid grid-cols-5 w-full px-3 py-2 border-[1px] border-gray-100 cursor-pointer hover:bg-gray-50 duration-200 rounded-md !bg-white">
                            <div class="col-span-4">
                                <div class="inline-flex items-center gap-2">
                                    <span id="photo_appr_` + emp.idEmploye + `"></span>
                                    <div class="flex flex-col gap-0">
                                        <p class="text-base font-normal text-gray-700">` + emp.name + ` ` + emp
                            .firstName + `</p>
                                    </div>
                                </div>
                            </div>
                            <div class="grid items-center justify-center w-full col-span-1">
                                <div onclick="manageApprenantInter(` + emp.id + `, ` + emp.batch_id + `)" class="flex items-center justify-center w-10 h-10 duration-150 bg-red-100 rounded-full cursor-pointer icon hover:bg-red-50 group/icon">
                                    <i class="text-sm text-red-500 duration-150 fa-solid fa-minus group-hover/icon:text-red-600"></i>
                                </div>
                            </div>
                        </li>`);

                        var photo_appr = $('#photo_appr_' + emp.idEmploye);
                        photo_appr.html('');

                        if (emp.photo == "" || emp.photo == null) {
                            if (emp.firstName != null) {
                                photo_appr.append(
                                    `<div class="flex items-center justify-center w-10 h-10 mr-4 text-gray-500 uppercase bg-gray-200 rounded-full">` +
                                    emp.firstName[0] + `</div>`);
                            } else {
                                photo_appr.append(
                                    `<div class="flex items-center justify-center w-10 h-10 mr-4 text-gray-500 uppercase bg-gray-200 rounded-full">` +
                                    emp.name[0] + `</div>`);
                            }
                        } else {
                            photo_appr.append(
                                `<img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/employes/` +
                                emp.photo +
                                `" alt="Avatar" class="object-cover w-10 h-10 mr-4 rounded-full">`);
                        }
                    });
                },
                error: function(xhr) {
                    console.log("ERReur");
                }
            });
        }

        function manageApprenantInter(id, batch_id) {
            $.ajax({
                type: "delete",
                url: "/etp/batch_learner/delete/" + id,
                data: {
                    _token: '{!! csrf_token() !!}'
                },
                dataType: "json",
                success: function(res) {
                    toastr.success(res.success, 'Succès', {
                        timeOut: 1500
                    });
                    getApprenant(batch_id);
                    getApprenantAdded(batch_id);
                    getBatch();
                },
                error: function(xhr) {
                    console.log(xhr);

                }
            });
        }

        function manageApprenantInterAdd(batch_id, employe_id) {
            $.ajax({
                type: "post",
                url: "/etp/batch_learner/create",
                data: {
                    employe_id: employe_id,
                    batch_id: batch_id
                },
                dataType: "json",
                success: function(res) {
                    toastr.success(res.success, 'Succès', {
                        timeOut: 1500
                    });
                    getApprenant(batch_id);
                    getApprenantAdded(batch_id);
                    getBatch();
                },
                error: function(r) {
                    toastr.error('Erreur', r, {
                        timeOut: 1500
                    });
                }

            });
        }
    </script>
@endsection
