<section
    id="start-study"
    class="scroll-mt-[74px] bg-white px-6 py-[76px] md:px-10 lg:px-0"
>
    <div class="mx-auto w-full max-w-[980px] text-center">

        <h2
            class="text-[28px] font-medium leading-tight tracking-[-0.6px] text-[#242424]"
        >
            What do you want to test?
        </h2>

        <p
            class="mx-auto mt-[16px] max-w-[560px] text-[13px] leading-[1.5] text-[#686868]"
        >
            Choose the area you want insights on. We’ll recommend the best research methods for you.
        </p>

        @php
            $cards = [
                [
                    'Product Research',
                    'Product',
                    'Understand which features or options matter most to your customers.'
                ],
                [
                    'Pricing Research',
                    'Pricing',
                    'Find the right price point people are willing to pay.'
                ],
                [
                    'Concept Research',
                    'Concept',
                    'Test new ideas or products before launching them.'
                ],
                [
                    'Brand Research',
                    'Brand',
                    'Measure how people see, recognize, and trust your brand.'
                ],
                [
                    'UX Research',
                    'UX',
                    'Improve how users interact with your website or app.'
                ],
                [
                    'Not sure',
                    'Not sure',
                    'Not sure what you need? We’ll help you choose the right approach.'
                ],
            ];
        @endphp

        <div
            class="mx-auto mt-[76px] grid max-w-[790px] gap-[22px] text-left md:grid-cols-3"
        >
            @foreach ($cards as $card)
                <button
                    type="button"
                    @click="goal = '{{ $card[0] }}'"
                    :class="goal === '{{ $card[0] }}'
                        ? 'border-[#4D7FCF] bg-[#F7F9FD] ring-1 ring-[#4D7FCF]'
                        : 'border-transparent bg-[#F5F5F5]'"
                    class="min-h-[112px] rounded-[5px] border px-[18px] py-[18px] transition hover:border-[#4D7FCF]"
                >
                    <h3 class="text-[14px] font-medium text-[#242424]">
                        {{ $card[1] }}
                    </h3>

                    <p class="mt-[22px] text-[11px] leading-[1.35] text-[#5F5F5F]">
                        {{ $card[2] }}
                    </p>
                </button>
            @endforeach
        </div>

        <button
            type="button"
            @click="scrollTo('method')"
            class="mt-[72px] h-[42px] w-[240px] rounded-[3px] bg-[#4D7FCF] text-[13px] font-medium text-white transition hover:bg-[#416FB4]"
        >
            Next
        </button>
    </div>
</section>