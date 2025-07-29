@php
    $color ??= '';
@endphp
{{-- <a href="{{ route('abonnement.su', $plan->id) }}"
    class="card flex flex-row mt-4 justify-between items-center text-[{{ $color }}] border border-[{{ $color }}] rounded-xl w-96 transform transition-transform duration-50 hover:scale-105 cursor-pointer">
    <div class="px-6">
        <div class="flex flex-row justify-center items-center">
            <div class="flex w-[110px] h-[40px] justify-center items-center rounded-full bg-gray-50 shadow-sm">
                <div
                    class="fa-solid fa-gem 2xl:text-xl md:text-xl bg-[{{ $color }}] text-transparent bg-clip-text">
                </div>
                <p class="ml-2 text-sm font-bold text-[{{ $color }}]">
                    <span id="price">{{ number_format($plan->price, 0, ',', ' ') }}</span> {{ $plan->currency }}/mois
                </p>
            </div>
        </div>
    </div>
    <div class="flex flex-col justify-center items-center">
        <div class="p-6 flex flex-1 justify-center items-center gap-3">
            <h5
                class="font-sans text-lg font-semibold antialiased tracking-normal text-[{{ $color }}] capitalize">
                {{ $plan->name }}</h5>
            <p class="text-xl font-bold text-[{{ $color }}]">{{ $plan->subscriptions_count }}</p>
        </div>
    </div>
</a> --}}


<tr class="cursor-pointer" onclick="window.location.href ='{{ route('abonnement.su', $plan->id) }}'">
    <td class="capitalize" class="px-6 py-4 text-left">{{ $plan->name }}</td>
    <td class="px-6 py-4 text-left">{{ number_format($plan->price, 0, ',', ' ') }}</span> {{ $plan->currency }}/mois</td>
    <td class="px-6 py-4 text-right">{{ $plan->subscriptions_count }}</td>
</tr>
