@extends($extends_containt)

@section('content')
    <div class="container mx-auto px-10 py-6 bg-white my-10 rounded-lg">

        {{-- <div class="lg:pl-10 pt-6">
            <a href="{{ route('detail.vousetes') }}"><i class="fa-solid fa-arrow-left text-xl"></i></a>
        </div> --}}

        <div class="flex flex-col lg:flex-row py-10 lg:px-10 lg:mx-auto w-full mt-10">

            <div class="basis-1/3">
                <img class="rounded-lg"
                    src="https://images.ctfassets.net/2pudprfttvy6/4sAX5vdnnKd7QwpYTxiMFW/3d3ea68ad67571bd2f2bb166d7db16f4/academy_leadership_hero.jpg"
                    alt="Image formateur" />
            </div>
            <div class="basis-2/3">
                <div class="lg:px-12 py-6">
                    <p class="text-3xl lg:text-4xl font-bold">Formateur</p>

                    <p class="mt-2 font-semibold text-lg">Découvrez des outils puissants pour révolutionner vos sessions de
                        formation !</p>

                    <p class="whitespace-pre-line mt-4"><strong>Notre logiciel vous offre une expérience de gestion simple
                            et fluide : </strong> créez, gérez et suivez vos formations en quelques clics.

                        <strong>Profitez d’une panoplie de fonctionnalités pratiques </strong> comme la planification des
                        cours, l’évaluation personnalisée des apprenants, et des analyses de performance approfondies pour
                        garantir un apprentissage à la fois engageant et performant
                    </p>
                </div>
            </div>

        </div>

    </div>
@endsection
