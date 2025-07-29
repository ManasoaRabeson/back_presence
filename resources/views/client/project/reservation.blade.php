@extends($extends_containt)

@section('content')
    <div class="px-6 py-6 mx-4 mb-10 space-y-6 bg-white rounded-lg md:px-14 lg:px-16 xl:px-20 lg:mx-20 xl:mx-60 mt-28">
        <div class="flex flex-col items-center">
            <p class="text-xl font-semibold">{{ $module_name }}</p>
            <p>Le {{ $date_begin }} - {{ $date_end }}</p>
        </div>
        <form action="{{ route('reservation.store') }}" method="post" class="space-y-6">
            @csrf
            <div class="space-y-3">
                <p>NOMBRE DE PLACES</p>
                <input type="number"
                    class="outline-none w-full bg-transparent pl-2 h-10 border-[1px] border-gray-200 rounded-md hover:border-purple-300 focus:border-purple-300 focus:ring-2 focus:ring-purple-100 duration-200 text-gray-400"
                    name="nbPlace" required>
            </div>

            <div class="flex flex-col items-center justify-center space-y-2">
                <button class="rounded-full px-4 py-2 bg-[#a462a4] text-white text-center" type="submit">Reserver ma
                    place</button>
            </div>
        </form>
    </div>

    @include('layouts.homeFooter')
@endsection

@section('script')
    <script>
        $('#menu-button').on('click', function() {
            const dropdownMenu = $('#dropdown-menu');
            dropdownMenu.classList.toggle('hidden');
            dropdownMenu.classList.toggle('block');
        });

        window.addEventListener('click', function(event) {
            if (!event.target.closest('#menu-button') && !event.target.closest('#dropdown-menu')) {
                $('#dropdown-menu').addClass('hidden')
                $('#dropdown-menu').removeClass('block')
            }
        });
    </script>
@endsection
