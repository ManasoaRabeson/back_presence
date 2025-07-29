@extends($extends_containt)

@push('custom_style')
@endpush

@section('content')
    <div class="h-full overflow-y-scroll">
        <div class="relative bg-cover bg-center h-32 md:h-32 lg:h-40"
            style="background-image: url('{{ asset('img/hero/hero4.jpg') }}');">
            <div class="absolute inset-0 bg-slate-900 opacity-60"></div>
            <div class="relative flex items-center justify-center h-full">
                <p class="text-xl md:text-4xl font-bold text-white">{{ $customers->count() }}</strong> institutions au total
                </p>
            </div>
        </div>

        <div class="flex justify-center items-center bg-slate-50 px-6 py-5 w-full">

            <label class="input input-bordered w-3/4 lg:w-4/12 flex items-center gap-2">
                <input id="search" type="search" class="grow" placeholder="Rechercher ..." />
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-4 w-4 opacity-70">
                    <path fill-rule="evenodd"
                        d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                        clip-rule="evenodd" />
                </svg>
            </label>

        </div>

        <div class="mx-auto px-10 lg:px-40">
            <div class="flex flex-col lg:justify-between lg:mx-0 items-center">
                <div class="grid w-full grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mt-6 mb-20"
                    id="listCfp">
                    @foreach ($customers as $customer)
                        <div
                            class="card bg-white w-full h-[20rem] cfp-item shadow-xl overflow-hidden cursor-pointer duration-300">
                            <a href="/organisme_formation/{{ $customer->idCustomer }}" class="hover:text-inherit h-full">
                                <figure class="overflow-hidden h-[10rem] bg-slate-50">
                                    @if (isset($customer->logo))
                                        <img src="https://formafusionmg.ams3.cdn.digitaloceanspaces.com/formafusionmg/img/entreprises/{{ $customer->logo }}"
                                            alt="Organisme" class="w-full h-full" />
                                    @else
                                        <i class="fa-solid fa-image text-3xl text-slate-400"></i>
                                    @endif
                                </figure>
                            </a>
                            <div class="card-body">
                                <a href="/organisme_formation/{{ $customer->idCustomer }}" class="hover:text-inherit">
                                    <h2 class="card-title text-lg line-clamp-1 capitalize text-slate-800" id="nameCfp">
                                        {{ $customer->customerName }}</h2>
                                    <p class="text-slate-600">Membre depuis
                                        <strong>{{ \Carbon\Carbon::parse($customer->created_at)->format('Y') }}</strong>
                                    </p>
                                </a>
                                <div class="card-actions justify-end mt-3">
                                    <div data-bs-toggle="tooltip"
                                        title="{{ $customer->customerPhone ?? 'Pas de téléphone' }}"
                                        class="badge text-slate-500 border-[1px] border-slate-400 font-normal badge-outline badge-lg">
                                        Téléphone</div>
                                    <div data-bs-toggle="tooltip"
                                        title="{{ $customer->customerEmail ?? "Pas d'adresse email" }}"
                                        class="badge text-slate-500 border-[1px] border-slate-400 font-normal badge-outline badge-lg">
                                        Email</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @include('layouts.homeFooter')
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $("#search").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $(".cfp-item").filter(function() {
                    var isVisible = $(this).find('#nameCfp').text().toLowerCase().indexOf(value) > -
                        1;
                    $(this).toggle(isVisible);
                });
            });
        });

        // Sélectionner tous les icônes d'informations
        const icons = document.querySelectorAll('[data-target]');

        icons.forEach(icon => {
            icon.addEventListener('click', function() {
                // Obtenir l'ID de l'élément cible à afficher
                const targetId = this.getAttribute('data-target');
                const targetInfo = document.getElementById(targetId);

                // Masquer toutes les informations
                document.querySelectorAll('.info').forEach(info => {
                    if (info !== targetInfo) {
                        info.classList.add('hidden');
                    }
                });

                // Basculer l'affichage de l'élément cible
                targetInfo.classList.toggle('hidden');
            });
        });
    </script>
@endsection
