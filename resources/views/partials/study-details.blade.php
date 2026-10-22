
@php
    $studyDetailsContent = [
        'heading' => \App\Models\WebsiteContent::getValue(
            'home',
            'study_details',
            'heading',
            'Tell us about your study'
        ),
        'description' => \App\Models\WebsiteContent::getValue(
            'home',
            'study_details',
            'description',
            'These details help us tailor the study for you.'
        ),
        'country_label' => \App\Models\WebsiteContent::getValue(
            'home',
            'study_details',
            'country_label',
            'Target Market / Country'
        ),
        'country_placeholder' => \App\Models\WebsiteContent::getValue(
            'home',
            'study_details',
            'country_placeholder',
            'Select Country'
        ),
        'audience_label' => \App\Models\WebsiteContent::getValue(
            'home',
            'study_details',
            'audience_label',
            'Target Audience'
        ),
        'audience_placeholder' => \App\Models\WebsiteContent::getValue(
            'home',
            'study_details',
            'audience_placeholder',
            'e.g US Adults 18–65'
        ),
        'sample_size_label' => \App\Models\WebsiteContent::getValue(
            'home',
            'study_details',
            'sample_size_label',
            'Sample Size'
        ),
        'timeline_label' => \App\Models\WebsiteContent::getValue(
            'home',
            'study_details',
            'timeline_label',
            'Timeline (optional)'
        ),
        'timeline_placeholder' => \App\Models\WebsiteContent::getValue(
            'home',
            'study_details',
            'timeline_placeholder',
            'Select timeline'
        ),
        'notes_label' => \App\Models\WebsiteContent::getValue(
            'home',
            'study_details',
            'notes_label',
            'Anything else we should know? (optional)'
        ),
        'notes_placeholder' => \App\Models\WebsiteContent::getValue(
            'home',
            'study_details',
            'notes_placeholder',
            'Add any specific requirements or context about your study...'
        ),
        'next_button_text' => \App\Models\WebsiteContent::getValue(
            'home',
            'study_details',
            'next_button_text',
            'Next'
        ),
    ];
@endphp

<section
    id="study-details"
    class="scroll-mt-[74px] bg-white px-6 py-[110px] md:px-10 lg:px-0"
