@extends('layouts.masterAdmin')

@section('content')
  <div class="w-full flex flex-col justify-center items-center ">
    <div class="w-full h-[64px] flex justify-start items-center bg-gray-100/50">
      {{-- Menu Title --}}
      <div class="w-1/3 inline-flex items-center gap-2 px-3">
        <i class="fa-solid fa-users 2xl:text-3xl md:text-2xl text-gray-600"></i>
        <label class="2xl:text-2xl md:text-xl text-gray-500 font-semibold">Listes des utilisateurs</label>
      </div>
      {{-- fin menu title --}}
      <div class="flex w-1/3 justify-center items-center">
        {{-- BARRE DE RECHERCHE  --}}
        <div class="w-[70%] h-12 mx-2 flex flex-col justify-center items-start m-2 ml-[40px]">
          <div
            class="flex flex-row justify-center items-center w-full h-[42px] shadow-sm bg-white border-[1px] border-gray-300 gap-3 rounded-full outline-none hover:border-purple-400 focus:border-purple-500 focus:bg-white transition-all duration-300">
            <button id="boutonRecherche"
              class="h-full w-[80px] cursor-pointer rounded-l-full hover:bg-gray-100 text-gray-500 flex justify-center items-center">
              <i class="fa-solid fa-magnifying-glass text-2xl"></i>
            </button>
            <input id="search-input" type="text"
              class="w-full h-full text-gray-500 text-center text-lg focus-visible:outline-none rounded-lg bg-transparent outline-none"
              placeholder="Rechercher un utilisateur...">
            <div class="relative inline-block text-left group my-2">
              <button id="delete-button"
                class="w-[30px] h-[30px] m-2 text-center text-gray-500 rounded-full hover:text-gray-500 hover:bg-gray-100 bg-inherit flex justify-center items-center">
                <i class="fa-solid fa-xmark text-2xl "></i>
              </button>
              <div
                class="hidden group-hover:block absolute z-10 top-15 left-1/2 transform -translate-x-1/2 w-42 text-center bg-blue-50 text-xs rounded-md">
                <!-- Votre contenu popover ici -->
                <p class="px-2 py-2 text-center text-gray-500">Supp.</p>
              </div>
            </div>
            <div class="relative inline-block text-left group my-2">
              <button
                class="w-[30px] h-[30px] m-2 text-center text-gray-500 rounded-full hover:text-purple-500 hover:bg-purple-100 bg-inherit flex justify-center items-center transition duration-150"
                href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" id="ouvrirModal8">
                <i class="fa-solid fa-sliders text-2xl"></i>
              </button>
              <div
                class="hidden group-hover:block absolute z-10 top-15 left-1/2 transform -translate-x-1/2 w-42 text-center bg-blue-50 text-xs rounded-md">
                <!-- Votre contenu popover ici -->
                <p class="px-2 py-2 text-center text-gray-500">Filtrer</p>
              </div>
            </div>
          </div>
        </div>
        {{-- FIN DE BARRE RECHERCHE  --}}
      </div>
      <div class="w-1/3">
        {{-- Modal export --}}
        <div class="w-full h-full flex justify-end items-center px-4">
          <button
            class="flex items-center justify-center w-1/2 px-3 py-2 text-sm text-gray-500 bg-white transition duration-200 border rounded-lg gap-2 sm:w-auto"
            data-bs-container="body" data-bs-popover="popover" data-bs-custom-class="tooltip-hors-ligne"
            data-bs-placement="top" data-bs-content="Exporter la table" data-bs-trigger="hover" data-bs-toggle="modal"
            data-bs-target="#btn-offline1{{-- $m->idModule --}}" id="myButton">
            <i class="fa-solid fa-arrow-down text-xl text-gray-500"></i>
            <span>Exporter</span>
          </button>

          <!-- Modal -->
          <div class="modal fade" id="btn-offline1{{-- $m->idModule --}}" data-bs-backdrop="static"
            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div
                class="modal-content bg-white border-none h-[300px] w-[430px] justify-start items-center gap-4 rounded-xl">
                <div
                  class="w-full h-auto py-3 px-4 flex flex-row justify-between items-center text-white bg-gray-300 rounded-t-xl">
                  <p class="text-2xl font-semibold">Exporter</p>
                  <button type="button"
                    class="w-[28px] h-[28px] text-white hover:bg-red-700 rounded-full flex justify-center items-center"
                    data-bs-dismiss="modal" data-bs-dismiss="tooltip">
                    <i
                      class="fa-solid fa-xmark text-white text-2xl hover:text-red-800 scale-95 hover:scale-100 rounded-full py-2 transition duration-200"></i></button>
                </div>
                <p class="text-xl text-gray-600 text-center px-4">Vous allez télécharger ce liste sous format
                <div class="w-full flex justify-center items-center">
                  <div class="w-1/2">
                    <div class="flex justify-start items-center px-5">
                      <div class="rounded w-[65px] h-[65px] flex justify-center items-center">
                        <img src="{{ asset('img/file.jpg') }}" alt="">
                      </div>
                    </div>
                    <span class="flex flex-row gap-2 w-full px-5">
                      <i class="fa-regular fa-file text-xl text-gray-600"></i>
                      <p class="flex text-gray-500 font-semibold px-2">PDF/EXCEL</p>
                    </span>
                  </div>
                  <div class="w-1/2 flex flex-col justify-start items-center px-4 gap-3 mt-4">
                    <button
                      class="flex items-center justify-center w-1/2 px-5 py-2 text-sm text-gray-500 bg-gray-100/50 scale-95 hover:scale-100 transition duration-200 border rounded-lg gap-2 sm:w-auto"><i
                        class="bi bi-cloud-arrow-down text-2xl text-gray-500"></i>
                      <span> PDF (1MB)</span>
                    </button>
                    <button
                      class="flex items-center justify-center w-1/2 px-5 py-2 text-sm text-gray-500 bg-gray-100/50 scale-95 hover:scale-100 transition duration-200 border rounded-lg gap-2 sm:w-auto"><i
                        class="bi bi-cloud-arrow-down text-2xl text-gray-500"></i>
                      <span> XLS (2MB)</span>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        {{-- FIN --}}
      </div>
    </div>

    {{-- Toggle --}}
    <div class="card border-gray-100 flex w-full justify-center items-start rounded bg-gray-50 px-4 py-4 mt-3">
      <span class="flex flex-row gap-3 text-xl text-gray-500">
        <i class="fa-solid fa-chart-pie text-gray-500 text-xl"></i>
        Statistiques des utilisateurs
      </span>
      <div class="accordion-content" style="display: none;">
        <!-- Content for card 1 -->
        <div class="w-full flex flex-row justify-start items-center m-4">
          <div class="flex justify-start items-center w-1/2 h-auto pl-12 gap-4">
            <div class=" w-full flex flex-row gap-6 justify-center items-start">
              <div
                class="flex flex-1 h-[90px] w-[240px]  card-container bg-white border-gray-100 shadow-sm justify-center items-center gap-2 text-gray-500 rounded-md"
                data-target="container1">
                <p class="flex text-2xl font-semibold text-pink-600">30</p>
                Stagiaires
              </div>
              <div
                class="flex flex-1 h-[90px] w-[240px] card-container bg-white border-gray-100 shadow-sm justify-center items-center gap-2 text-gray-500 rounded-md"
                data-target="container2">
                <p class="flex text-2xl font-semibold text-pink-600">80</p>
                Réferents
              </div>
              <div
                class="flex flex-1 h-[90px] w-[240px] card-container bg-white border-gray-100 shadow-sm justify-center items-center gap-2 text-gray-500 rounded-md"
                data-target="container3">
                <p class="flex text-2xl font-semibold text-pink-600">30</p>
                Employées
              </div>
            </div>
          </div>
        </div>
        {{-- Fin Chart --}}
      </div>
    </div>
    {{-- Fin toggle --}}

    {{-- Début tableau --}}
    <div class="flex justify-center items-center mt-2 w-full mx-2">
      @if (count($users) <= 0)
        <div class="flex w-full justify-center items-center">
          <div class="flex flex-col justify-center items-center w-[240px] h-auto text-gray-100">
            <div id="lottie-animation-container" class="justify-center text-gray-200"></div>.
          </div>
        </div>
      @else
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50 y-800">
            <tr>
              <th scope="col" class="py-3.5 px-4 text-gray-500">
                <button class="flex items-center gap-x-3 focus:outline-none">
                  <span>Nom</span>
                  <svg class="h-3" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M2.13347 0.0999756H2.98516L5.01902 4.79058H3.86226L3.45549 3.79907H1.63772L1.24366 4.79058H0.0996094L2.13347 0.0999756ZM2.54025 1.46012L1.96822 2.92196H3.11227L2.54025 1.46012Z"
                      fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                    <path
                      d="M0.722656 9.60832L3.09974 6.78633H0.811638V5.87109H4.35819V6.78633L2.01925 9.60832H4.43446V10.5617H0.722656V9.60832Z"
                      fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                    <path
                      d="M8.45558 7.25664V7.40664H8.60558H9.66065C9.72481 7.40664 9.74667 7.42274 9.75141 7.42691C9.75148 7.42808 9.75146 7.42993 9.75116 7.43262C9.75001 7.44265 9.74458 7.46304 9.72525 7.49314C9.72522 7.4932 9.72518 7.49326 9.72514 7.49332L7.86959 10.3529L7.86924 10.3534C7.83227 10.4109 7.79863 10.418 7.78568 10.418C7.77272 10.418 7.73908 10.4109 7.70211 10.3534L7.70177 10.3529L5.84621 7.49332C5.84617 7.49325 5.84612 7.49318 5.84608 7.49311C5.82677 7.46302 5.82135 7.44264 5.8202 7.43262C5.81989 7.42993 5.81987 7.42808 5.81994 7.42691C5.82469 7.42274 5.84655 7.40664 5.91071 7.40664H6.96578H7.11578V7.25664V0.633865C7.11578 0.42434 7.29014 0.249976 7.49967 0.249976H8.07169C8.28121 0.249976 8.45558 0.42434 8.45558 0.633865V7.25664Z"
                      fill="currentColor" stroke="currentColor" stroke-width="0.3" />
                  </svg>
                </button>
              </th>
              <th scope="col" class="px-12 py-3.5 text-base font-normal text-center rtl:text-right text-gray-700">
                Organisme
              </th>
              <th scope="col" class="px-12 py-3.5 text-base font-normal text-center rtl:text-right text-gray-700">
                Status
              </th>
              <th scope="col" class="px-12 py-3.5 text-base font-normal text-center rtl:text-right text-gray-700">
                Matricules
              </th>
              <th scope="col" class="px-12 py-3.5 text-base font-normal text-center rtl:text-right text-gray-700">
                Adresse
              </th>
              <th scope="col" class="px-12 py-3.5 text-base font-normal text-center rtl:text-right text-gray-700">
                E-mail
              </th>
              <th scope="col" class="px-12 py-3.5 text-base font-normal text-center rtl:text-right text-gray-700">
                Numéro
              </th>
              <th scope="col" class="px-12 py-3.5 text-base font-normal text-center rtl:text-right text-gray-700">
                Actions
              </th>
            </tr>
          </thead>

          <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($users as $usr)
              <tr>
                <td class="px-4 text-sm font-medium whitespace-nowrap">
                  <div>
                    <h2 class="font-medium text-gray-800">{{ $usr->name }}</h2>
                  </div>
                </td>
                <td class="px-6 py-1 text-sm font-medium whitespace-nowrap">
                  <div class="w-full h-auto flex flex-col justify-center items-center">
                    <img src="{{ $usr->photo }}" alt="logo_Etp" class="w-[72px] h-[32px]">
                    <p class="text-sm font-normal text-gray-600">{{ $usr->fonction }}</p>
                  </div>
                </td>
                <td class="px-12 py-1 text-sm font-medium whitespace-nowrap">
                  {{-- Avatar --}}
                  <div class="avatar-containerw-full h-auto flex justify-center items-center">
                    <div class="avatar" id="avatar">
                      <img
                        src="https://images.unsplash.com/photo-1633332755192-727a05c4013d?ixlib=rb-1.2.1&amp;ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;auto=format&amp;fit=crop&amp;w=1480&amp;q=80"
                        alt="Avatar">
                    </div>
                  </div>
                  {{-- Fin Avatar --}}
                </td>
                <td class="px-4 py-1 text-sm whitespace-nowrap">
                  <div class="h-auto flex justify-center items-center">
                    <h4>{{ $usr->matricule }}</h4>
                  </div>
                </td>
                <td class="px-4 py-1 text-sm whitespace-nowrap">
                  <div class="h-auto flex justify-center items-center">
                    {{ $usr->adresse }}
                  </div>
                </td>

                <td class="px-4 py-1 text-sm whitespace-nowrap">
                  <div class="h-auto flex justify-center items-center">
                    <p>
                      {{ $usr->email }}
                    </p>
                  </div>
                </td>
                <td class="px-4 py-1 text-sm whitespace-nowrap">
                  <div class="h-auto flex justify-center items-center">
                    <p>
                      {{ $usr->phone }}
                    </p>
                  </div>
                </td>
                <td class="p-2 text-center">
                  {{-- EXEMPLE DE MODAL  --}}
                  <div class="w-full h-full flex justify-center items-center p-2">
                    {{-- @if ($m->moduleStatut == 1) --}}
                    <button
                      class="px-2 py-2 border-[1px] border-red-500 bg-red-100 hover:bg-red-500 hover:text-white text-red-500 text-sm rounded"
                      data-bs-container="body" data-bs-popover="popover" data-bs-custom-class="tooltip-hors-ligne"
                      data-bs-placement="top" data-bs-content="Mettre hors ligne" data-bs-trigger="hover"
                      data-bs-toggle="modal" data-bs-target="#btn-offline{{-- $m->idModule --}}" id="myButton">
                      Bloquer
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="btn-offline{{-- $m->idModule --}}" data-bs-backdrop="static"
                      data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                      aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered">
                        <div
                          class="modal-content bg-white border-none h-[300px] w-[430px] justify-center gap-2 rounded-xl"
                          id="lottieAnimation">
                          <div class="p-3 flex flex-col rounded items-center">

                            <lottie-player src="{{ asset('Animations/CautionRed.json') }}" background="transparent"
                              speed="1" style="width: 200px; height: 100px;" loop autoplay></lottie-player>
                            <h1 class="text-red-600 text-3xl font-semibold flex flex-1" id="staticBackdropLabel">
                              Bloquer
                            </h1>
                          </div>
                          <p class="text-xl text-gray-600 text-center px-4">Vous allez bloquer cet utilisateur du
                            logiciel. Êtes-vous
                            sûr de vouloir le bloquer?</p>
                          <div class="p-3 inline-flex gap-3 justify-center">
                            <button type="button"
                              class="border-custom-offLine border-red-500 text-red-700 text-lg hover:text-red-800 scale-95 hover:scale-100 rounded-full px-4 py-2 transition duration-200"
                              data-bs-dismiss="modal" data-bs-dismiss="tooltip">Non,
                              annuler</button>
                            <button type="button"
                              class="bg-red-700 text-white text-lg rounded-full px-4 py-2 scale-95 hover:scale-100 hover:bg-red-800 transition duration-200"
                              onclick='manageModule("patch", "/modules/{{-- $m->idModule --}}/makeOffline")'
                              data-bs-dismiss="modal">Oui,
                              Bloquer</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  {{-- FIN EXEMPLE --}}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    </div>
