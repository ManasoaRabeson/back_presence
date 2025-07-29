<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forma-Fusion</title>

    <script src="{{ asset('js/tailwind.js') }}"></script>
    <script src="https://www.google.com/recaptcha/enterprise.js?render={{ env('GOOGLE_RECAPTCHA_SECRET') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/bootstrapIcons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/fontAwesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery.raty.css') }}">
    <link rel="stylesheet" href="{{ asset('css/daisyUI.min.css') }}">
    @stack('custom_style')

    {{-- favicon --}}
    <link rel="icon" href="{{ asset('img/logo/Logo_mark.svg') }}" type="image/x-icon">
</head>

<body>

    <div class="flex flex-col w-screen h-screen overflow-y-scroll bg-white">
        @include('layouts.navbars.navbarOtherUser')
        <div class="w-full h-full pt-10 my-4 lg:pt-32">
            @yield('content')
        </div>
    </div>

    {{-- Modal Deconnexion --}}
    <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true" id="logout">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-white border-none p-3 justify-center gap-2 rounded-md" id="lottieAnimation">
                <div class="flex flex-col rounded items-center">
                    <lottie-player src="{{ asset('Animations/Logout.json') }}" background="transparent" speed="1"
                        style="width: 60px; height: 50px;" loop autoplay></lottie-player>
                    <h1 class="text-gray-700 text-2xl font-semibold flex flex-1" id="staticBackdropLabel">Deconnexion
                    </h1>
                </div>
                <p class="text-base text-gray-600 text-center px-4">Voulez-vous vraiment vous deconnectez ?</p>
                <div class="p-3 inline-flex gap-3 justify-center">
                    <button type="button"
                        class="border-[1px] border-gray-600 text-gray-500 text-base hover:text-gray-700 scale-95 hover:scale-100 rounded-md px-4 py-2 transition duration-200"
                        data-bs-dismiss="modal" data-bs-dismiss="tooltip">Non,
                        annuler</button>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();"
                        class="bg-[#A462A4] text-white text-base rounded-md px-4 py-2 scale-95 hover:scale-100 hover:bg-[#A462A4]/80 transition duration-200"
                        data-bs-dismiss="modal">Oui, deconnexion</a>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/jquery-3.6.1.min.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    @yield('script')
</body>
