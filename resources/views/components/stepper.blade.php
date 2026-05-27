@props(['active' => 1])

@php
    $steps = [
        1 => 'Goal',
        2 => 'Method',
        3 => 'Audience',
        4 => 'Review',
    ];
@endphp

<div class="mx-auto mt-8 w-full max-w-[476px]">
    <div class="relative flex items-center justify-between">
        <div class="absolute left-6 right-6 top-1/2 h-[2px] -translate-y-1/2 bg-[#D9D9D9]"></div>

        <div
            class="step-line absolute left-6 top-1/2 h-[2px] -translate-y-1/2 bg-[#33B6FF] transition-all duration-700 ease-out"
            data-progress="{{ (($active - 1) / 3) * 100 }}"
            style="width: 0%"
        ></div>

        @foreach ($steps as $number => $label)
            <div class="relative z-10 flex flex-col items-center">
                <div class="{{ $number <= $active ? 'bg-[#33B6FF] text-white border-[#33B6FF]' : 'bg-white text-[#A0A0A0] border-[#D9D9D9]' }} flex h-10 w-10 items-center justify-center rounded-full border text-[15px] font-medium">
                    {{ $number }}
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-2 flex justify-between text-[12px] text-[#A0A0A0]">
        @foreach ($steps as $label)
            <span>{{ $label }}</span>
        @endforeach
    </div>
</div>