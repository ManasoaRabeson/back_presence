@php
    $click ??= '';
@endphp
@if ($click === 'client')
    <div class="dropdown">
        <button
            class="text-lg text-gray-700 font-semibold px-2 py-1 scale-105 transition duration-200 outline-none uppercase"
            type="button">
            <a class="dropdown-item text-lg text-gray-700 font-semibold px-2 py-1 rounded-md hover:bg-gray-50 focus:bg-[#A462A4] focus:text-white transition duration-150 uppercase"
                href="{{ route('ReportingClientEtp') }}">
                Centre de Formation
            </a>
        </button>
    </div>
@endif
