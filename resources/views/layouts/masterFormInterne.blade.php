<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forma-Fusion</title>

    {{-- Style --}}
    <script src="{{ asset('js/tailwind.js') }}"></script>
    <script src="https://www.google.com/recaptcha/enterprise.js?render={{ env('GOOGLE_RECAPTCHA_SECRET') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/bootstrapIcons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/fontAwesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jQuery_wizard/smart_wizard_all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/bs-stepper.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery.raty.css') }}">
    @stack('custom_style')
    <style>
        .custom-tooltip-enligne {
            --bs-tooltip-bg: #A855F7;
            --bs-tooltip-color: white;
        }

        .custom-tooltip-default {
            --bs-tooltip-bg: #374151;
            --bs-tooltip-color: white;
        }

        .breadcrumb {
            margin-bottom: 0px !important;
            padding: 8px 0;
        }
    </style>

    <link rel="icon" href="{{ asset('img/logo/Logo_mark.svg') }}" type="image/x-icon">
</head>


<body>

    <div class="w-screen h-screen min-[360px]:hidden sm:block relative">
        <div class="w-full inline-flex items-start h-full">
            <x-sidebar-form-interne />
            {{-- NAVBAR --}}
            <div class="flex flex-col absolute left-[50px] w-[calc(100%-50px)]">
                <div class="shadow-sm w-full">
                    @include('layouts.navbars.navbarFormateurInterne')
                </div>
                <div class="max-h-screen overflow-y-auto max-w-screen w-full mt-[90px]">
                    <div class="flex flex-row h-full w-full">
                        @yield('content')
                    </div>
                </div>
            </div>
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
    <script src="{{ asset('js/sidebar-drawer.js') }}"></script>
    <script src="{{ asset('js/collapse.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.13.3/jquery-ui.min.js"
        integrity="sha256-sw0iNNXmOJbQhYFuC9OF2kOlD5KQKe1y5lfBn4C9Sjg=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/jquery.number.min.js') }}"></script>
    <script src="{{ asset('js/sideBarProject.js') }}"></script>

    {{-- Moment JS --}}
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/datepicker/datepicker.all.min.js') }}"></script>
    <script src="{{ asset('js/datepicker/datepicker.en.js') }}"></script>
    <script src="{{ asset('js/jquery.mask.min.js') }}"></script>

    <script src="{{ asset('js/global_js.js') }}"></script>
    <script src="{{ asset('js/customer-sidebar-form.js') }}"></script>
    <script src="{{ asset('js/jquery.raty.js') }}"></script>
    <script src="{{ asset('js/customer-sidebar.js') }}"></script>

    @yield('script')
</body>
