@extends('layouts.masterGuest')

@section('content')
    @if (Session::has('error'))
        <p class="w-3/4 mx-auto p-4 rounded-xl bg-red-100 text-red-600">{{ Session('error') }}</p>
    @endif

    <div class="container h-full mx-auto px-1 pt-4">

        <div class="flex flex-col lg:flex-row items-center justify-center px-6 lg:mx-80 h-full">

            <div class="xl:w-3/4 w-full justify-center mb-20 px-10 pb-10 bg-[#f1f1f4] rounded-xl">

                <div class="w-full flex flex-col items-center xl:items-center mb-6">
                    <img src="/img/logo/Logo_mark.svg" alt="Logo" class="w-28 h-28 mt-6">
                    <h1
                        class="text-xl md:text-3xl mt-4 font-extrabold text-[#A462A4] leading-tight text-center xl:text-left">
                        Connectez-vous
                    </h1>
                </div>

                <form action="{{ url('login') }}" method="POST" class="p-4 bg-white shadow-lg rounded-xl">
                    @csrf
                    <h1 class="text-gray-700">Adresse email</h1>
                    <x-input type="text" name="email" value="{{ Session('email') }}" class="w-full h-20 md:h-10"
                        placeholder=" votre-adresse@gmail.com" />
                    <p class="mt-6 text-xl text-[#A462A4]">On se connait déjà ! </p>
                    <p class="mb-2 text-gray-700">Renseignez vos mot de passe</p>
                    <div class="relative inline-flex w-full">
                        <input type="password" id="password" name="password" data-target="password"
                            class="password bg-white password-toggle outline-none w-full bg-transparent pl-2 h-10 border-[1px] border-gray-200 rounded-md hover:border-purple-300 focus:border-purple-300 focus:ring-2 focus:ring-purple-100 duration-200 text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="absolute cursor-pointer bi bi-eye-fill top-3 right-4 eye-icon-toggle"
                            viewBox="0 0 16 16">
                            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                            <path
                                d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                        </svg>
                    </div>
                    <div class="grid mt-6 place-items-center">
                        <button type="submit"
                            class="rounded-xl w-full bg-[#a462a4] px-4 py-2 text-white flex justify-center hover:bg-[#A462A4]/90 duration-200">Connexion</button>
                    </div>
                    <div class="grid mt-4 place-items-end">
                        <a href="{{ route('forgot') }}" class="text-[#a462a4] underline-offset-4 text-sm underline">Mot
                            de
                            passe
                            oublié ?</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
