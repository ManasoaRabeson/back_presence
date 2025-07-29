@extends('layouts.masterAdmin')

@section('content')
    <div class="w-full">
        <div class="w-full h-[64px] flex justify-start items-center mb-3 border-b-[1px] boredr-gray-600 bg-gray-100/50">
            {{-- Menu Title --}}
            <div class="w-1/3 inline-flex items-baseline gap-2 px-3">
            <i class="fa-solid fa-user-tie 2xl:text-2xl md:text-xl text-gray-600"></i>
            <label class="2xl:text-2xl md:text-xl text-gray-500 font-semibold">Super Admin</label>
            </div>
            {{-- fin menu title --}}
            <div class="flex w-1/3 justify-center items-center">
            </div>
            <div class="w-1/3">
            </div>
        </div>

        {{-- Profil SuperAdmin --}}
        <div class="flex flex-row justify-center items-start mx-2">
            <div
            class="w-1/4 flex flex-col justify-start items-center bg-gradient-to-r from-violet-200 to-pink-100 shadow-md rounded-md">
            {{-- card --}}
            <div class="w-full m-4 p-4 flex flex-col justify-center items-center overflow-hidden bg-white">
                <div
                class="w-[130px] h-[130px] mt-2 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex justify-center items-center">
                <img class="w-[122px] h-[122px] rounded-full" src="{{ asset('img/employes/profil.jpg') }}"
                    alt="Photo de profil">
                </div>
                @foreach ($superAdm as $sa)
                <div class="px-6 py-4">
                    <div class="font-bold text-xl text-gray-500 mb-2 text-center">{{ $sa->name }}</div>
                    <p class="text-gray-500 text-base text-center">
                    Status : Super Admin<br>
                    Email : {{ $sa->email }}<br>
                    Numéro de téléphone : +1234567890<br>
                    Bio : [Résumé]
                    </p>
                </div>
                <div class="px-6 py-4">
                    <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2">
                    #SuperAdmin
                    </span>
                    <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2">
                    #Expert
                    </span>
                    <!-- Ajoutez d'autres badges ou informations ici -->
                </div>
            </div>
            @endforeach

            {{-- Fin card --}}
            </div>
            {{-- Actualités ou Message de Rapport --}}
            <div class="w-2/4 flex flex-col justify-center items-center">
            <h2 class="text-xl text-gray-500 text-center mb-2 mt-2">Messages et Feedback</h2>
            <div class="w-full flex flex-row justify-between items-center">
                {{-- 1 --}}
                <div
                class="w-1/2 h-auto flex flex-col justify-center items-center rounded-xl mt-4 mx-4 border-[1px] border-gray-100">
                <span
                    class="w-full h-[56px] flex felx-row justify-between items-center bg-gray-100 text-gray-500 mb-2 px-3 rounded-t-xl">
                    <h3>Messages recent</h3>
                    <button
                    class="text-purple-500 hover:bg-purple-100 hover:border-[1px] hover:border-purple-500 rounded py-1 px-2">Clear
                    all</button>
                </span>
                <div class="w-[320px] h-auto text-center flex flex-col gap-4">
                    <p class="border-[1px] border-gray-200 rounded shadow-sm p-2 text-gray-500">Lorem ipsum dolor sit, amet
                    consectetur
                    adipisicing elit.
                    Reiciendis, in provident quasi
                    doloremque
                    deserunt est et tenetur quis doloribus cum a consequatur ducimus iure autem soluta impedit quaerat! Quod,
                    ea?</p>
                    <p class="border-[1px] border-gray-200 rounded shadow-sm p-2 text-gray-500">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsa libero doloremque vero ad, ullam deserunt
                    nam,
                    magnam repellendus magni non vel dignissimos architecto numquam blanditiis animi explicabo, amet aut.
                    Libero.
                    </p>
                    <p class="border-[1px] border-gray-200 rounded shadow-sm p-2 text-gray-500">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsa libero doloremque vero ad, ullam deserunt
                    nam,
                    magnam repellendus magni non vel dignissimos architecto numquam blanditiis animi explicabo, amet aut.
                    Libero.
                    </p>
                    <p class="border-[1px] border-gray-200 rounded shadow-sm p-2 text-gray-500">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsa libero doloremque vero ad, ullam deserunt
                    nam,
                    magnam repellendus magni non vel dignissimos architecto numquam blanditiis animi explicabo, amet aut.
                    Libero.
                    </p>
                </div>
                </div>
                {{-- 2 --}}
                <div
                class="w-1/2 h-auto flex flex-col justify-center items-center rounded-xl mt-4 mx-4 border-[1px] border-gray-100">
                <span
                    class="w-full h-[56px] flex felx-row justify-between items-center bg-gray-100 text-gray-500 mb-2 px-3 rounded-t-xl">
                    <h3>Feedback recent</h3>
                    <button
                    class="text-purple-500 hover:bg-purple-100 hover:border-[1px] hover:border-purple-500 rounded py-1 px-2">Clear
                    all</button>
                </span>
                <div class="w-[320px] h-auto text-center flex flex-col gap-4">
                    <p class="border-[1px] border-gray-200 rounded shadow-sm p-2 text-gray-500">Lorem ipsum dolor sit, amet
                    consectetur
                    adipisicing elit.
                    Reiciendis, in provident quasi
                    doloremque
                    deserunt est et tenetur quis doloribus cum a consequatur ducimus iure autem soluta impedit quaerat! Quod,
                    ea?</p>
                    <p class="border-[1px] border-gray-200 rounded shadow-sm p-2 text-gray-500">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsa libero doloremque vero ad, ullam deserunt
                    nam,
                    magnam repellendus magni non vel dignissimos architecto numquam blanditiis animi explicabo, amet aut.
                    Libero.
                    </p>
                    <p class="border-[1px] border-gray-200 rounded shadow-sm p-2 text-gray-500">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsa libero doloremque vero ad, ullam deserunt
                    nam,
                    magnam repellendus magni non vel dignissimos architecto numquam blanditiis animi explicabo, amet aut.
                    Libero.
                    </p>
                    <p class="border-[1px] border-gray-200 rounded shadow-sm p-2 text-gray-500">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsa libero doloremque vero ad, ullam deserunt
                    nam,
                    magnam repellendus magni non vel dignissimos architecto numquam blanditiis animi explicabo, amet aut.
                    Libero.
                    </p>
                </div>
                </div>
            </div>
            </div>

            {{-- Notifications de récents activités --}}
            <div class="w-1/4 flex justify-center items-start mx-2">
            <div class="w-[480px] flex flex-col gap-6 h-auto rounded m-2">
                {{-- card1 --}}
                <div class="card border-gray-100 flex justify-center items-start rounded bg-gray-100/50 shadow-sm px-4 py-4">
                <span class="flex flex-row gap-3 text-xl text-gray-500">
                    <i class="fa-solid fa-circle-dollar-to-slot text-gray-400 text-xl"></i>
                    Paramètres des unités monaitaire
                </span>
                <div class="accordion-content w-full flex justify-center items-center" style="display: none;">
                    <div class="w-full flex flex-col gap-2 justify-center items-center mt-3">

                    <button class="h-auto shadow-sm p-2 rounded-md text-gray-500 text-base font-normal mb-2">

                    </button>
                    </div>
                </div>
                </div>
                {{-- card 2 --}}
                <div class="card border-gray-100 flex justify-center items-start rounded bg-gray-100/50 shadow-sm px-4 py-4">
                <span class="flex flex-row gap-3 text-xl text-gray-500">
                    <i class="fa-solid fa-comments-dollar text-gray-400 text-xl"></i>
                    Paramètres des Taux et TVA
                </span>
                <div class="accordion-content" style="display: none;">
                    <!-- Content for card  2 -->
                    <!-- Add your content here -->
                    This is the content for card 2.
                </div>
                </div>
                {{-- card 3 --}}
                <div class="card border-gray-100 flex justify-center items-start rounded bg-gray-100/50 shadow-sm px-4 py-4">
                <span class="flex flex-row gap-3 text-xl text-gray-500">
                    <i class="fa-solid fa-scale-balanced text-gray-400 text-xl"></i>
                    Code fiscal
                </span>
                <div class="accordion-content" style="display: none;">
                    <!-- Content for card  2 -->
                    <!-- Add your content here -->
                    This is the content for card 3.
                </div>
                </div>
                {{-- card 4 --}}
                <div class="card border-gray-100 flex justify-center items-start rounded bg-gray-100/50 shadow-sm px-4 py-4">
                <span class="flex flex-row gap-3 text-xl text-gray-500">
                    <i class="fa-solid fa-screwdriver-wrench text-gray-400 text-xl"></i>
                    Agenda
                </span>
                <div class="accordion-content" style="display: none;">
                    <input type="date" name="" id="">
                    <input type="text" name="" id="">
                </div>
                </div>
                {{-- Fin card --}}
            </div>
            </div>
        </div>
    </div>
@endsection
