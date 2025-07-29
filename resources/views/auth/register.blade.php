@extends('layouts.masterGuest')

@push('custom_style')
    <style>
        input[type="text"] {
            height: 35px;
            font-size: 16px;
        }
    </style>
@endpush

@section('content')
    <form action="{{ route('register.customer.store') }}" method="POST" class="">

        <div class="container mx-auto h-full px-1 pt-4 ">

            <div
                class="flex flex-col xl:flex-row xl:mx-0 sm:text-4xl xl:w-auto xl:mx-2 mt-20 lg:mt-24 bg-[#f1f1f4] rounded-xl">

                <div class="w-full xl:w-2/4 xl:px-24 px-10 lg:mt-14 mt-4 lg:mb-20">
                    <img src="/img/logo/Logo_mark.svg" alt="Logo"
                        class="w-28 h-28 lg:w-20 lg:h-20 lg:-ml-6 mx-auto lg:mx-0 sm:mx-auto sm:block">
                    <h1
                        class="text-xl md:text-4xl lg:mt-1 mt-4 font-extrabold text-[#A462A4] leading-tight text-center xl:text-left">
                        Inscrivez-vous
                    </h1>

                    <div class="">
                        @csrf
                        @if (Session::has('error'))
                            <p class="text-base alert alert-danger">{{ Session('error') }}</p>
                        @endif
                        <h1 class="text-xl font-extrabold mt-6">Adresse email</h1>
                        <x-input type="text" class="w-full h-20 md:h-10 mt-6" name="customer_email"
                            value="{{ Session('email') }}" placeholder=" votre-adresse@email.com" />
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('login') }}"
                            class="text-[#a462a4] underline text-lg underline-offset-4 hover:text-[#8a3b8e] transition duration-300">J'ai
                            déjà
                            un compte</a>
                    </div>
                </div>

                <div
                    class="xl:w-2/4 xl:px-24 text-lg lg:mx-24 xl:mx-0 mb-6 xl:mr-10 xl:pl-10 xl:pr-20 xl:mt-10 xl:text-base px-10">
                    <p class="text-xl font-extrabold my-6">Etes-vous nouveau par ici ! Renseignez vos informations</p>
                    <select name="account_type" id="account-type" class="w-full select select-bordered">
                        <option selected disabled>--veuillez choisir votre type de compte--</option>
                        @foreach ($typeEntreprises as $typeEntreprise)
                            <option value="{{ $typeEntreprise->idTypeEtp }}">{{ $typeEntreprise->type_etp_desc }}</option>
                        @endforeach
                    </select>
                    <div class="input_fillable"></div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script src="{{ asset('js/jquery-3.6.1.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#account-type").change(function() {
                var fillableInput = $('.input_fillable');
                fillableInput.empty();

                if ($(this).val() == 1 || $(this).val() == 2 || $(this).val() == 4 || $(this).val() == 5 || $(this).val() == 6 || $(this).val() == 7 || $(this).val() == 9) {
                    fillableInput.append(`<div class="mt-4 flex flex-col gap-2">
                                              <x-input type="text" name="customer_name" label="Raison social" />
                                          </div>
                                          <div class="w-full mt-4">
                                              <x-input type="text" name="referent_name" value="" label="Nom du responsable" />
                                          </div>
                                          <div class="w-full mt-4">
                                              <x-input type="text" name="referent_firstName" value="" label="Prénom du responsable" />
                                          </div>

                                          <div class="w-full mt-4">
                                              <x-input type="password" name="password" label="Mot de passe" placeholder=" Mot de passe"/>
                                          </div>
                                          <div class="w-full mt-4">
                                              <x-input type="password" name="password_confirmation" label="Confirmation mot de passe" placeholder=" Confirmer mot de passe"/>
                                          </div>
                                          
                                          <div class="grid place-items-center my-6">
                                              <button type="submit" class="rounded-full bg-[#a462a4] px-4 py-2 text-white flex justify-center">Créer mon compte</button>
                                          </div>`);
                } else if ($(this).val() == 8) {
                    fillableInput.append(`<div class="w-full mt-4">
                                              <x-input type="text" label="Nom" name="part_name" placeholder=" Nom"/>
                                          </div>
                                          <div class="w-full mt-4">
                                              <x-input type="text" label="Prénoms" name="part_firstName" placeholder="Prénoms"/>
                                          </div>
                                          <div class="w-full mt-4">
                                              <x-input type="password" label="Mot de passe" name="password" placeholder=" Mot de passe"/>
                                          </div>
                                          <div class="w-full mt-4">
                                              <x-input type="password" label="Confirmation mot de passe" name="password_confirmation" placeholder=" Confirmer mot de passe"/>
                                          </div>
                                          <div class="grid place-items-center my-6">
                                              <button type="submit" class="rounded-full bg-[#a462a4] px-4 py-2 text-white flex justify-center">Créer mon compte</button>
                                          </div>`);
                }
            });
        });

        $('#log').ready(function() {
            function togglePasswordVisibility() {
                var eyeIcon = $(this);
                var targetId = eyeIcon.data("target");
                var passwordInput = $("#" + targetId);

                if (passwordInput.attr("type") === "password") {
                    passwordInput.attr("type", "text");
                    eyeIcon.removeClass("bi-eye-fill").addClass("bi-eye-slash-fill");
                } else {
                    passwordInput.attr("type", "password");
                    eyeIcon.removeClass("bi-eye-slash-fill").addClass("bi-eye-fill");
                }
            }

            $(".eye-icon-toggle").click(togglePasswordVisibility);
        });
    </script>
@endsection
