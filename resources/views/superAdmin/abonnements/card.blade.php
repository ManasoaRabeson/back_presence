@extends('layouts.masterAdmin')

@section('content')
  @php
    $colors = ['#00BC8F', '#864DFF', '#e59c14', '#42b1b5'];
    $groupedPlans = [
        'centre de formation' => [],
        'entreprise' => [],
    ];

    // Grouper les plans par user_type
    foreach ($plans as $plan) {
        if (isset($plan->user_type)) {
            $groupedPlans[$plan->user_type][] = $plan;
        }
    }
  @endphp
  <div class="w-full flex flex-col mt-4">
    <div>
      <x-sub-super icon="money-check-dollar" label="CRUD Abonnement">
        <x-v-separator />
        <div class="flex gap-2 items-center">
          <i class="fa fa-solid fa-plus text-gray-700"></i>
          <a href="{{ route('crudAbn.create') }}" class="text-gray-700">Ajouter un Plan/Feature</a>
        </div>
        <x-v-separator />
        <div class="flex gap-2 items-center">
          <i class="fa fa-solid fa-table-list text-gray-700"></i>
          <a href="{{ route('crudAbn.index') }}" class="text-gray-700">Vue tableau</a>
        </div>
      </x-sub-super>
    </div>
    <div class="container mt-2">
      @if (count($plans) <= 0)
        <x-no-data texte="Vous n'avez pas de plan pour l'instant" />
      @else
        @foreach ($groupedPlans as $userType => $plans)
          <h2 class="text-xl text-gray-600 font-semibold mt-4 uppercase">{{ $userType }}</h2>
          <div class="flex flex-wrap gap-4 items-center justify-center w-full">
            @foreach ($plans as $index => $plan)
              @include('superAdmin.abonnements.card-abn', [
                  'color' => $colors[$index % count($colors)],
              ])
            @endforeach
          </div>
        @endforeach
      @endif
    </div>
  </div>
@endsection
