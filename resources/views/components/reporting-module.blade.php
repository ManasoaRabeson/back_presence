<div id="tableReporting" class="w-full px-4 bg-white rounded-md shadow-sm xl:container table-responsive-xxl">
    <div class="flex justify-between items-center">
        <div>
            <p class="text-xl font-semibold">{{ $module->moduleName }}</p>
            <p>Niveau: {{ $module->module_level_name }}</p>
        </div>

        <div class="inline-flex justify-between ">
            <div class="flex gap-2 my-5 w-fit">
                <div class="">
                    <a href="{{ Route('exportXl') }}"
                        class="px-4 py-3 font-normal text-green-600 duration-200 bg-green-100 rounded-lg hover:text-green-700 hover:bg-green-200">
                        <span class="inline-flex items-center gap-2">
                            <i class="text-xl fa-solid fa-file-excel"></i>
                            <p>Telecharger en Excel</p>
                        </span>
                    </a>
                </div>
                <div class="">
                    <a href="{{ Route('exportPdf') }}"
                        class="px-4 py-3 font-normal text-red-500 duration-200 bg-red-100 rounded-lg hover:text-red-700 hover:bg-red-200">
                        <span class="inline-flex items-center gap-2">
                            <i class="text-xl fa-solid fa-file-pdf"></i>
                            <p>Telecharger en PDF</p>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @foreach ($projects as $module)
        <div class="text-xl flex justify-between font-semibold bg-gray-200 w-full py-3 rounded rounded-xl px-4">
            <span>{{ $module['year']}} </span> 
            <span>{{ $module['count_project'] }} projets</span>
        </div>
        @if (count($module['modules']) > 0)
            <table class="table font-sans table-hover my-3">
                <thead class="table-light">
                    <tr class="text-gray-700">
                        <th scope="col"></th>
                        <th scope="col">Client</th>
                        <th scope="col">Type de projet</th>
                        <th scope="col">Date debut</th>
                        <th scope="col">Date fin</th>
                        <th scope="col">Prix</th>
                        <th scope="col">Status</th>
                        <th scope="col">Lieu</th>
                        <th scope="col">Detail</th>
                    </tr>
                </thead>
                <tbody id="showResult">
                    @foreach ($module['modules'] as $key => $mod)
                        <tr class="text-gray-600">
                            <td>{{ ++$key }}</td>
                            <td>{{ $mod->etp_name}}</td>
                            <td>{{ $mod->project_type}}</td>
                            <td>{{ $mod->dateDebut}}</td>
                            <td>{{ $mod->dateFin}}</td>
                            <td>{{number_format($mod->total_ttc, 2, ".", " ")}} Ar</td>
                            <td>{{ $mod->project_status}}</td>
                            <td>{{ $mod->ville}}</td>
                            <td> 
                                <a href="/cfp/projets/{{ $mod->idProjet }}/detail">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="mb-3 ml-1">Aucun</p>
        @endif
       
    @endforeach
</div>