<section id="start-study" class="bg-white py-20">
    <div class="mx-auto max-w-[980px] px-6 text-center">
        <img src="{{ asset('images/logo.svg') }}" alt="Impala" class="mx-auto h-[56px] w-auto">

        <x-stepper :active="1" />

        <h2 class="mt-8 text-[30px] font-bold text-[#2F2F32]">
            What do you want to test?
        </h2>

        <p class="mx-auto mt-3 max-w-[560px] text-[14px] leading-[1.5] text-[#6F6F6F]">
            Choose the area you want insight into. We’ll recommend the best research methods for you.
        </p>

        <div class="mt-10 grid gap-6 md:grid-cols-3">
            @php
                $cards = [
                    ['Product Research', 'Product', 'Understand which features or options matter most to your customers.'],
                    ['Pricing Research', 'Pricing', 'Find the right price point people are willing to pay.'],
                    ['Concept Research', 'Concept', 'Test new ideas or products before launching them.'],
                    ['Brand Research', 'Brand', 'Measure how people see, recognize, and trust your brand.'],
                    ['UX Research', 'UX', 'Improve how users interact with your website or app.'],
                    ['Not sure', 'Not sure', 'Not sure what you need? We’ll help you choose the right approach.'],
                ];
            @endphp

            @foreach ($cards as $card)
                <button
                    type="button"
                    @click="goal = '{{ $card[0] }}'"
                    :class="goal === '{{ $card[0] }}' ? 'border-[#33B6FF] ring-2 ring-[#33B6FF]' : 'border-[#CFCFCF]'"
                    class="min-h-[108px] rounded-[4px] border bg-white p-6 text-left transition hover:border-[#33B6FF] hover:shadow-sm"
                >
                    <h3 class="text-[18px] font-medium text-[#2F2F32]">{{ $card[1] }}</h3>
                    <p class="mt-5 text-[12px] leading-[1.35] text-[#555555]">{{ $card[2] }}</p>
                </button>
            @endforeach
        </div>

        <button
            type="button"
            @click="scrollTo('method')"
            class="mt-9 h-[46px] w-[260px] rounded-[3px] bg-[#33B6FF] text-[15px] font-medium text-white transition hover:bg-[#159fe4]"
        >
            Next
        </button>
    </div>
</section>