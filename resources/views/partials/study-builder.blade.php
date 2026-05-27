<section id="start-study" class="bg-white py-20">
    <div class="mx-auto max-w-[980px] px-6 text-center">
        <img src="{{ asset('images/logo.svg') }}" alt="Impala" class="mx-auto h-[56px] w-auto">

        <div class="mt-8 flex items-center justify-center">
            @foreach ([['1','Goal'], ['2','Method'], ['3','Audience'], ['4','Review']] as $step)
                <div class="flex items-center">
                    <div class="{{ $step[0] === '1' ? 'bg-[#33B6FF] text-white' : 'bg-white text-[#A0A0A0]' }} flex h-12 w-12 items-center justify-center rounded-full border border-[#D9D9D9] text-lg font-medium">
                        {{ $step[0] }}
                    </div>

                    @if (!$loop->last)
                        <div class="h-[2px] w-24 bg-[#D9D9D9]"></div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-1 flex justify-center gap-[82px] text-[13px] text-[#A0A0A0]">
            <span>Goal</span>
            <span>Method</span>
            <span>Audience</span>
            <span>Review</span>
        </div>

        <h2 class="mt-8 text-[30px] font-bold text-[#2F2F32]">
            What do you want to test?
        </h2>

        <p class="mx-auto mt-3 max-w-[560px] text-[14px] leading-[1.5] text-[#6F6F6F]">
            Choose the area you want insight into. We’ll recommend the best research methods for you.
        </p>

        <div class="mt-10 grid gap-6 md:grid-cols-3">
            @php
                $cards = [
                    ['Product', 'Understand which features or options matter most to your customers.'],
                    ['Pricing', 'Find the right price point people are willing to pay.'],
                    ['Concept', 'Test new ideas or products before launching them.'],
                    ['Brand', 'Measure how people see, recognize, and trust your brand.'],
                    ['UX', 'Improve how users interact with your website or app.'],
                    ['Not sure', 'Not sure what you need? We’ll help you choose the right approach.'],
                ];
            @endphp

            @foreach ($cards as $card)
                <button
                    type="button"
                    class="study-card min-h-[108px] rounded-[4px] border border-[#CFCFCF] bg-white p-6 text-left transition hover:border-[#33B6FF] hover:shadow-sm"
                >
                    <h3 class="text-[18px] font-medium text-[#2F2F32]">{{ $card[0] }}</h3>
                    <p class="mt-5 text-[12px] leading-[1.35] text-[#555555]">{{ $card[1] }}</p>
                </button>
            @endforeach
        </div>

        <button class="mt-9 h-[46px] w-[260px] rounded-[3px] bg-[#33B6FF] text-[15px] font-medium text-white transition hover:bg-[#159fe4]">
            Next
        </button>
    </div>
</section>