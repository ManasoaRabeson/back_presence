@php
    $click ??= '';
@endphp
@if ($click === 'client')
    <div class="dropdown">
        <button
            class="text-gray-700 flex items-center px-2 py-1 scale-105 transition duration-200 outline-none"
            type="button">
            <a class="dropdown-item text-gray-700 px-2 py-1 rounded-md hover:bg-gray-50 focus:bg-[#A462A4] focus:text-white transition duration-150"
                href="{{ route('reporting.filter.client') }}"> <i class="fa-solid fa-handshake mr-2"></i> Clients
            </a>
        </button>
    </div>
@endif
