
@php
    use App\Models\WebsiteContent;

    $cms = fn (string $key, string $default): string =>
        WebsiteContent::getValue(
            'home',
            'study_builder',
            $key,
            $default
        ) ?? $default;

    $heading = $cms(
        'heading',
        'What do you want to test?'
    );

    $description = $cms(
        'description',
        'Choose the area you want insights on. We’ll recommend the best research methods for you.'
    );

    $nextButtonText = $cms(
        'next_button_text',
        'Next'
    );

    /*
     * Card structure:
     * [internal goal value, visible title, visible description]
     *
     * Internal goal values must remain unchanged.
     */
    $cards = [
        [
            'Product Research',
            $cms('product_title', 'Product'),
            $cms(
                'product_description',
                'Understand which features or options matter most to your customers.'
            ),
        ],
        [
            'Pricing Research',
            $cms('pricing_title', 'Pricing'),
            $cms(
                'pricing_description',
                'Find the right price point people are willing to pay.'
            ),
        ],
        [
            'Concept Research',
            $cms('concept_title', 'Concept'),
            $cms(
                'concept_description',
                'Test new ideas or products before launching them.'
            ),
        ],
        [
            'Brand Research',
            $cms('brand_title', 'Brand'),
            $cms(
                'brand_description',
                'Measure how people see, recognize, and trust your brand.'
            ),
        ],
        [
            'UX Research',
            $cms('ux_title', 'UX'),
            $cms(
                'ux_description',
                'Improve how users interact with your website or app.'
            ),
        ],
        [
            'Not sure',
            $cms('not_sure_title', 'Not sure'),
            $cms(
                'not_sure_description',
                'Not sure what you need? We’ll help you choose the right approach.'
            ),
        ],
    ];
@endphp

<section
    id="start-study"
    class="scroll-mt-[74px] bg-white px-6 py-[76px] md:px-10 lg:px-0"
>
    <div class="mx-auto w-full max-w-[980px] text-center">

        <h2 class="text-[28px] font-medium leading-tight tracking-[-0.6px] text-[#242424]">
            {{ $heading }}
        </h2>

        <p class="mx-auto mt-[16px] max-w-[560px] text-[13px] leading-[1.5] text-[#686868]">
            {{ $description }}
        </p>

        <div class="mx-auto mt-[76px] grid max-w-[790px] gap-[22px] text-left md:grid-cols-3">

            @foreach ($cards as $card)
                <button
                    type="button"
                    @click="goal = @js($card[0])"
                    :aria-pressed="goal === @js($card[0])"
                    :class="goal === @js($card[0])
                        ? 'border-[#4D7FCF] bg-[#F7F9FD] ring-1 ring-[#4D7FCF]'
                        : 'border-transparent bg-[#F5F5F5]'"
                    class="min-h-[112px] rounded-[5px] border px-[18px] py-[18px] text-left transition hover:border-[#4D7FCF]"
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
            @click="
                if (!goal) {
                    alert('Please select your research goal.');
                    return;
                }

                scrollTo('method');
            "
            class="mt-[72px] h-[42px] w-[240px] rounded-[3px] bg-[#4D7FCF] text-[13px] font-medium text-white transition hover:bg-[#416FB4]"
        >
            {{ $nextButtonText }}
        </button>

    </div>
</section>
