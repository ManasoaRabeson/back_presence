@extends($extends_containt)


@section('content')
    <style>
        .contenuhidden {
            display: none;
        }

        .active-tab {
            background-color: #0056d2;
            color: white;
        }

        .session label {
            cursor: pointer;
        }

        .details .hidden {
            display: none;
        }

        .details {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px;
            margin-top: 16px;
            background-color: #f9fafb;
        }

        .session {
            background-color: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>

    <div class="bg-[#f3f4f6]">

        <div
            class="container mx-auto relative flex flex-col lg:flex-row py-10 px-10 gap-12 lg:mx-auto w-full items-center justify-center px-6 lg:px-0">
            <div class="basis-2/3">
                <div class="px-12 py-6">
                    <p class="text-4xl font-bold p-2">Using Basic Formulas and Functions in Microsoft Excel - Niveau 1</p>
                    <p class="mt-2 p-2"><i class="fa-solid fa-language mr-2"></i> Cours en français</p>
                    <div class="flex mt-2">
                        <div class="flex-none w-14">
                            <p><img class="h-full rounded-full"
                                    src="https://d3njjcbhbojbot.cloudfront.net/api/utilities/v1/imageproxy/https://coursera-instructor-photos.s3.amazonaws.com/54/2dcb0c179d450c8d22f8add4761d16/Paula.jpg?auto=format%2Ccompress&dpr=1&w=75&h=75&fit=crop"
                                    alt="Image formateur"> </p>
                        </div>
                        <div class="flex-auto w-64 p-3 ml-2">
                            <p>Instructeur : <strong>Koto Randriabema</strong></p>
                        </div>
                    </div>
                    <img />
                </div>
            </div>

            <div class="basis-1/3 hidden lg:block">
                <img class=""
                    src="https://images.ctfassets.net/2pudprfttvy6/2PTfJx9KtWYo151cjeiErm/bbf36a34e42b435921f8162fbf8afccb/iStock-1148394694__1___1_.jpg"
                    alt="Apprenants" />
            </div>
        </div>

    </div>


    <div class="container mx-auto flex flex-col lg:flex-row gap-10 py-10 px-6 lg:px-0 w-full items-start justify-center">


        {{-- Div 1 mila script --}}
        <div
            class="order-1 lg:order-2 basis-full lg:basis-1/3 border border-1 border-[#e8eef7] bg-white rounded-lg shadow-lg p-6 w-full lg:w-1/3 lg:self-start">
            <div class="flex justify-between items-center mb-4">

                <div class="w-full max-w-lg mx-auto">

                    <div class="flex flex-row justify-between mb-4">
                        <button id="tab1"
                            class="basis-1/2 px-6 py-3 bg-gray-200 text-gray-800 font-semibold text-sm rounded-l active-tab">
                            INTER
                        </button>
                        <button id="tab2"
                            class="basis-1/2 px-6 py-3 bg-gray-200 text-gray-800 font-semibold text-sm rounded-r">
                            SUR MESURE
                        </button>
                    </div>

                    <div class="bg-white border border-1 border-[#e8eef7] p-6 rounded-lg">
                        <div id="content1" class="tab-content">

                            <div class="flex items-center justify-between w-full border-b border-gray-300 p-4">
                                <div class="flex items-center space-x-2 w-1/2">
                                    <i class="fa-solid fa-location-dot text-[#868c96]"></i>
                                    <p class="text-gray-700">Lieu</p>
                                </div>

                                <div class="relative w-1/2">
                                    <p class="text-gray-700 font-semibold text-xl">
                                        Numerika Analamahitsy
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between w-full border-b border-gray-300 p-4">
                                <div class="flex items-center space-x-2 w-1/2">
                                    <i class="fa-solid fa-clock text-[#868c96]"></i>
                                    <p class="text-gray-700">Durée</p>
                                </div>

                                <div class="relative w-1/2">
                                    <p class="text-gray-700 font-semibold text-xl">
                                        3 jours | 32 heures
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between w-full border-b border-gray-300 p-4">
                                <div class="flex items-center space-x-2 w-1/2">
                                    <i class="fa-solid fa-money-bill text-[#868c96]"></i>
                                    <p class="text-gray-700">Prix</p>
                                </div>

                                <div class="relative w-1/2">
                                    <p class="text-gray-700 font-semibold text-xl">
                                        15 000 Ar/personne
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between w-full border-b border-gray-300 p-4">
                                <div class="flex items-center space-x-2 w-1/2">
                                    <i class="fa-solid fa-people-group text-[#868c96]"></i>
                                    <p class="text-gray-700">Place</p>
                                </div>

                                <div class="relative w-1/2">
                                    <p class="text-gray-700 font-semibold text-xl">
                                        12 personnes
                                    </p>
                                </div>
                            </div>

                            <div class="flex justify-center">
                                <button
                                    class="bg-[#3EB489] text-[#ffffff] py-3 rounded-lg mt-6 w-full font-semibold text-base">Choisir
                                    une session</button>
                            </div>
                        </div>

                        <div id="content2" class="tab-content contenuhidden">
                            <p class="font-bold text-center px-6 py-2 mb-1">FORMATION À LA DEMANDE</p>
                            <div class="bg-[#ffffff] text-center p-6 rounded-lg">
                                <p class="text-base">Cette thématique vous intéresse ?
                                    Nos experts conçoivent votre formation
                                    sur-mesure !</p>
                                <button class="bg-[#FF0000] text-[#ffffff] font-semibold px-6 py-2 rounded-lg mt-4"><a
                                        href="{{ route('contact.formafusion') }}">Nous contacter</a></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Script du div 1 --}}
        <script>
            const tab1 = document.getElementById('tab1');
            const tab2 = document.getElementById('tab2');
            const content1 = document.getElementById('content1');
            const content2 = document.getElementById('content2');

            tab1.addEventListener('click', function() {
                tab1.classList.add('active-tab');
                tab2.classList.remove('active-tab');
                content1.classList.remove('contenuhidden');
                content2.classList.add('contenuhidden');
            });

            tab2.addEventListener('click', function() {
                tab2.classList.add('active-tab');
                tab1.classList.remove('active-tab');
                content2.classList.remove('contenuhidden');
                content1.classList.add('contenuhidden');
            });
        </script>


        <div
            class="order-2 lg:order-1 basis-full lg:basis-2/3 w-full lg:w-2/3 border border-1 border-[#e8eef7] rounded-lg shadow-lg">
            <div class="px-12 py-8">

                {{-- Div 2 mila script  --}}
                <div class="border border-1 border-[#e8eef7] rounded-lg px-8 py-8 mt-4">

                    <p class="text-gray-700 font-bold text-xl">À propos de ce cours</p>

                    <p id="textContent" class="text-justify overflow-hidden max-h-24 mt-4">
                        Cette formation mène à un Certificat en option : Directeur informatique (Réf. 9375). <br><br>
                        Cette formation donne les clés pour maîtriser toutes les dimensions du métier de Directeur des
                        Systèmes d'information : organiser l'activité, mettre en place et sécuriser les processus, gérer les
                        budgets, manager les collaborateurs. <br><br>
                        Pour décliner la stratégie de l’entreprise, le responsable informatique pilote des équipes
                        d’horizons très différents. Véritable chef d’orchestre, il organise l’activité et fédère les hommes
                        qu’il supervise. Il coordonne l’ensemble des domaines, optimise la gestion des processus, gère un
                        budget et fixe les priorités. Il doit veiller aux bons développements et assurer une exploitation du
                        SI satisfaisante pour tous les utilisateurs. <br><br>
                        Cette formation donne les clés pour maîtriser toutes les dimensions de ce métier.
                    </p>

                    <a id="toggleLink" class="text-blue-500 cursor-pointer mt-2 block">Voir plus</a>
                </div>

                {{-- Script du div 2 --}}
                <script>
                    const textContent = document.getElementById('textContent');
                    const toggleLink = document.getElementById('toggleLink');

                    let isExpanded = false; // Variable pour suivre l'état du texte (réduit ou développé)

                    toggleLink.addEventListener('click', () => {
                        if (isExpanded) {
                            // Réduire le texte
                            textContent.classList.add('max-h-24', 'overflow-hidden');
                            toggleLink.textContent = 'Voir plus';
                        } else {
                            // Développer le texte
                            textContent.classList.remove('max-h-24', 'overflow-hidden');
                            toggleLink.textContent = 'Voir moins';
                        }
                        isExpanded = !isExpanded; // Inverser l'état
                    });
                </script>

                <div class="bg-[#f3f4f6] p-4 mt-10 rounded-lg">
                    <p class="text-gray-700 font-bold text-xl">Sessions</p>

                    <div class="mt-4 container mx-auto">
                        <div class="flex flex-col md:flex-row mb-4 gap-3">
                            <div class="w-full md:w-auto">
                                <select id="dateFilter" class="border border-gray-300 p-2 rounded w-full">
                                    <option>Date</option>
                                    <option value="all">Tous</option>
                                    <option value="dec">Décembre 2024</option>
                                    <option value="jan">Janvier 2025</option>
                                </select>
                            </div>
                            <div class="w-full md:w-auto">
                                <select id="cityFilter" class="border border-gray-300 p-2 rounded w-full">
                                    <option value="">Ville</option>
                                    <option value="all">Toutes</option>
                                    <option value="analamahitsy">Analamahitsy</option>
                                    <option value="antananarivo">Antananarivo</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-8">
                            <div class="mt-4">
                                <!-- Première Session -->
                                <div class="session flex flex-col md:flex-row justify-between items-center py-4">
                                    <span class="flex flex-col md:flex-row">
                                        <label class="flex items-center" data-target="calendarDetails1"
                                            onclick="toggleDetails(this)">
                                            <i class="fa-solid fa-calendar-days mr-1"></i> Du 12 août au 15 août 2024
                                        </label>
                                        <label class="flex items-center ml-0 md:ml-4 md:border-l md:pl-4 border-gray-300"
                                            data-target="locationDetails1" onclick="toggleDetails(this)">
                                            <i class="fa-solid fa-map-pin mr-1"></i> Analamahitsy
                                        </label>
                                        <label class="flex items-center ml-0 md:ml-4 md:border-l md:pl-4 border-gray-300">
                                            <i class="fa-solid fa-money-bill mr-1"></i> 15.000Ar/personne
                                        </label>
                                        <label
                                            class="flex items-center ml-0 md:ml-4 md:border-l md:pl-4 border-gray-300 bg-[#f1f7e7] text-[#92bd46] px-4 py-2 lg:-mt-2">Places
                                            disponibles</label>
                                    </span>
                                    <a href="#"
                                        class="text-[#e6233a] font-bold text-base px-4 py-2 underline mt-2 md:mt-0">S'inscrire</a>
                                </div>

                                <div id="calendarDetails1" class="details hidden bg-gray-100 p-4 mt-4 rounded shadow-md">
                                    <p class="text-gray-800">
                                        ib-Cegos La Défense<br>
                                        Tour Atlantique - La Défense<br>
                                        9 1 Place de la Pyramide<br>
                                        92911 PARIS LA DEFENSE CEDEX
                                    </p>
                                </div>

                                <div id="locationDetails1" class="details hidden bg-gray-100 p-4 mt-4 rounded shadow-md">
                                    <p class="text-gray-800">
                                        Numerika Analamahitsy
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4">
                                <!-- Deuxième Session -->
                                <div class="session flex flex-col md:flex-row justify-between items-center py-4">
                                    <span class="flex flex-col md:flex-row">
                                        <label class="flex items-center" data-target="calendarDetails2"
                                            onclick="toggleDetails(this)">
                                            <i class="fa-solid fa-calendar-days mr-1"></i> Du 15 déc. au 16 déc. 2024
                                        </label>
                                        <label class="flex items-center ml-0 md:ml-4 md:border-l md:pl-4 border-gray-300"
                                            data-target="locationDetails2" onclick="toggleDetails(this)">
                                            <i class="fa-solid fa-map-pin mr-1"></i> Analamahitsy
                                        </label>
                                        <label class="flex items-center ml-0 md:ml-4 md:border-l md:pl-4 border-gray-300">
                                            <i class="fa-solid fa-money-bill mr-1"></i> 20.000Ar/personne
                                        </label>
                                        <label
                                            class="flex items-center ml-0 md:ml-4 md:border-l md:pl-4 border-gray-300 bg-[#f1f7e7] text-[#92bd46] px-4 py-2 lg:-mt-2">Places
                                            disponibles</label>
                                    </span>
                                    <a href="#"
                                        class="text-[#e6233a] font-bold text-base px-4 py-2 underline mt-2 md:mt-0">S'inscrire</a>
                                </div>

                                <div id="calendarDetails2" class="details hidden bg-gray-100 p-4 mt-4 rounded shadow-md">
                                    <p class="text-gray-800">
                                        Détails pour le deuxième événement.
                                    </p>
                                </div>

                                <div id="locationDetails2" class="details hidden bg-gray-100 p-4 mt-4 rounded shadow-md">
                                    <p class="text-gray-800">
                                        Détails pour le deuxième lieu.
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4">
                                <!-- Troisième Session -->
                                <div class="session flex flex-col md:flex-row justify-between items-center py-4 hidden">
                                    <span class="flex flex-col md:flex-row">
                                        <label class="flex items-center" data-target="calendarDetails3"
                                            onclick="toggleDetails(this)">
                                            <i class="fa-solid fa-calendar-days mr-1"></i> Du 20 déc. au 21 déc. 2024
                                        </label>
                                        <label class="flex items-center ml-0 md:ml-4 md:border-l md:pl-4 border-gray-300"
                                            data-target="locationDetails3" onclick="toggleDetails(this)">
                                            <i class="fa-solid fa-map-pin mr-1"></i> Analamahitsy
                                        </label>
                                        <label class="flex items-center ml-0 md:ml-4 md:border-l md:pl-4 border-gray-300">
                                            <i class="fa-solid fa-money-bill mr-1"></i> 25.000Ar/personne
                                        </label>
                                        <label
                                            class="flex items-center ml-0 md:ml-4 md:border-l md:pl-4 border-gray-300 bg-[#ffeee7] text-[#ff5e32] px-4 py-2 lg:-mt-2">Places
                                            complètes</label>
                                    </span>
                                </div>

                                <div id="calendarDetails3" class="details hidden bg-gray-100 p-4 mt-4 rounded shadow-md">
                                    <p class="text-gray-800">
                                        Détails pour le troisième événement.
                                    </p>
                                </div>

                                <div id="locationDetails3" class="details hidden bg-gray-100 p-4 mt-4 rounded shadow-md">
                                    <p class="text-gray-800">
                                        Détails pour le troisième lieu.
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4">
                                <!-- Quatrième Session -->
                                <div class="session flex flex-col md:flex-row justify-between items-center py-4 hidden">
                                    <span class="flex flex-col md:flex-row">
                                        <label class="flex items-center" data-target="calendarDetails4"
                                            onclick="toggleDetails(this)">
                                            <i class="fa-solid fa-calendar-days mr-1"></i> Du 25 déc. au 26 déc. 2024
                                        </label>
                                        <label class="flex items-center ml-0 md:ml-4 md:border-l md:pl-4 border-gray-300"
                                            data-target="locationDetails4" onclick="toggleDetails(this)">
                                            <i class="fa-solid fa-map-pin mr-1"></i> Analamahitsy
                                        </label>
                                        <label class="flex items-center ml-0 md:ml-4 md:border-l md:pl-4 border-gray-300">
                                            <i class="fa-solid fa-money-bill mr-1"></i> 30.000Ar/personne
                                        </label>
                                        <label
                                            class="flex items-center ml-0 md:ml-4 md:border-l md:pl-4 border-gray-300 bg-[#ffeee7] text-[#ff5e32] px-4 py-2 lg:-mt-2">Places
                                            complètes</label>
                                    </span>
                                </div>

                                <div id="calendarDetails4" class="details hidden bg-gray-100 p-4 mt-4 rounded shadow-md">
                                    <p class="text-gray-800">
                                        Détails pour le quatrième événement.
                                    </p>
                                </div>

                                <div id="locationDetails4" class="details hidden bg-gray-100 p-4 mt-4 rounded shadow-md">
                                    <p class="text-gray-800">
                                        Détails pour le quatrième lieu.
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="#" id="toggleSessions" class="text-blue-600">Voir plus</a>
                            </div>
                        </div>

                        <script>
                            function toggleDetails(label) {
                                const targetId = label.getAttribute('data-target');
                                const details = document.getElementById(targetId);
                                if (details.classList.contains('hidden')) {
                                    details.classList.remove('hidden');
                                } else {
                                    details.classList.add('hidden');
                                }
                            }
                        </script>
                    </div>

                    <script>
                        const toggleSessions = document.getElementById('toggleSessions');
                        const hiddenSessions = document.querySelectorAll('.session.hidden');

                        toggleSessions.addEventListener('click', function(e) {
                            e.preventDefault();
                            hiddenSessions.forEach(session => {
                                session.classList.toggle('hidden');
                            });
                            toggleSessions.textContent = hiddenSessions[0].classList.contains('hidden') ? 'Voir plus' :
                                'Voir moins';
                        });
                    </script>
                </div>


                {{-- Div 3 mila script --}}
                <div class="border border-1 border-[#e8eef7] rounded-lg container mx-auto mt-10 p-8">
                    <div>
                        <p class="text-gray-700 text-xl font-bold mb-4">Le programme de formation</p>

                        <button id="toggleAllBtn" class="text-blue-500 py-2 mb-4">
                            Développer toutVoir plus
                        </button>

                        <div class="mb-4">
                            <div
                                class="flex justify-between items-center cursor-pointer toggle-title bg-gray-100 rounded px-6 py-4">
                                <p class="font-semibold text-lg">Pedagogical goals</p>
                                <span class="icon text-2xl">&#x25BC;</span>
                            </div>
                            <div class="content hidden py-4 px-6 mt-2 rounded">
                                <p class="whitespace-pre-line"><i class="fa-solid fa-check text-xs mr-2"></i>GÉNÉRAL :
                                    Connexion à son espace ZEENDOC Présentation de la page d'accueil

                                    <i class="fa-solid fa-check text-xs mr-2"></i> DÉPÔTS DE DOCUMENTS : Par numérisation
                                    Par dépôt direct À partir d'un smartphone

                                    <i class="fa-solid fa-check text-xs mr-2"></i> DÉCOUPAGE DES DOCUMENTS
                                </p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div
                                class="flex justify-between items-center cursor-pointer toggle-title bg-gray-100 rounded px-6 py-4">
                                <p class="font-semibold text-lg">Training content</p>
                                <span class="icon text-2xl">&#x25BC;</span>
                            </div>
                            <div class="content hidden py-4 px-6 mt-2 rounded">
                                <p class="whitespace-pre-line"><i class="fa-solid fa-check text-xs mr-2"></i>GÉNÉRAL :
                                    Connexion à son espace ZEENDOC Présentation de la page d'accueil

                                    <i class="fa-solid fa-check text-xs mr-2"></i> DÉPÔTS DE DOCUMENTS : Par numérisation
                                    Par dépôt direct À partir d'un smartphone

                                    <i class="fa-solid fa-check text-xs mr-2"></i> DÉCOUPAGE DES DOCUMENTS
                                </p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div
                                class="flex justify-between items-center cursor-pointer toggle-title bg-gray-100 rounded px-6 py-4">
                                <p class="font-semibold text-lg">Mise en œuvre en situation de travail</p>
                                <span class="icon text-2xl">&#x25BC;</span>
                            </div>
                            <div class="content hidden py-4 px-6 mt-2 rounded">
                                <p class="whitespace-pre-line"><i class="fa-solid fa-check text-xs mr-2"></i>GÉNÉRAL :
                                    Connexion à son espace ZEENDOC Présentation de la page d'accueil

                                    <i class="fa-solid fa-check text-xs mr-2"></i> DÉPÔTS DE DOCUMENTS : Par numérisation
                                    Par dépôt direct À partir d'un smartphone

                                    <i class="fa-solid fa-check text-xs mr-2"></i> DÉCOUPAGE DES DOCUMENTS
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Script du div 3 --}}
                <script>
                    const toggleTitles = document.querySelectorAll('.toggle-title');
                    const contents = document.querySelectorAll('.content');
                    const toggleAllBtn = document.getElementById('toggleAllBtn');

                    toggleTitles.forEach((title, index) => {
                        title.addEventListener('click', () => {
                            const content = contents[index];
                            const icon = title.querySelector('.icon');

                            content.classList.toggle('hidden');

                            if (content.classList.contains('hidden')) {
                                icon.innerHTML = '&#x25BC;';
                            } else {
                                icon.innerHTML = '&#x25B2;';
                            }
                        });
                    });

                    let allExpanded = false;

                    toggleAllBtn.addEventListener('click', () => {
                        contents.forEach((content, index) => {
                            const icon = toggleTitles[index].querySelector('.icon');

                            if (allExpanded) {
                                content.classList.add('hidden');
                                icon.innerHTML = '&#x25BC;';
                            } else {
                                content.classList.remove('hidden');
                                icon.innerHTML = '&#x25B2;';
                            }
                        });

                        allExpanded = !allExpanded;
                        toggleAllBtn.textContent = allExpanded ? 'Réduire tout' : 'Développer tout';
                    });
                </script>

                {{-- Avis client      --}}
                {{-- <div class="border border-1 border-[#e8eef7] rounded-lg p-8 container mx-auto m-10">
                    
                    <h2 class="text-gray-700 text-xl font-bold mb-4">Avis Clients</h2>
                    <p class="text-gray-700 mb-6">
                        Découvrez ce que nos clients pensent de nos formations ! Vos retours sont essentiels pour nous aider à nous améliorer.
                    </p>

                    <div id="reviews">
                        <!-- Client 1 -->
                        <div class="border border-gray-300 p-4 rounded-lg shadow-lg mb-4">
                            <div class="flex items-center mb-2">
                                <img src="https://via.placeholder.com/50" alt="Client 1" class="rounded-full mr-2">
                                <div>
                                    <p class="font-semibold">Alice Dupont</p>
                                    <div class="text-yellow-500">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <p>"Une formation très enrichissante ! J'ai appris énormément de choses en peu de temps. Je recommande vivement."</p>
                        </div>
                
                        <!-- Client 2 -->
                        <div class="border border-gray-300 p-4 rounded-lg shadow-lg mb-4">
                            <div class="flex items-center mb-2">
                                <img src="https://via.placeholder.com/50" alt="Client 2" class="rounded-full mr-2">
                                <div>
                                    <p class="font-semibold">Marc Bernard</p>
                                    <div class="text-yellow-500">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-regular fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <p>"Les formateurs sont très compétents et disponibles. Un bon rapport qualité-prix pour la formation."</p>
                        </div>
                    </div>
                
                    <button id="showMore" class="mt-4 text-blue-500 hover:underline">
                        Voir plus
                    </button>
                
                    <div id="moreReviews" class="hidden">
                        <!-- Client 4 -->
                        <div class="border border-gray-300 p-4 rounded-lg shadow-lg mb-4">
                            <div class="flex items-center mb-2">
                                <img src="https://via.placeholder.com/50" alt="Client 4" class="rounded-full mr-2">
                                <div>
                                    <p class="font-semibold">Julien Lefevre</p>
                                    <div class="text-yellow-500">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-regular fa-star"></i>
                                        <i class="fa-regular fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <p>"Une bonne formation, mais j'aurais aimé plus d'exemples pratiques."</p>
                        </div>
                
                        <!-- Client 6 -->
                        <div class="border border-gray-300 p-4 rounded-lg shadow-lg mb-4">
                            <div class="flex items-center mb-2">
                                <img src="https://via.placeholder.com/50" alt="Client 6" class="rounded-full mr-2">
                                <div>
                                    <p class="font-semibold">Thomas Renard</p>
                                    <div class="text-yellow-500">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-regular fa-star"></i>
                                        <i class="fa-regular fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <p>"De bonnes informations, mais le contenu pourrait être plus dynamique."</p>
                        </div>
                    </div>
                    
                    <script>
                        document.getElementById('showMore').addEventListener('click', function() {
                            const moreReviews = document.getElementById('moreReviews');
                            if (moreReviews.classList.contains('hidden')) {
                                moreReviews.classList.remove('hidden');
                                this.innerText = 'Voir moins';
                            } else {
                                moreReviews.classList.add('hidden');
                                this.innerText = 'Voir plus';
                            }
                        });
                    </script>
                    
                </div>      --}}

            </div>
        </div>

    </div>
@endsection
