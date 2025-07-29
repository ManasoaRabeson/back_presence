<script src="https://cdn.tailwindcss.com"></script>

<div class="top-0 z-10 w-full px-4 py-4 flex justify-between items-center">
    <a class="text-6xl ml-2 flex font-bold leading-none" href="#">
        <img class="h-20" alt="logo" viewBox="0 0 10240 10240" src="http://127.0.0.1:8000/img/logo/Logo_mark.svg">
        </img> Forma-Fusion
    </a>
</div>

<div class="flex flex-col xl:flex-row mx-10 space-y-32 xl:space-y-0 xl:mx-56 text-[#a462a4] ">
    <div class="w-full xl:w-1/2 space-y-20 xl:space-y-10 xl:pr-16">
        <h1 class="text-8xl xl:text-4xl"> Bienvenue sur Forma-Fusiom.com </h1>
        <p class="text-4xl xl:text-lg">Forma-Fusiom.com est l'interface unique entre les Organismes de Formation et les entreprises !</p>
        <p class="text-4xl xl:text-lg">Avec notre marketplace il devient plus facile d’identifier la bonne formation pour chacun de vos besoins et de vos enjeux.</p>
        <p class="text-4xl xl:text-lg">Parce que l’acquisition des bonnes compétences est essentielle, découvrez nos Organismes de formations de confiance.</p>
    </div>
    <div class="w-full xl:w-1/2 xl:pl-16 space-y-16  lg:space-y-4 text-4xl xl:text-lg ">
        <h1 class="sm:text-8xl xl:text-4xl">Mot de passe oublié ?</h1>
        <p class="text-gray-600">Entrez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>
        <input type="text" name="email" class="h-20 bg-white w-full bg-transparent pl-2 xl:h-10 border-[1px] border-gray-200 rounded-md hover:border-purple-300 focus:border-purple-300 focus:ring-2 focus:ring-purple-100 duration-200 text-gray-400" placeholder="votre-adresse@email.com" />
        <div class="flex flex-col text-center sm:space-y-16 xl:space-y-4">
            <div class="">
                <button class="rounded-full bg-[#a462a4] px-4 py-2 text-white"> Réinitialiser le mot de passe </button>
            </div>
            <div class="">
                <a href="{{ url('/user/login') }}" class="text-[#a462a4] underline">Se connecter</a>
            </div>
        </div>
    </div>
</div>