<section
    id="method"
    class="scroll-mt-[78px] bg-white px-6 py-[110px]"
>
    <div class="mx-auto w-full max-w-[1120px] text-center">

        {{-- Heading --}}
        <h2
            class="text-[28px] font-medium leading-tight tracking-[-0.6px] text-[#242424]"
        >
            Recommended methods for Product Research
        </h2>

        {{-- Description --}}
        <p
            class="mx-auto mt-[16px] max-w-[520px] text-[13px] leading-[1.5] text-[#686868]"
        >
            Based on your selection, these methods will give you the most relevant insights.
        </p>

        @php
            $methods = [
                [
                    'Eye Tracking',
                    'Understand where people look, what captures attention, and how visual elements are viewed.'
                ],
                [
                    'Clicks',
                    'Tracks user interactions to understand engagement, navigation behavior, and decision-making.'
                ],
                [
                    'Emotion Measurement',
                    'Analyzes emotional reactions to brands, ads, products, or experiences.'
                ],
                [
                    'Surveys',
                    'Collects direct consumer feedback, opinions, attitudes, and purchase intentions.'
                ],
                [
                    'RTM (Reaction Time Measurement)',
                    'Measures reaction speed to understand automatic responses, associations, and decision-making.'
                ],
            ];
        @endphp

        {{-- Method cards --}}
        <div
            class="mx-auto mt-[76px] grid max-w-[790px] gap-[12px] md:grid-cols-2"
        >
            @foreach ($methods as $index => $item)

                <button
                    type="button"

                    @click="
                        if (methods.includes('{{ $item[0] }}')) {
                            methods = methods.filter(
                                item => item !== '{{ $item[0] }}'
                            );
                        } else {
                            methods.push('{{ $item[0] }}');
                        }
                    "

                    :class="
                        methods.includes('{{ $item[0] }}')
                            ? 'border-[#4D7FCF] bg-[#F7F9FD] ring-1 ring-[#4D7FCF]'
                            : 'border-transparent bg-[#F5F5F5]'
                    "

                    class="
                        min-h-[92px]
                        rounded-[5px]
                        border
                        px-[20px]
                        py-[18px]
                        transition
                        hover:border-[#4D7FCF]
                        {{ $index === 4 ? 'md:col-span-2' : '' }}
                    "
                >

                    <div class="flex h-full items-start justify-between gap-4">

                        {{-- LEFT ALIGNED CONTENT --}}
                        <div class="flex-1 text-left">

                            <h3
                                class="text-left text-[14px] font-medium text-[#242424]"
                            >
                                {{ $item[0] }}
                            </h3>

                            <p
                                class="mt-[24px] text-left text-[11px] leading-[1.35] text-[#5F5F5F]"
                            >
                                {{ $item[1] }}
                            </p>

                        </div>

                        {{-- Checkbox --}}
                        <span
                            class="
                                mt-[1px]
                                flex
                                h-[18px]
                                w-[18px]
                                shrink-0
                                items-center
                                justify-center
                                rounded-[3px]
                                border
                            "

                            :class="
                                methods.includes('{{ $item[0] }}')
                                    ? 'border-[#4D7FCF] bg-[#4D7FCF]'
                                    : 'border-[#BFBFBF] bg-white'
                            "
                        >

                            <svg
                                x-show="methods.includes('{{ $item[0] }}')"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                                class="h-[12px] w-[12px] text-white"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M16.704 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3.25-3.25a1 1 0 011.414-1.414l2.543 2.543 6.543-6.543a1 1 0 011.414 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>

                        </span>

                    </div>

                </button>

            @endforeach
        </div>

        {{-- Next button --}}
        <button
            type="button"
            @click="scrollTo('study-details')"
            class="
                mt-[30px]
                h-[42px]
                w-[240px]
                rounded-[3px]
                bg-[#4D7FCF]
                text-[13px]
                font-medium
                text-white
                transition
                hover:bg-[#416FB4]
            "
        >
            Next
        </button>

    </div>
</section>