>
    <div class="mx-auto w-full max-w-[980px] text-center">

        <h2 class="text-[28px] font-medium leading-tight tracking-[-0.6px] text-[#242424]">
            {{ $studyDetailsContent['heading'] }}
        </h2>

        <p class="mx-auto mt-[16px] max-w-[420px] text-[13px] leading-[1.5] text-[#686868]">
            {{ $studyDetailsContent['description'] }}
        </p>

        <div class="mx-auto mt-[76px] max-w-[790px] text-left">

            <div class="grid gap-x-[48px] gap-y-[24px] md:grid-cols-2">

                {{-- Country --}}
                <label class="block">
                    <span class="text-[13px] font-normal text-[#242424]">
                        {{ $studyDetailsContent['country_label'] }}
                    </span>

                    <select
                        x-model="country"
                        class="mt-[10px] h-[44px] w-full rounded-[3px] border border-[#BFBFBF] bg-white px-[14px] text-[12px] text-[#6F6F6F] outline-none transition focus:border-[#4D7FCF]"
                    >
                        <option value="">{{ $studyDetailsContent['country_placeholder'] }}</option>
                        <option value="North Macedonia">North Macedonia</option>
                        <option value="United States">United States</option>
                        <option value="Germany">Germany</option>
                        <option value="United Kingdom">United Kingdom</option>
                    </select>
                </label>

                {{-- Audience --}}
                <label class="block">
                    <span class="text-[13px] font-normal text-[#242424]">
                        {{ $studyDetailsContent['audience_label'] }}
                    </span>

                    <input
                        type="text"
                        x-model="audience"
                        maxlength="1000"
                        placeholder="{{ $studyDetailsContent['audience_placeholder'] }}"
                        class="mt-[10px] h-[44px] w-full rounded-[3px] border border-[#BFBFBF] px-[14px] text-[12px] text-[#242424] outline-none transition placeholder:text-[#8D8D8D] focus:border-[#4D7FCF]"
                    >
                </label>

                {{-- Sample Size --}}
                <label class="block">
                    <span class="text-[13px] font-normal text-[#242424]">
                        {{ $studyDetailsContent['sample_size_label'] }}
                    </span>

                    <div class="mt-[10px] flex h-[44px] items-center gap-[14px]">

                        <input
                            type="range"
                            min="1"
                            max="1000"
                            step="1"
                            x-model="sampleSize"
                            class="flex-1 accent-[#4D7FCF]"
                        >

                        <input
                            type="text"
                            inputmode="numeric"
                            maxlength="4"
                            x-model="sampleSize"
                            @input="
                                sampleSize = String(sampleSize).replace(/[^0-9]/g, '');

                                if (sampleSize === '') sampleSize = '1';

                                let value = parseInt(sampleSize, 10);

                                if (value < 1) value = 1;
                                if (value > 1000) value = 1000;

                                sampleSize = String(value);
                            "
                            @blur="
                                let value = parseInt(sampleSize || 1, 10);

                                if (value < 1) value = 1;
                                if (value > 1000) value = 1000;

                                sampleSize = String(value);
                            "
                            @keydown="
                                if (['-', '+', '.', ',', 'e', 'E'].includes($event.key)) {
                                    $event.preventDefault();
                                }
                            "
                            class="h-[44px] w-[72px] rounded-[3px] border border-[#BFBFBF] px-2 text-center text-[12px] text-[#242424] outline-none transition focus:border-[#4D7FCF]"
                        >
                    </div>
                </label>

                {{-- Timeline --}}
                <label class="block">
                    <span class="text-[13px] font-normal text-[#242424]">
                        {{ $studyDetailsContent['timeline_label'] }}
                    </span>

                    <select
                        x-model="timeline"
                        class="mt-[10px] h-[44px] w-full rounded-[3px] border border-[#BFBFBF] bg-white px-[14px] text-[12px] text-[#6F6F6F] outline-none transition focus:border-[#4D7FCF]"
                    >
                        <option value="">{{ $studyDetailsContent['timeline_placeholder'] }}</option>
                        <option value="1-2 weeks">1-2 weeks</option>
                        <option value="2-4 weeks">2-4 weeks</option>
                        <option value="1-2 months">1-2 months</option>
                    </select>
                </label>

            </div>

            {{-- Notes --}}
            <label class="mt-[24px] block">
                <span class="text-[12px] font-normal text-[#242424]">
                    {{ $studyDetailsContent['notes_label'] }}
                </span>

                <textarea
                    x-model="notes"
                    maxlength="5000"
                    placeholder="{{ $studyDetailsContent['notes_placeholder'] }}"
                    class="mt-[10px] h-[86px] w-full resize-none rounded-[3px] border border-[#BFBFBF] px-[14px] py-[12px] text-[12px] text-[#242424] outline-none transition placeholder:text-[#8D8D8D] focus:border-[#4D7FCF]"
                ></textarea>
            </label>

            {{-- Next --}}
            <div class="mt-[60px] text-center">
                <button
                    type="button"
                    @click="
                        if (!country) {
                            alert('Please select your target country.');
                            return;
                        }

                        if (!audience.trim()) {
                            alert('Please enter your target audience.');
                            return;
                        }

                        scrollTo('submit-request');
                    "
                    class="h-[42px] w-[240px] rounded-[3px] bg-[#4D7FCF] text-[13px] font-medium text-white transition hover:bg-[#416FB4]"
                >
                    {{ $studyDetailsContent['next_button_text'] }}
                </button>
            </div>

        </div>
    </div>
</section>
