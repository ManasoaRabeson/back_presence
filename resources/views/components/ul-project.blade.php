@php
    $headDate ??= '';
@endphp
<ul class="menu w-full p-0 [&_li>*]:rounded-none">
    <li class="menu-title !text-2xl p-3 bg-slate-50 rounded-xl text-slate-700 capitalize">{{ $headDate }}</li>
    <section class="grid p-4 grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-3 gap-4 content" data-view="carte"
        data-val="{{ $headDate }}">
    </section>
    <section class="grid p-4 grid-cols-1 hidden gap-4 content" data-view="list" data-val="list_{{ $headDate }}">
    </section>
</ul>
