@php
    $id ??= '';
    $icon ??= 'folder-open';
    $text ??= 'Projets';
    $nb ??= '';
    $href ??= '#';
    $description ??= '';
@endphp

<div class="indicator">
    <span class="indicator-item badge bg-slate-600 text-white">{{ $nb }}</span>
    <a id="{{ $id }}" href="{{ $href }}" title="{{ $description }}"
        class="btn bg-slate-100 hover:bg-slate-200 relative text-slate-600 hover:text-slate-600"><i
            class="{{ $icon }}"></i></a>
</div>