@endsection

@push('custom_style')
  <style>
    .avatar-container {
      position: relative;
      display: inline-block;
    }

    .avatar {
      position: relative;
      width: 50px;
      height: 50px;
      overflow: hidden;
      border-radius: 50%;
    }

    .avatar img {
      width: 100%;
      height: auto;
      display: block;
      transition: transform 0.3s ease-in-out;
    }

    .popover {
      display: none;
      position: absolute;
      bottom: calc(100% + 10px);
      left: 50%;
      transform: translateX(-50%);
      background-color: #fff;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
      padding: 10px;
      border-radius: 5px;
      z-index: 1;
    }

    .avatar:hover img {
      transform: scale(1.1);
    }

    .avatar:hover .popover {
      display: block;
    }
  </style>
@endpush

@section('script')
  <script>
    $(document).ready(function() {
      // Initialize Bootstrap popover for each element with class "popover-trigger"
      $('.popover-trigger').popover({
        placement: 'top',
        html: true,
        content: function() {
          // Get the content specific to this element using data-content-id
          var popoverContentId = $(this).data('content-id');
          return $('#' + popoverContentId).html();
        }
      });

      // Gérer le clic sur une carte
      $(".card-container").on("click", function() {
        // Masquer tous les conteneurs
        $(".container").hide();

        // Afficher le conteneur associé à la carte cliquée
        var targetContainer = $(this).data("target");
        $("#" + targetContainer).show();
      });
      //Accordion
      $(".card").click(function() {
        $(this).find(".accordion-content").slideToggle(200); // Adjust the speed here (e.g., 300 milliseconds)
      });
    });
  </script>
@endsection
