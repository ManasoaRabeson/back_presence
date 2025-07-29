@extends('layouts.masterGuest')

@section('content')
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

                <form action="{{ route('user.check') }}" method="POST" class="p-4 bg-white shadow-lg rounded-xl">
                    @csrf
                    <p class="text-gray-700 md:mt-4">Renseignez votre adresse email </p>
                    <x-input type="text" class="w-full h-20 mt-4 md:h-10" name="email"
                        placeholder="votre-adresse@gmail.com" />
                </form>
                <p class="pt-4 pl-2 text-lg text-[#a462a4] underline font-bold transition-all duration-300 hover:text-[#9d4ed4] hover:underline-offset-4">
                    <a href="/user/register" class="hover:text-[#9d4ed4] hover:underline">Pas encore de compte ? Inscrivez-vous</a>
                </p>  
            </div>
        </div>
    </div>
@endsection
