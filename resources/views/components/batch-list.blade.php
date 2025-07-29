<table class="table align-middle table-striped caption-top table-hover my-3">
    <thead class="table-light">
        <tr class="!text-2xl font-medium text-gray-800">
            <th scope="col"></th>
            <th scope="col">Nom</th>
            <th scope="col">Nombre d'employe</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $batch)
            <tr class="text-lg text-gray-600">
                <th>{{ ++$key }}</th>
                <th>{{ $batch->name }}</th>
                <th>{{ $batch->batchlearners_count }}</th>
                <th>
                    <div class="space-x-4">
                        <a onclick="__global_drawer('offcanvasApprenant', this)" data-id="{{ $batch->id }}"
                            class="hover:text-inherit focus:outline-none ml-3 inline-flex items-center gap-2 cursor-pointer text-[#A462A4] underline underline-offset-2 
                            transition duration-200">
                            <span data-bs-toggle="tooltip" title="Ajouter vos apprenants"><i
                                    class="text-blue-500 fa-solid fa-user-plus fa-lg"></i>
                        </a>
                        <a data-id="{{ $batch->id }}"
                            class="hover:text-inherit focus:outline-none ml-3 inline-flex items-center gap-2 cursor-pointer text-[#A462A4] underline underline-offset-2 
                            transition duration-200"
                            onclick="showEditModal({{ $batch->id }})">
                            <span data-bs-toggle="tooltip" title="Modifier vos batch"><i
                                    class="text-yellow-400 fa-solid fa-pen fa-lg"></i>
                        </a>
                        <a data-id="{{ $batch->id }}"
                            class="hover:text-inherit focus:outline-none ml-3 inline-flex items-center gap-2 cursor-pointer text-[#A462A4] underline underline-offset-2 
                            transition duration-200"
                            onclick="showDeleteModal({{ $batch->id }})">
                            <span data-bs-toggle="tooltip" title="Supprimer vos batch"><i
                                    class="text-red-400 fa-solid fa-trash-can fa-lg"></i>
                        </a>
                        <a
                            class="hover:text-inherit focus:outline-none ml-3 inline-flex items-center gap-2 cursor-pointer text-[#A462A4] underline underline-offset-2 
                            transition duration-200">
                            <span data-bs-toggle="tooltip" title="Ajouter vos apprenants"><i
                                    class="text-blue-500 fa-solid fa-download fa-lg"></i>
                        </a>
                    </div>
                </th>
            </tr>
        @endforeach
    </tbody>
</table>
