
@php
    use App\Models\WebsiteContent;

    /*
     * Read only published CMS content.
     *
     * If a field has never been published,
     * retain its original navigation label.
     */
    $goalLabel = WebsiteContent::getValue(
        'home',
        'study_progress',
        'goal_label',
        'Goal'
    );

    $methodLabel = WebsiteContent::getValue(
        'home',
        'study_progress',
        'method_label',
        'Method'
    );

    $audienceLabel = WebsiteContent::getValue(
        'home',
        'study_progress',
        'audience_label',
        'Audience'
    );

    $reviewLabel = WebsiteContent::getValue(
        'home',
        'study_progress',
        'review_label',
        'Review'
    );
@endphp

<div
    x-data="{
        activeStep: 'goal',

        init() {
            const sections = [
                { id: 'start-study', step: 'goal' },
                { id: 'method', step: 'method' },
                { id: 'study-details', step: 'audience' },
                { id: 'submit-request', step: 'review' }
            ];

            const observer = new IntersectionObserver(
                (entries) => {
                    const visible = entries
                        .filter(entry => entry.isIntersecting)
                        .sort((a, b) => b.intersectionRatio - a.intersectionRatio);

                    if (!visible.length) return;

                    const current = sections.find(
                        section => section.id === visible[0].target.id
                    );

                    if (current) {
                        this.activeStep = current.step;
                    }
                },
                {
                    threshold: [0.15, 0.3, 0.45, 0.6],
                    rootMargin: '-95px 0px -45% 0px'
                }
            );

            sections.forEach(section => {
                const element = document.getElementById(section.id);

                if (element) {
                    observer.observe(element);
                }
            });
        },

        goTo(id, step) {
            this.activeStep = step;

            document.getElementById(id)?.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    }"
    x-init="init()"
    class="sticky top-0 z-40 border-b border-[#EAEAEA] bg-white/95 backdrop-blur"
>
    <div
        class="mx-auto flex h-[74px] w-full max-w-[1120px] items-center px-6 lg:px-0"
    >
        {{-- Logo --}}
        <button
            type="button"
            @click="goTo('start-study', 'goal')"
            class="shrink-0"
        >
            <img
                src="{{ asset('images/logo.svg') }}"
                alt="Impala"
                class="h-[30px] w-auto"
            >
        </button>

        {{-- Progress navigation --}}
        <div class="mx-auto w-full max-w-[520px]">
            <div class="relative">

                {{-- Base line --}}
                <div
                    class="absolute bottom-0 left-0 h-px w-full bg-[#D4D4D4]"
                ></div>

                {{-- Progress line --}}
                <div
                    class="absolute bottom-0 left-0 h-px bg-[#242424] transition-all duration-500 ease-out"
                    :style="{
                        width:
                            activeStep === 'goal' ? '25%' :
                            activeStep === 'method' ? '50%' :
                            activeStep === 'audience' ? '75%' :
                            '100%'
                    }"
                ></div>

                <div class="relative grid grid-cols-4 text-center text-[12px]">

                    {{-- Step 1: Goal --}}
                    <button
                        type="button"
                        @click="goTo('start-study', 'goal')"
                        class="pb-[11px] transition-colors"
                        :class="activeStep === 'goal'
                            ? 'font-medium text-[#242424]'
                            : 'text-[#A7A7A7]'"
                    >
                        {{ $goalLabel }}
                    </button>

                    {{-- Step 2: Method --}}
                    <button
                        type="button"
                        @click="goTo('method', 'method')"
                        class="pb-[11px] transition-colors"
                        :class="activeStep === 'method'
                            ? 'font-medium text-[#242424]'
                            : 'text-[#A7A7A7]'"
                    >
                        {{ $methodLabel }}
                    </button>

                    {{-- Step 3: Audience --}}
                    <button
                        type="button"
                        @click="goTo('study-details', 'audience')"
                        class="pb-[11px] transition-colors"
                        :class="activeStep === 'audience'
                            ? 'font-medium text-[#242424]'
                            : 'text-[#A7A7A7]'"
                    >
                        {{ $audienceLabel }}
                    </button>

                    {{-- Step 4: Review --}}
                    <button
                        type="button"
                        @click="goTo('submit-request', 'review')"
                        class="pb-[11px] transition-colors"
                        :class="activeStep === 'review'
                            ? 'font-medium text-[#242424]'
                            : 'text-[#A7A7A7]'"
                    >
                        {{ $reviewLabel }}
                    </button>

                </div>
            </div>
        </div>

        {{-- Balancing spacer --}}
        <div class="w-[88px] shrink-0"></div>
    </div>
</div>
