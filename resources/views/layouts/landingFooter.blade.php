<footer class="dark:bg-gray-900 absolute bottom-0 w-full">
    <div class="space-y-6 mx-auto">
        <div class="pl-20 bg-[#a462a4]">
            <div class="text-2xl 2xl:text-3xl py-3 text-white xl:ml-0  font-semibold">
                Toutes les catégories de formation
            </div>
            <div class="text-white 2xl:text-lg content-center">
                <ul class="lg:grid grid-cols-2 xl:grid-cols-4 mx-2 p-4 list-disc">
                    @foreach ($domaines as $d)
                        <li>
                            <a href="/formation/category/{{ $d['idDomaine'] }}"
                                class=" transition-colors duration-200 dark:text-gray-200 dark:hover:text-blue-400 hover:text-blue-600">{{ $d['nomDomaine'] }}
                                <span class="text-white">({{ $d['nb_module'] }})</span></a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</footer>
