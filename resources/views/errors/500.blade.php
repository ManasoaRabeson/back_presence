<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="{{ asset('js/tailwind.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('fonts/fontAwesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/daisyUI.min.css') }}">
    <title>Document</title>
</head>

<body>
    <div
        class="flex flex-col-reverse items-center justify-center gap-16 px-4 py-24 lg:px-24 lg:py-24 md:py-20 md:px-44 lg:flex-row md:gap-28">
        <div class="relative w-full pb-12 xl:pt-24 xl:w-1/2 lg:pb-0">
            <div class="relative">
                <div class="absolute">
                    <div class="flex flex-col gap-2">
                        <h1 class="my-2 text-2xl font-bold text-gray-800">
                            Désolé, une erreur s'est produite !
                        </h1>
                        <p class="my-2 text-gray-800">Il y a un problème au niveau du serveur. Veuillez réessayer plus
                            tard !</p>
                        <a href="{{ url()->previous() }}"
                            class="sm:w-full lg:w-auto my-2 border rounded md py-4 px-8 text-center bg-[#A462A4]/90 text-white hover:bg-[#A462A4] focus:outline-none focus:ring-2 focus:ring-[#A462A4] focus:ring-opacity-50">Revenir
                            à la page précedente!</a>
                    </div>
                </div>
                {{-- <div>
                    <img src="https://i.ibb.co/G9DC8S0/404-2.png" />
                </div> --}}
            </div>
        </div>
        <div>
            <img src="https://i.ibb.co/ck1SGFJ/Group.png" />
        </div>
    </div>
</body>

</html>
