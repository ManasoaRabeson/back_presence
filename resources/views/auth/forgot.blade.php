@extends('layouts.masterGuest')

@section('content')
    <div class="container h-full mx-auto px-1 pt-4">

        <div class="flex flex-col lg:flex-row items-center justify-center px-6 lg:mx-80 h-full">

            <div class="xl:w-3/4 w-full justify-center mb-20 px-10 pb-10 bg-[#f1f1f4] rounded-xl">

                <div class="w-full flex flex-col items-center xl:items-center mb-6">
                    <img src="/img/logo/Logo_mark.svg" alt="Logo" class="w-28 h-28 mt-6">
                    <h1
                        class="text-xl md:text-3xl mt-4 font-extrabold text-[#A462A4] leading-tight text-center xl:text-left">
                        Mot de passe oublié ?
                    </h1>
                </div>

                <div class="flex flex-col gap-2 p-4 rounded-xl bg-white">
                    <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-4">
                        @csrf
                        <p class="text-lg text-slate-700">Entrez votre adresse email et nous vous enverrons un lien
                            pour
                            réinitialiser votre mot de passe</p>
                        <input type="email" name="email" class="input input-bordered w-full"
                            placeholder="votre-adresse@gmail.com">
                        <div class="flex flex-col w-full items-center gap-3">
                            <button class="btn btn-primary text-white capitalize w-full">réinitialiser
                                votre mot de passe</button>
                            <a href="{{ route('login') }}"
                                class="text-base w-full text-right text-purple-600 duration-150 cursor-pointer hover:text-purple-500/90 hover:underline hover:underline-offset-4">Se
                                connecter ?</a>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
