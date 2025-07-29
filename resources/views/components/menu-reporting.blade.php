@php
    $click ??= '';
@endphp
@if ($click === 'formation')
    <div class="dropdown">
        <button
            class="text-gray-700 px-2 py-1 scale-105 transition duration-200 outline-none"
            type="button">
            <a class="dropdown-item text-gray-700 px-2 py-1 rounded-md hover:bg-gray-50 focus:bg-[#A462A4] focus:text-white transition duration-150"
                href="{{ route('ReportingFormation') }}">
                <i class="fa-solid fa-graduation-cap"></i>
                Formation
            </a>
        </button>
    </div>
@endif
