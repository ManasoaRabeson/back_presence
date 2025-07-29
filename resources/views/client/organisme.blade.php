@extends($extends_containt)

@push('custom_style')
    <style>
        .hidden {
            display: none;
        }
    </style>
@endpush

@section('content')
    <div class="h-full">
        <div class="relative bg-cover bg-center h-32 md:h-32 lg:h-40"
            style="background-image: url('{{ asset('img/hero/hero4.jpg') }}');">
            <div class="absolute inset-0 bg-[#212529] opacity-60"></div>
            <div class="relative flex items-center justify-center h-full">
                <p class="text-xl md:text-4xl font-bold text-white">Plus de {{ $roundedOrganismes }} organismes de
                    formation</p>
            </div>
        </div>

        <div class="bg-[#f3f4f6] h-full py-14">
            
            <div class="lg:container mx-auto px-14 py-6">

                <div class="container mx-auto p-6 bg-white shadow-xl rounded-xl">
                    <div class="flex flex-col lg:flex-row gap-4 items-center lg:items-start lg:justify-between">
                        <div class="text-center lg:text-left mb-8 lg:mb-0 lg:w-5/12">
                            <div class="bg-[#e8eef7] rounded-xl p-8">
                                <p class="text-2xl font-semibold underline text-[#a462a4]"><a href="/liste_organisme"> Des Organismes de formation de confiance! <i class="fa-solid fa-arrow-right ml-2"></i> </p> </a>
                                <p class="text-base px-10 lg:px-0 text-slate-600 mt-4">
                                    Forma Fusion c’est un nombre d’Organismes de Formation restreint, engagés dans une
                                    démarche qualité et accompagnent afin de mettre en place rapidement des
                                    formations adaptées à vos besoins.
                                </p>
                            </div>
                            <div class="mt-4">
                                <div class="relative mb-6">
                                    <div class="inline-flex items-center bg-yellow-500 text-white font-semibold px-4 py-2 rounded-tl-lg rounded-tr-lg shadow-md">
                                      <span class="mr-2">⭐</span>
                                      <span>Publicité Sponsorisée</span>
                                      <span class="ml-2">⭐</span>
                                    </div>
                                    <div class="w-full h-1 bg-yellow-400"></div>
                                </div>                                                                   
                                <div class="flex justify-center space-x-4">
                                    @foreach ($firstTwoPublicites as $firstTwoPublicite)
                                        <div class="slide"><a href="/formation/detail/{{ $firstTwoPublicite->idModule }}"><div class="bg-white rounded-lg shadow-md overflow-hidden w-96 relative">
                                            <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/modules/{{ $firstTwoPublicite->module_image }}" 
                                                alt="Photo de la formation 1" 
                                                class="h-36 w-full object-cover" />
                                            <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/entreprises/{{ $firstTwoPublicite->logo }}" 
                                                alt="Logo centre de formation" 
                                                class="h-12 w-24 absolute top-2 left-2" />
                                            <div class="p-4 text-left">
                                                <h3 class="text-lg font-semibold text-gray-800 overflow-hidden whitespace-nowrap text-ellipsis">{{ $firstTwoPublicite->moduleName }}</h3>
                                                <p class="text-sm text-gray-600 mt-2 overflow-hidden whitespace-nowrap text-ellipsis">
                                                    {{ $firstTwoPublicite->description ?? 'Aucune description' }}
                                                </p>
                                                <div class="flex items-center mt-2">
                                                    <i class="text-gray-600 fa-solid fa-medal"></i>
                                                    <span class="text-sm text-gray-600 ml-1">{{ $firstTwoPublicite->module_level_name }}</span>
                                                </div>
                                                <div class="flex items-center mt-2">
                                                    <i class="text-gray-600 fa-solid fa-clock"></i>
                                                    <span class="text-sm text-gray-600 ml-1">{{ $firstTwoPublicite->dureeJ ?? 0 }}
                                                        jours | {{ $firstTwoPublicite->dureeH ?? 0 }} heures</span>
                                                </div>
                                                @php
                                                    $prix = floatval($firstTwoPublicite->prix ?? 0);
                                                @endphp
                                                <div class="flex items-center mt-2">
                                                    <i class="mr-1 fa-regular fa-money-bill-1"></i>
                                                    <span class="text-sm text-gray-600 ml-1"><span class="font-bold">{{ number_format($prix, 2, ',', ' ') }}
                                                        Ar</span></span>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    @php
                                                        $averageScore = $firstTwoPublicite->note['average'] ?? 0;
                                                        if (!function_exists('generalRound')) {
                                                            function generalRound($score)
                                                            {
                                                                return round($score * 2) / 2;
                                                            }
                                                        }
                                                        $adjustedAverage = generalRound($averageScore);
                                                    @endphp
                                                    <div class="flex average"
                                                        data-average="{{ $firstTwoPublicite->note['average'] ?? 0 }}"></div>
                                                    <p class="text-gray-600">{{ $adjustedAverage ?? 'N/A' }}
                                                        <span
                                                            class="text-gray-400">({{ $firstTwoPublicite->note['totalEmployees'] ?? 0 }}
                                                            avis)</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div></a></div>  
                                    @endforeach

                                    @foreach ($otherPublicites as $otherPublicite)
                                        <div class="slide hidden"><a href="/formation/detail/{{ $otherPublicite->idModule }}"><div class="bg-white rounded-lg shadow-md overflow-hidden w-96 relative">
                                            <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/modules/{{ $otherPublicite->module_image }}" 
                                                alt="Photo de la formation 1" 
                                                class="h-36 w-full object-cover" />
                                            <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/entreprises/{{ $otherPublicite->logo }}" 
                                                alt="Logo centre de formation" 
                                                class="h-12 w-24 absolute top-2 left-2" />
                                            <div class="p-4 text-left">
                                                <h3 class="text-lg font-semibold text-gray-800 overflow-hidden whitespace-nowrap text-ellipsis">{{ $otherPublicite->moduleName }}</h3>
                                                <p class="text-sm text-gray-600 mt-2 overflow-hidden whitespace-nowrap text-ellipsis">
                                                    {{ $otherPublicite->description ?? 'Aucune description' }}
                                                </p>
                                                <div class="flex items-center mt-2">
                                                    <i class="text-gray-600 fa-solid fa-medal"></i>
                                                    <span class="text-sm text-gray-600 ml-1">{{ $firstTwoPublicite->module_level_name }}</span>
                                                </div>
                                                <div class="flex items-center mt-2">
                                                    <i class="text-gray-600 fa-solid fa-clock"></i>
                                                    <span class="text-sm text-gray-600 ml-1">{{ $firstTwoPublicite->dureeJ ?? 0 }}
                                                        jours | {{ $firstTwoPublicite->dureeH ?? 0 }} heures</span>
                                                </div>
                                                @php
                                                    $prix = floatval($firstTwoPublicite->prix ?? 0);
                                                @endphp
                                                <div class="flex items-center mt-2">
                                                    <i class="mr-1 fa-regular fa-money-bill-1"></i>
                                                    <span class="text-sm text-gray-600 ml-1"><span class="font-bold">{{ number_format($prix, 2, ',', ' ') }}
                                                        Ar</span></span>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    @php
                                                        $averageScore = $firstTwoPublicite->note['average'] ?? 0;
                                                        if (!function_exists('generalRound')) {
                                                            function generalRound($score)
                                                            {
                                                                return round($score * 2) / 2;
                                                            }
                                                        }
                                                        $adjustedAverage = generalRound($averageScore);
                                                    @endphp
                                                    <div class="flex average"
                                                        data-average="{{ $firstTwoPublicite->note['average'] ?? 0 }}"></div>
                                                    <p class="text-gray-600">{{ $adjustedAverage ?? 'N/A' }}
                                                        <span
                                                            class="text-gray-400">({{ $firstTwoPublicite->note['totalEmployees'] ?? 0 }}
                                                            avis)</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div></a></div>
                                    @endforeach
                                </div>                                
                            </div>
                        </div>

                        <div class="container mx-auto js-carousel-container lg:w-7/12 lg:ml-10 -mt-1">
                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4" id="js-carousel-container">
                                @foreach ($customers as $customer)
                                    <div class="card bg-white w-full h-[18rem] shadow-xl hover:-translate-y-2 duration-300">
                                        <a href="/organisme_formation/{{ $customer->idCustomer }}"
                                            class="hover:text-inherit">
                                            <figure class="px-10 h-[100px] py-4 bg-slate-50">
                                                @if (isset($customer->logo))
                                                    <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/entreprises/{{ $customer->logo }}"
                                                        alt="Shoes" class="rounded-xl" />
                                                @else
                                                    <i class="fa-solid fa-image text-3xl text-slate-700"></i>
                                                @endif
                                            </figure>
                                            <div class="card-body items-center text-center">
                                                <h2 class="card-title"> {{ $customer->customerName }}</h2>
                                                <p class="line-clamp-2">{{ $customer->customer_slogan ?? '' }}</p>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>

                            <div class="button-container" id="button-container" style="display: none;">
                                <button class="js-carousel-arrow js-carousel-prev" id="prev"
                                    aria-label="Previous Slide">‹</button>
                                <button class="js-carousel-arrow js-carousel-next" id="next"
                                    aria-label="Next Slide">›</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    
@endsection

@section('script')
    <script src="{{ asset('js/jquery.raty.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.average').each(function() {
                var average = $(this).data('average');
                ratyNotationFormation($(this), average);
            });
        });

        function ratyNotationFormation(element, average) {
            $(element).raty({
                score: average,
                readOnly: true
            });

            $(`.average img`).addClass(`w-5 h-5`);
        }
    </script>
    <script>
        const slides = document.querySelectorAll('.slide');
        let currentIndex = 0;

        function showSlides(index) {
            slides.forEach((slide, i) => {
                if (i >= index && i < index + 2) {
                    slide.classList.remove('hidden');
                } else {
                    slide.classList.add('hidden');
                }
            });
        }

        function cycleSlides() {
            showSlides(currentIndex);
            currentIndex += 2;
            if (currentIndex >= slides.length) {
                currentIndex = (currentIndex % slides.length);
            }
            if (currentIndex === 1 && slides.length % 2 !== 0) {
                currentIndex = 0; 
            }
        }
        showSlides(currentIndex);
        setInterval(cycleSlides, 4000); 
    </script>
@endsection
