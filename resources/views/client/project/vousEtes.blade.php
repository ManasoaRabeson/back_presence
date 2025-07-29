@extends($extends_containt)

@section('content')
    <div class="h-full overflow-y-scroll">

        <div class="mt-10">

            <div class="w-full h-full py-10 mx-auto mt-16 rounded-lg lg:px-10 bg-slate-100">

                <div class="px-4 py-6 lg:px-12 xl:ml-52">
                    <p class="text-3xl font-bold">Découvrez qui peut utiliser FormaFusion.</p>
                    <p class="mt-2 text-lg">Explorez une gamme variée de profils sur notre marketplace pour répondre à vos
                        besoins d'apprentissage et de développement personnel.</p>
                </div>

                <div class="grid grid-cols-1 gap-10 p-6 mx-auto sm:grid-cols-2 xl:grid-cols-3 xl:pl-60 xl:pr-60">
                    <div
                        class="flex flex-col h-full transition-transform duration-300 bg-white rounded-lg shadow-md hover:shadow-xl hover:scale-105">
                        <img class="rounded-t-lg object-cover object-[center_top] h-80 w-full"
                            src="https://images.ctfassets.net/2pudprfttvy6/4sAX5vdnnKd7QwpYTxiMFW/3d3ea68ad67571bd2f2bb166d7db16f4/academy_leadership_hero.jpg"
                            alt="Image formateur" />
                        <div class="p-6">
                            <p class="text-xl font-semibold">Formateur</p>
                            <p class="mt-2 text-gray-700">Découvrez des outils puissants pour révolutionner vos sessions de
                                formation !</p>
                            <p class="mt-4 text-blue-600 underline">
                                <a href="{{ route('detail.formateur') }}">En savoir plus
                                    <i class="ml-1 fa-solid fa-arrow-right"></i>
                                </a>
                            </p>
                        </div>
                    </div>

                    <div
                        class="flex flex-col h-full transition-transform duration-300 bg-white rounded-lg shadow-md hover:shadow-xl hover:scale-105">
                        <img class="object-cover w-full rounded-t-lg h-80"
                            src="https://images.ctfassets.net/2pudprfttvy6/u00MkmeeO0Q8PoWwSSa4U/7ecc414fdf037ece3a730cd14767b2e5/Gov_upskilling_hero.jpg"
                            alt="Entreprises" />
                        <div class="p-6">
                            <p class="text-xl font-semibold">Entreprises ou groupes d'entreprises</p>
                            <p class="mt-2 text-gray-700">Transformez la gestion de vos formations en un jeu d’enfant !</p>
                            <p class="mt-4 text-blue-600 underline">
                                <a href="{{ route('detail.etp') }}">En savoir plus
                                    <i class="ml-1 fa-solid fa-arrow-right"></i>
                                </a>
                            </p>
                        </div>
                    </div>

                    <div
                        class="flex flex-col h-full transition-transform duration-300 bg-white rounded-lg shadow-md hover:shadow-xl hover:scale-105">
                        <img class="object-cover w-full rounded-t-lg h-80"
                            src="https://images.ctfassets.net/2pudprfttvy6/10yQpGr9WH8bzgGnZVxrrM/99a0eb4d07af68c529f877b6b6d92a03/academy_marketing_image1.jpg"
                            alt="Centre de formation" />
                        <div class="p-6">
                            <p class="text-xl font-semibold">Centre de formation</p>
                            <p class="mt-2 text-gray-700">Boostez la gestion de vos formations avec une solution tout-en-un
                                !</p>
                            <p class="mt-4 text-blue-600 underline">
                                <a href="{{ route('detail.cfp') }}">En savoir plus
                                    <i class="ml-1 fa-solid fa-arrow-right"></i>
                                </a>
                            </p>
                        </div>
                    </div>

                    <div
                        class="flex flex-col h-full transition-transform duration-300 bg-white rounded-lg shadow-md hover:shadow-xl hover:scale-105">
                        <img class="object-cover w-full rounded-t-lg h-80"
                            src="https://images.ctfassets.net/2pudprfttvy6/2PTfJx9KtWYo151cjeiErm/bbf36a34e42b435921f8162fbf8afccb/iStock-1148394694__1___1_.jpg"
                            alt="Apprenants" />
                        <div class="p-6">
                            <p class="text-xl font-semibold">Apprenants</p>
                            <p class="mt-2 text-gray-700">Plongez dans une expérience d'apprentissage interactive et sur
                                mesure !</p>
                            <p class="mt-4 text-blue-600 underline">
                                <a href="{{ route('detail.apprenant') }}">En savoir plus
                                    <i class="ml-1 fa-solid fa-arrow-right"></i>
                                </a>
                            </p>
                        </div>
                    </div>

                    <div
                        class="flex flex-col h-full transition-transform duration-300 bg-white rounded-lg shadow-md hover:shadow-xl hover:scale-105">
                        <img class="object-cover w-full rounded-t-lg h-80"
                            src="https://images.ctfassets.net/2pudprfttvy6/2hQa2f3MzHpFV9WbTKn98v/5ec924309d126da32c64dea61472f4a5/Homepage_hero.jpg"
                            alt="Particuliers" />
                        <div class="p-6">
                            <p class="text-xl font-semibold">Particuliers</p>
                            <p class="mt-2 text-gray-700">Simplifiez la gestion de vos formations personnelles avec une
                                interface intuitive !</p>
                            <p class="mt-4 text-blue-600 underline">
                                <a href="{{ route('detail.particulier') }}">En savoir plus
                                    <i class="ml-1 fa-solid fa-arrow-right"></i>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection
