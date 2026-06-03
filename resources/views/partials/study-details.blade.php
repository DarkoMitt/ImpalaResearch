<section id="study-details" class="bg-white py-20">
    <div class="mx-auto max-w-[980px] px-6 text-center">
        <img src="{{ asset('images/logo.svg') }}" alt="Impala" class="mx-auto h-[55px] w-auto">

        <x-stepper :active="3" />

        <h2 class="mt-8 text-[30px] font-bold text-[#2F2F32]">
            Tell us about your study
        </h2>

        <p class="mx-auto mt-3 max-w-[420px] text-[14px] leading-[1.5] text-[#6F6F6F]">
            These details help us tailor the study for you.
        </p>

        <form class="mx-auto mt-8 max-w-[862px] text-left">
            <div class="grid gap-6 md:grid-cols-2">
                <label class="block">
                    <span class="text-[14px] font-medium text-[#2F2F32]">Target Market / Country</span>
                    <select x-model="country" class="mt-3 h-[48px] w-full rounded-[3px] border border-[#CFCFCF] bg-white px-4 text-[13px] text-[#8A8A8A] outline-none focus:border-[#33B6FF]">
                        <option value="">Select Country</option>
                        <option>North Macedonia</option>
                        <option>United States</option>
                        <option>Germany</option>
                        <option>United Kingdom</option>
                    </select>
                </label>

                <label class="block">
                    <span class="text-[14px] font-medium text-[#2F2F32]">Target Audience</span>
                    <input
                        type="text"
                        placeholder="e.g US Adults 18–65"
                        x-model="audience"
                        class="mt-3 h-[48px] w-full rounded-[3px] border border-[#CFCFCF] px-4 text-[13px] outline-none placeholder:text-[#8A8A8A] focus:border-[#33B6FF]"
                    >
                </label>

                <label class="block">
                    <span class="text-[14px] font-medium text-[#2F2F32]">
                        Sample Size
                    </span>

                    <div
                        class="mt-3 flex h-[48px] items-center gap-4 rounded-[3px] border border-[#CFCFCF] px-4"
                    >
                        <input
                            type="range"
                            min="1"
                            max="1000"
                            step="1"
                            x-model="sampleSize"
                            class="flex-1 accent-[#33B6FF]"
                        >

                        <input
                            type="text"
                            inputmode="numeric"
                            maxlength="4"
                            x-model="sampleSize"
                            @input="
                                sampleSize = String(sampleSize).replace(/[^0-9]/g, '');

                                if (sampleSize === '') {
                                    sampleSize = '1';
                                }

                                let value = parseInt(sampleSize, 10);

                                if (value < 1) {
                                    value = 1;
                                }

                                if (value > 1000) {
                                    value = 1000;
                                }

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
                            class="w-[90px] rounded border border-[#CFCFCF] px-2 py-1 text-center text-[13px] outline-none focus:border-[#33B6FF]"
                        >
                    </div>
                </label>

                <label class="block">
                    <span class="text-[14px] font-medium text-[#2F2F32]">Timeline(optional)</span>
                    <select x-model="timeline" class="mt-3 h-[48px] w-full rounded-[3px] border border-[#CFCFCF] bg-white px-4 text-[13px] text-[#8A8A8A] outline-none focus:border-[#33B6FF]">
                        <option value="">Select timeline</option>
                        <option>1-2 weeks</option>
                        <option>2-4 weeks</option>
                        <option>1-2 months</option>
                    </select>
                </label>
            </div>

            <label class="mt-6 block">
                <span class="text-[14px] font-medium text-[#2F2F32]">
                    Anything else we should know? (optional)
                </span>
                <textarea
                    rows="4"
                    x-model="notes"
                    placeholder="Add any specific requirements or context about your study..."
                    class="mt-3 w-full resize-none rounded-[3px] border border-[#CFCFCF] px-4 py-4 text-[13px] outline-none placeholder:text-[#8A8A8A] focus:border-[#33B6FF]"
                ></textarea>
            </label>

            <div class="mt-9 text-center">
                <button
                    @click="scrollTo('submit-request')"
                    type="button"
                    class="h-[46px] w-[260px] rounded-[3px] bg-[#33B6FF] text-[15px] font-medium text-white transition hover:bg-[#159fe4]"
                >
                    Next
                </button>
            </div>
        </form>
    </div>
</section>