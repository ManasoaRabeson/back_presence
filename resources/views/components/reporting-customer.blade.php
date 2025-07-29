<div class="w-full p-4 bg-white rounded-md shadow-sm xl:container table-responsive-xxl">
    <div class="text-xl flex justify-between bg-gray-200 w-full py-3 rounded rounded-xl px-4">
        Referents
    </div>
    @foreach ($referents as $ref)
        <p>Nom: {{$ref->name}} {{$ref->firstName}}</p>
        <p>Email: {{$ref->email}}</p>
    @endforeach
</div>

{{-- <div class="w-full p-4 bg-white rounded-md shadow-sm xl:container table-responsive-xxl">
    <div class="text-xl flex justify-between font-semibold bg-gray-200 w-full py-3 rounded rounded-xl px-4">
        Taux
    </div>
    <p>Taux de presence de 50 % </p>
    <p>Taux de presence de 50 % </p>
</div> --}}

<div class="w-full p-4 bg-white rounded-md shadow-sm xl:container table-responsive-xxl">
    <div class="text-xl flex justify-between bg-gray-200 w-full py-3 rounded rounded-xl px-4">
        Evaluation du chiffres d'affaires
    </div>
    @if (count($results['ca']))
        <table class="table align-middle table-striped caption-top table-hover">
            <thead class="table-light">
                <tr class="!text-2xl text-gray-800">
                    <th></th>
                    @foreach ($months as $month)
                        <th scope="col">{{ $month }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody id="showResult">
                @foreach ($results['ca'] as $result)
                    <tr class="text-lg text-gray-600">
                        <td>{{ $result['year']}}</td>
                        @foreach ($result['ca_customer'] as $customer)
                            <td>{{number_format($customer['total_ttc'], 2, ".", " ")}} Ar</td>
                        @endforeach
                        <td>{{number_format($result['total'], 2, ".", " ")}} Ar</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Aucun</p>
    @endif
</div>

<div class="w-full p-4 bg-white rounded-md shadow-sm xl:container table-responsive-xxl">
    <div class="text-xl flex justify-between bg-gray-200 w-full py-3 rounded rounded-xl px-4">
        Nombre de projet ({{ $total_project }})
    </div>
    @if (count($results['count_projects']))
        <table class="table align-middle table-striped caption-top table-hover">
            <thead class="table-light">
                <tr class="!text-2xl text-gray-800">
                    <th></th>
                    @foreach ($months as $month)
                        <th scope="col">{{ $month }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody id="showResult">
                @foreach ($results['count_projects'] as $result)
                    <tr class="text-lg text-gray-600">
                        <td>{{ $result['year']}}</td>
                        @foreach ($result['projects'] as $project)
                            <td>{{ $project['nb_project'] }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Aucun</p>
    @endif
</div>

<div class="w-full p-4 bg-white rounded-md shadow-sm xl:container table-responsive-xxl">
    <div class="text-xl bg-gray-200 w-full py-3 rounded rounded-xl px-4">
        Nombre d'apprenant ({{$total_learner}})
    </div>
    @if (count($results['learners']))
        <table class="table align-middle table-striped caption-top table-hover">
            <thead class="table-light">
                <tr class="!text-2xl text-gray-800">
                    <th></th>
                    @foreach ($months as $month)
                        <th scope="col">{{ $month }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody id="showResult">
                @foreach ($results['learners'] as $result)
                    <tr class="text-lg text-gray-600">
                        <td>{{ $result['year']}}</td>
                        @foreach ($result['learners'] as $learner)
                            <td>{{ $learner['nb_learner'] }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Aucun</p>
    @endif
</div>

<div class="w-full p-4 bg-white rounded-md shadow-sm xl:container table-responsive-xxl">
    <div class="text-xl flex justify-between bg-gray-200 w-full py-3 rounded rounded-xl px-4">
        Historique des projet
    </div>
    @foreach ($results['story_projects'] as $st_project)
        <p class="text-xl underline text-center"> {{ $st_project['year']}} </p>
        @if (count($st_project['story_projects']) > 0)
             @foreach ($st_project['story_projects'] as $story)
                @if (count($story['projects']) > 0)
                    <p class="underline"> {{ $story['month'] }} </p>
                    <table class="table align-middle caption-top table-hover my-3">
                        <thead class="table-light">
                            <tr class="!text-2xl text-gray-800">
                                <th scope="col"></th>
                                <th scope="col">Projet</th>
                                <th scope="col">Type</th>
                                <th scope="col">Date debut</th>
                                <th scope="col">Date fin</th>
                                <th scope="col">Prix</th>
                                <th scope="col">Detail</th>
                            </tr>
                        </thead>
                        <tbody id="showResult">
                            @foreach ($story['projects'] as $key => $proj)
                                <tr class="text-lg text-gray-600">
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $proj->module_name}}</td>
                                    <td>{{ $proj->project_type}}</td>
                                    <td>{{ $proj->dateDebut}}</td>
                                    <td>{{ $proj->dateFin}}</td>
                                    <td>{{number_format($proj->total_ttc, 2, ".", " ")}} Ar</td>
                                    <td> 
                                        <a href="/cfp/projets/{{ $proj->idProjet }}/detail">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            @endforeach
        @else
            <p class="mb-3 ml-1">Aucun</p>
        @endif
       
    @endforeach
</div>