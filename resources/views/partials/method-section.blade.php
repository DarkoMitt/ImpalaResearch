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

            @foreach ($methods as $method)
                <button
                    type="button"
                    class="method-card flex min-h-[72px] w-full items-center justify-between rounded-[4px] border border-[#CFCFCF] bg-white px-6 py-4 transition hover:border-[#33B6FF] hover:shadow-sm"
                >
                    <div>
                        <h3 class="text-[17px] font-medium text-[#2F2F32]">{{ $method[0] }}</h3>
                        <p class="mt-3 text-[12px] leading-[1.35] text-[#555555]">{{ $method[1] }}</p>
                    </div>

                    <span class="method-radio h-7 w-7 rounded-full border border-[#CFCFCF]"></span>
                </button>
            @endforeach
        </div>

        <button class="mt-9 h-[46px] w-[260px] rounded-[3px] bg-[#33B6FF] text-[15px] font-medium text-white transition hover:bg-[#159fe4]">
            Next
        </button>
    </div>
</section>