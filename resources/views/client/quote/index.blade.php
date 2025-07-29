@extends($extends_containt)

@section('content')
    <div
        class="px-6 h-full md:px-14 lg:px-28 xl:px-20 bg-white mx-4 lg:mx-20 xl:mx-60  space-y-6 py-6 mt-28 mb-10 rounded-lg">
        <div class="flex flex-row items-center justify-center">
            <img src="https://www.skills.hr/images/picto/rocket-7f7bbec200661130cddac0dcb0efb107.svg?vsn=d" alt="">
            <p class="text-xl font-semibold ml-2">Nous sommes ravis que cette formation vous intéresse</p>
        </div>
        <div class="text-center">
            <p>D'ici, vous pouvez demander des informations complémentaires pour la formation sélectionnée.</p>
            <p>👌 Pas d'engagement particulier ici, c'est un premier contact.</p>
            <p>🖌 Répondez à ces quelques questions pour que l'organisme de formation en apprenne un peu plus sur vous.</p>
        </div>
        <div class="flex flex-col items-center justify-center space-y-2">
            <a href="{{ url('/demande_devis/company/' . $etpId . '/' . $module) }}"
                class="border border-[#a462a4] text-[#a462a4] font-semibold w-full md:w-[36.6rem] text-center px-4 py-2">
                Je demande un devis pour mettre en place une formation pour mes équipes
            </a>
            <a href="{{ url('/demande_devis/individual/' . $etpId . '/' . $module) }}"
                class="border border-[#a462a4] text-[#a462a4] font-semibold px-4 py-2 w-full md:w-[36.6rem] text-center">
                Je demande un devis à titre individuel
            </a>
        </div>

    </div>
@endsection
