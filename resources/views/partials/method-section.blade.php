<section id="method" class="bg-white py-20">
    <div class="mx-auto max-w-[980px] px-6 text-center">
        <img src="{{ asset('images/logo.svg') }}" alt="Impala" class="mx-auto h-[56px] w-auto">

        <x-stepper :active="2" />

        <h2 class="mt-8 text-[30px] font-bold text-[#2F2F32]">
            Recommended methods for Product Research
        </h2>

        <p class="mx-auto mt-3 max-w-[550px] text-[14px] leading-[1.5] text-[#6F6F6F]">
            Based on your selection, these methods will give you the most relevant insights.
        </p>

        <div class="mx-auto mt-10 max-w-[862px] space-y-6 text-left">
            @php
                $methods = [
                    ['Conjoint Analysis', 'Understand how people value different features and find the optimal combination.'],
                    ['MaxDiff', 'Identify the most important features by having respondents choose the top preferences.'],
                    ['Concept Testing', 'Validate product ideas and concepts with your target audience before launch.'],
                ];
            @endphp

            @foreach ($methods as $item)
                <button
                    type="button"
                    @click="method = '{{ $item[0] }}'"
                    :class="method === '{{ $item[0] }}'
                        ? 'border-[#33B6FF] ring-2 ring-[#33B6FF]/20'
                        : 'border-[#CFCFCF]'"
                    class="flex min-h-[72px] w-full items-center justify-between rounded-[4px] border bg-white px-6 py-4 text-left transition hover:border-[#33B6FF]"
                >
                    <div class="pr-6">
                        <h3 class="text-[17px] font-medium leading-none text-[#2F2F32]">
                            {{ $item[0] }}
                        </h3>

                        <p class="mt-4 text-[12px] leading-[1.35] text-[#555555]">
                            {{ $item[1] }}
                        </p>
                    </div>

                    <span
                        :class="method === '{{ $item[0] }}'
                            ? 'border-[#33B6FF] bg-[#33B6FF] after:absolute after:left-1/2 after:top-1/2 after:h-[15px] after:w-[15px] after:-translate-x-1/2 after:-translate-y-1/2 after:rounded-full after:bg-white'
                            : 'border-[#CFCFCF] bg-white'"
                        class="relative shrink-0 h-7 w-7 rounded-full border"
                    ></span>
                </button>
            @endforeach
        </div>

        <button
            type="button"
            @click="scrollTo('study-details')"
            class="mt-9 h-[46px] w-[260px] rounded-[3px] bg-[#33B6FF] text-[15px] font-medium text-white transition hover:bg-[#159fe4]"
        >
            Next
        </button>
    </div>
</section>