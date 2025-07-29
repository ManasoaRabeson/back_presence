@php
    $titre ??= '';
    $url ??= '';
    $type ??= '';
    $action ??= '';
    $onClick ??= '';
    $id ??= '';
@endphp

@if ($type === 'lien')
    <li>
        <a class="dropdown-item hover:bg-gray-50 text-base text-gray-600 focus:bg-gray-500 focus:text-white transition duration-150"
            href="{{ $url }}">
            {{ $titre }}
        </a>
    </li>
@elseif($type === 'buttonDelete')
    <li class="z-50">
        <form action="{{ $action }}" method="post">
            @csrf
            @method('delete')
            <button type="submit"
                class="dropdown-item hover:bg-gray-50 text-base text-gray-600 focus:bg-gray-500 focus:text-white transition duration-150 cursor-pointer">
                {{ $titre }}
            </button>
        </form>
    </li>
@elseif ($type === 'customBtn')
    <li>
        <button onclick="{{ $onClick }}" type="button"
            class="dropdown-item hover:bg-gray-50 text-base text-gray-600 focus:bg-gray-500 focus:text-white transition duration-150 cursor-pointer">
            {{ $titre }}
        </button>
    </li>
@elseif ($type === 'drawer')
    <li>
        <a class="dropdown-item hover:bg-gray-50 text-base text-gray-600 focus:bg-gray-500 focus:text-white transition duration-150 cursor-pointer"
            data-bs-toggle="offcanvas" href="#{{ $id }}" role="button" aria-controls="offcanvas">
            {{ $titre }}
        </a>
    </li>
@endif
