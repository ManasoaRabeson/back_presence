@extends('layouts.masterGuest')

@section('content')
    <div class="space-y-6 bg-white rounded-lg">
        <section class="py-16 antialiased bg-white dark:bg-gray-900">
            <div class="max-w-4xl mx-auto">

                <h2 class="mb-2 text-xl font-semibold text-gray-700 sm:text-2xl">Votre reservation a été
                    envoyée à {{ $reservation_data['customer_name'] }}.</h2>
                <p class="mb-6 text-gray-500 dark:text-gray-400 md:mb-8">Votre reservation de <span
                        class="font-medium text-gray-700">{{ $reservation_data['nbPlace'] }}
                        places</span> sera traitée dans les 24 heures pendant les jours ouvrables. Vous recevrez un
                    e-mail de confirmation dès que votre réservation sera validée.</p>
                <div
                    class="p-6 mb-6 space-y-4 border border-gray-100 rounded-lg sm:space-y-2 bg-gray-50 dark:border-gray-700 dark:bg-gray-800 md:mb-8">
                    <dl class="items-center justify-between gap-4 sm:flex">
                        <dt class="mb-1 font-normal text-gray-500 sm:mb-0 dark:text-gray-400">Nombre de place</dt>
                        <dd class="font-medium text-gray-900 dark:text-white sm:text-end">
                            {{ $reservation_data['nbPlace'] }}</dd>
                    </dl>
                    <dl class="items-center justify-between gap-4 sm:flex">
                        <dt class="mb-1 font-normal text-gray-500 sm:mb-0 dark:text-gray-400">Prix total</dt>
                        <dd class="font-medium text-gray-900 dark:text-white sm:text-end">
                            {{ number_format($reservation_data['prix_total'], 2, ',', ' ') }} Ar HT</dd>
                    </dl>
                    <dl class="items-center justify-between gap-4 sm:flex">
                        <dt class="mb-1 font-normal text-gray-500 sm:mb-0 dark:text-gray-400">Cours</dt>
                        <dd class="font-medium text-gray-900 dark:text-white sm:text-end">
                            {{ $reservation_data['module_name'] }}</dd>
                    </dl>
                    <dl class="items-center justify-between gap-4 sm:flex">
                        <dt class="mb-1 font-normal text-gray-500 sm:mb-0 dark:text-gray-400">Session</dt>
                        <dd class="font-medium text-gray-900 dark:text-white sm:text-end">
                            {{ $reservation_data['date_begin'] }} - {{ $reservation_data['date_end'] }}</dd>
                    </dl>
                    <dl class="items-center justify-between gap-4 sm:flex">
                        <dt class="mb-1 font-normal text-gray-500 sm:mb-0 dark:text-gray-400">Lieu</dt>
                        <dd class="font-medium text-gray-900 dark:text-white sm:text-end">
                            {{ $reservation_data['ville'] }}</dd>
                    </dl>
                    <dl class="items-center justify-between gap-4 sm:flex">
                        <dt class="mb-1 font-normal text-gray-500 sm:mb-0 dark:text-gray-400">Centre de formation</dt>
                        <dd class="font-medium text-gray-900 dark:text-white sm:text-end">
                            {{ $reservation_data['customer_name'] }}</dd>
                    </dl>
                </div>
                <div class="flex flex-col items-center justify-center space-y-2 lg:flex-row lg:space-y-0 lg:space-x-4">
                    <a href="{{ url('formation/search?project=&category=all&place=all') }}"
                        class="py-2.5 px-5 text-sm font-medium text-gray-700 focus:outline-none bg-white rounded-lg border border-gray-200 hover:text-gray-500">Explorer
                        nos autres formations</a>
                    <a href="{{ route('project.inter') }}"
                        class="py-2.5 px-5 text-sm font-medium text-gray-700 focus:outline-none bg-white rounded-lg border border-gray-200 hover:text-gray-500">Accéder
                        à vos réservations</a>
                    <a href="{{ route('exportInvoicePublic', $reservation_data['invoice_id']) }}"
                        class="py-2.5 px-5 text-sm font-medium text-gray-700 focus:outline-none bg-white rounded-lg border border-gray-200 hover:text-gray-500">
                        Exporter la facture
                    </a>
                </div>
            </div>
        </section>
    </div>
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
