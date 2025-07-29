@extends('layouts.masterParticulier')

@section('content')
  <div class="w-full flex flex-col max-h-[calc(100vh- 100px)] h-full">
    <x-sub-part label="Projet" icon="tarp">
    </x-sub-part>

    @if ($projetCount <= 0)
      <x-no-data texte="Vous n'avez pas encore de projet"></x-no-data>
    @else
      <div class="w-full max-w-screen-xl h-full mx-auto">
        <section id="filterSection" class="my-2 p-2">
          <div class="flex flex-col">
            <h1 class="text-3xl text-gray-700">Vous avez <span class="font-bold text-3xl">{{ $projetCount }} Projets</span>
            </h1>

            {{-- Filtre --}}
            @include('Particuliers.filtres.projets')
          </div>
        </section>

        <section id="content" class="mt-4 p-2">
          <div class="showResult flex flex-col w-full gap-2 mt-2 mb-4">
              @if (count($projectDates) > 0)
                @foreach ($projectDates as $projectDate)
                  <button
                    class="accordion text-3xl font-extrabold text-gray-700 my-2 uppercase w-full text-left px-4 py-2 bg-gray-50 hover:bg-gray-100 duration-200 cursor-pointer">
                    @if (isset($projectDate->headDate))
                      {{ $projectDate->headDate }}
                    @else
                    @endif
                  </button>
                  <ul class="w-full flex flex-col gap-3 mt-4">
                    @foreach ($projets as $p)
                      @if ($projectDate->headDate == $p['headDate'])
                        <div class="min-[350px]:hidden md:hidden lg:hidden xl:block">
                          @include('Particuliers.projets.projectList')
                        </div>
                        <div class="min-[350px]:hidden md:hidden lg:block xl:hidden">
                          @include('Particuliers.projets.projectListMd')
                        </div>
                        <div class="min-[350px]:block lg:hidden xl:hidden">
                          @include('Particuliers.projets.projectListSm')
                        </div>
                      @endif
                    @endforeach
                  </ul>
                @endforeach
              @endif
          </div>
        </section>
      </div>
    @endif
  </div>
@endsection

@push('custom_style')
  <style>
    .applyBtn {
      background: #5B5966;
      border: none;
    }

    .applyBtn:hover {
      background: #4b4a53;
      border: none;
    }
  </style>
@endpush

@section('script')
  <script src="{{ asset('js/filter/newFilter.js') }}"></script>
@endsection
