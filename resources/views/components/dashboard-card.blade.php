<div
    class="grid col-span-1 rounded-xl border-[1px] border-gray-100 shadow-sm hover:!shadow-lg bg-white w-full mx-auto xl:w-[25rem] h-[10rem] flex flex-col px-4 py-2 hover:-translate-y-1.5 duration-200">
    <span class="inline-flex items-center justify-between w-full">
        <h5 class="text-lg text-slate-400">{{ $title }}</h5>
        @isset($icon)
            <i class="fa-solid fa-{{ $icon }} text-[#A462A4] text-xl"></i>
        @else
            <p class="text-xl font-bold text-[#A462A4]">
                {{ $initial }}</p>
        @endisset
    </span>

    <span class="inline-flex items-center justify-between w-full">
        @if ($type == 'number')
            <h5 class="text-3xl font-semibold text-slate-700">{{ $new }}</h5>
            <div class="inline-flex">
                <span
                    class="
                    @if($old > $new)
                        text-[#ef4444]
                    @elseif($old < $new)
                        text-[#22c55e]
                    @else
                        text-[#3b82f6]
                    @endif">
                    @if ($old > $new)
                        <i class="bi bi-caret-down-fill"></i>
                    @elseif ($old < $new)
                        <i class="bi bi-caret-up-fill"></i>
                    @endif
                </span>
                <p class="ml-3 text-slate-400">{{ number_format(abs( $old == 0 ? 0 : (($new - $old) / $old) * 100), 2) }}%</p>
            </div>
        @elseif ($type == 'opportunity')
            <h5 class="text-3xl font-semibold text-slate-700">{{ DashboardFormat::formatPrice($new) }}</h5>
            
        @else
            <h5 class="text-3xl font-semibold text-slate-700">{{ DashboardFormat::formatPrice($new) }}</h5>
            <div class="inline-flex">
                <span
                    class="
                    @if($old > $new)
                        text-[#ef4444]
                    @elseif($old < $new)
                        text-[#22c55e]
                    @else
                        text-[#3b82f6]
                    @endif">
                    @if ($old > $new)
                        <i class="bi bi-caret-down-fill"></i>
                    @elseif ($old < $new)
                        <i class="bi bi-caret-up-fill"></i>
                    @endif
                </span>
                <p class="ml-3 text-slate-400">{{ number_format(abs( $old == 0 ? 0 : (($new - $old ) / $old)  * 100), 2)  }}%</p>
            </div>
        @endif
    </span>
    <span class="inline-flex items-center w-full">
        
        <h5 class="text-base text-slate-500">
            <span class="inline-flex items-center">
                @if ($old != 0)
                    @if ($type == 'number')
                        <p class="mr-3 text-slate-400">{{ $old }}</p>
                    @elseif ($type == 'opportunity')
                        <p class="mr-3 text-slate-400">{{ $old }}</p>
                    @else
                        <p class="mr-3 text-slate-400">{{ DashboardFormat::formatPrice($old) }}</p>
                    @endif
                @else
                    <p class=" mr-3 text-slate-400">-</p>
                @endif 
                 {{ $text }}
            </span>
        </h5>
    </span>
</div>
