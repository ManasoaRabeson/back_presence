@php
    $click ??= '';
@endphp
@if ($click === 'cours')
    <div class="dropdown">
        <button
            class="text-gray-700  px-2 py-1 scale-105 transition duration-200 outline-none"
            type="button">
            <a class="dropdown-item text-gray-700 px-2 py-1 flex items-center rounded-md hover:bg-gray-50 focus:bg-[#A462A4] focus:text-white transition duration-150"
                href="{{ route('reporting.filter.cours') }}"> <i class="fa-solid fa-puzzle-piece mr-2"></i> Cours
            </a>
        </button>
    </div>
@endif