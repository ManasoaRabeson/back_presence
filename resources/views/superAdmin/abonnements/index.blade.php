@extends('layouts.masterAdmin')

@section('content')
    <div class="flex flex-col w-full bg-white">
        <div>
            <x-sub-super icon="money-check-dollar" label="CRUD Abonnement">
                <x-v-separator />
                <div class="flex items-center gap-2">
                    <i class="text-gray-700 fa fa-solid fa-plus"></i>
                    <a href="{{ route('crudAbn.create') }}" class="text-gray-700">Ajouter un Plan/Feature</a>
                </div>
                <x-v-separator />
                <div class="flex items-center gap-2">
                    <i class="text-gray-700 fa fa-solid fa-table-columns"></i>
                    <a href="{{ route('crudAbn.card') }}" class="text-gray-700">vue card</a>
                </div>
            </x-sub-super>
        </div>
        <div class="container w-full p-4 mx-auto mt-10">
            @include('superAdmin.abonnements.pages.tab')
            <div id="plans">
                <x-table titre="Liste de vos Plans" className="min-w-[800px] overflow-x-auto">
                    <thead>
                        <x-tr class="border-b-2">
                            <x-th>Nom du plan</x-th>
                            <x-th>Pour</x-th>
                            <x-th>Prix</x-th>
                            <x-th>Dédier</x-th>
                            <x-th>Description</x-th>
                            <x-th>Durée d'abonnement</x-th>
                            <x-th class='text-right'>Actions</x-th>
                        </x-tr>
                    </thead>
                    <tbody>
                        @if (count($plans) <= 0)
                            <x-td>
                                <x-no-data texte="Pas de plan" />
                            </x-td>
                        @else
                            @foreach ($plans as $plan)
                                <x-tr>
                                    <x-td class="uppercase">{{ $plan->name }}</x-td>
                                    <x-td class="capitalize">{{ $plan->user_type }}</x-td>
                                    <x-td>{{ $plan->price }}</x-td>
                                    <x-td>{{ $plan->dedicate }}</x-td>
                                    <x-td>{{ $plan->description }}</x-td>
                                    <x-td>{{ $plan->invoice_period }} {{ $plan->invoice_interval }}</x-td>
                                    <x-td>
                                        <div class="inline-flex justify-end w-full">
                                            <x-btn-table type="simple" first="Edit"
                                                route="{{ route('crudAbn.editPlan', $plan->id) }}">
                                                <x-li-drop action="{{ route('crudAbn.destroy', $plan->id) }}"
                                                    type="buttonDelete" titre="Supprimer" />
                                            </x-btn-table>
                                        </div>
                                    </x-td>
                                </x-tr>
                            @endforeach
                        @endif
                    </tbody>
                </x-table>
            </div>
            <div id="features"></div>

        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/alpine.min.js') }}" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.7.7/lottie.min.js"></script>
    <script>
        function displayPlan() {
            $('#tabPlan').addClass('bg-white');
            $('#tabFeature').removeClass('bg-white');
            $('#features').empty();
            $('#plans').empty();
            $('#plans').append(`<x-table titre="Liste de vos Plans" className="min-w-[800px] overflow-x-auto">
                                <thead>
                                    <x-tr class="border-b-2">
                                        <x-th>Nom du plan</x-th>
                                        <x-th>Prix</x-th>
                                        <x-th>Dédier</x-th>
                                        <x-th>Description</x-th>
                                        <x-th>Durée d'abonnement</x-th>
                                        <x-th class='text-right'>Actions</x-th>
                                    </x-tr>
                                </thead>
                                <tbody>
                                    @if (count($plans) <= 0)
                                    <x-td>
                                        <x-no-data texte="Pas de plan" />
                                    </x-td>
                                    @else
                                    @foreach ($plans as $plan)
                                    <x-tr>
                                        <x-td>{{ $plan->name }}</x-td>
                                        <x-td>{{ $plan->price }}</x-td>
                                        <x-td>{{ $plan->dedicate }}</x-td>
                                        <x-td>{{ $plan->description }}</x-td>
                                        <x-td>{{ $plan->invoice_period }} {{ $plan->invoice_interval }}</x-td>
                                        <x-td>
                                            <div class="inline-flex justify-end w-full">
                                                <x-btn-table type="simple" first="Edit" route="{{ route('crudAbn.editPlan', $plan->id) }}">
                                                    <x-li-drop action="{{ route('crudAbn.destroy', $plan->id) }}" type="buttonDelete" titre="Supprimer" />
                                                </x-btn-table>
                                            </div>
                                        </x-td>
                                    </x-tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </x-table>`);
        }

        function displayFeature() {
            $('#tabFeature').addClass('bg-white');
            $('#tabPlan').removeClass('bg-white');
            $('#plans').empty();
            $('#features').empty();
            $('#features').append(`<x-table titre="Liste de vos Features" className="min-w-[800px] overflow-x-auto">
                                    <thead>
                                        <x-tr>
                                            <x-th>Plan</x-th>
                                            <x-th>Nom de Feature</x-th>
                                            <x-th>Valeur</x-th>
                                            <x-th class='text-right'>Actions</x-th>
                                        </x-tr>
                                    </thead>
                                    <tbody>
                                        @if (count($features) <= 0)
                                        <x-td>
                                            <x-no-data texte="Pas de features" />
                                        </x-td>
                                        @else
                                        @foreach ($features as $feature)
                                        <x-tr>
                                            <x-td>{{ $feature->plan->name }}</x-td>
                                            <x-td>{{ $feature->name }}</x-td>
                                            <x-td>{{ $feature->value }}</x-td>
                                            <x-td>
                                                <div class="inline-flex justify-end w-full">
                                                    <x-btn-table type="simple" first="Edit" route="{{ route('crudAbn.editFeature', $feature->id) }}">
                                                        <x-li-drop action="{{ route('crudAbn.destroy', $feature->id) }}" type="buttonDelete" titre="Supprimer" />
                                                    </x-btn-table>
                                                </div>
                                            </x-td>
                                        </x-tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </x-table>`);
        }
    </script>
@endsection
