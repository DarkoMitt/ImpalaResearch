<section id="submit-request" class="bg-white py-20">
    <div class="mx-auto max-w-[980px] px-6 text-center">
        <img src="{{ asset('images/logo.svg') }}" alt="Impala" class="mx-auto h-[55px] w-auto">

        <x-stepper :active="4" />

        <h2 class="mt-8 text-[30px] font-bold text-[#2F2F32]">
            Almost there! Let’s get your details
        </h2>

        <p class="mx-auto mt-3 max-w-[460px] text-[14px] leading-[1.5] text-[#6F6F6F]">
            We’ll use this information to prepare a tailored proposal.
        </p>

        <form class="mx-auto mt-10 grid max-w-[862px] gap-10 text-left lg:grid-cols-[1fr_230px]">
            <div>
                <div class="grid gap-6 md:grid-cols-2">
                    <label class="block">
                        <span class="text-[14px] font-medium text-[#2F2F32]">Full Name</span>
                        <input type="text" placeholder="Enter your name" class="mt-3 h-[45px] w-full rounded-[3px] border border-[#CFCFCF] px-4 text-[13px] outline-none placeholder:text-[#8A8A8A] focus:border-[#33B6FF]">
                    </label>

                    <label class="block">
                        <span class="text-[14px] font-medium text-[#2F2F32]">Email</span>
                        <input type="email" placeholder="Enter your email" class="mt-3 h-[45px] w-full rounded-[3px] border border-[#CFCFCF] px-4 text-[13px] outline-none placeholder:text-[#8A8A8A] focus:border-[#33B6FF]">
                    </label>

                    <label class="block">
                        <span class="text-[14px] font-medium text-[#2F2F32]">Phone Number</span>
                        <input type="text" placeholder="Enter phone" class="mt-3 h-[45px] w-full rounded-[3px] border border-[#CFCFCF] px-4 text-[13px] outline-none placeholder:text-[#8A8A8A] focus:border-[#33B6FF]">
                    </label>

                    <label class="block">
                        <span class="text-[14px] font-medium text-[#2F2F32]">Company</span>
                        <input type="text" placeholder="Company name" class="mt-3 h-[45px] w-full rounded-[3px] border border-[#CFCFCF] px-4 text-[13px] outline-none placeholder:text-[#8A8A8A] focus:border-[#33B6FF]">
                    </label>
                </div>

                <label class="mt-6 block">
                    <span class="text-[14px] font-medium text-[#2F2F32]">
                        Anything else we should know? (optional)
                    </span>
                    <textarea rows="4" placeholder="Add any specific requirements or context about your study..." class="mt-3 w-full resize-none rounded-[3px] border border-[#CFCFCF] px-4 py-4 text-[13px] outline-none placeholder:text-[#8A8A8A] focus:border-[#33B6FF]"></textarea>
                </label>

                <div class="mt-8 text-center">
                    <button
                        type="button"
                        @click="window.location.href='{{ route('thank-you') }}'"
                        class="h-[46px] w-[260px] rounded-[3px] bg-[#33B6FF] text-[15px] font-medium text-white transition hover:bg-[#159fe4]"
                    >
                        Submit Request
                    </button>
                </div>
            </div>

            <aside class="h-fit rounded-[3px] border border-[#CFCFCF] bg-white p-5">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-[14px] font-medium text-[#2F2F32]">Study Summary</h3>
                    <button type="button" @click="scrollTo('start-study')" class="text-[12px] font-medium text-[#33B6FF] hover:underline">
                        Edit
                    </button>
                </div>

                <div class="space-y-4 text-[12px]">
                    <div>
                        <p class="text-[#8A8A8A]">Goal</p>
                        <p class="mt-1 font-medium text-[#2F2F32]" x-text="goal || 'Not selected'"></p>
                    </div>

                    <div>
                        <p class="text-[#8A8A8A]">Method</p>
                        <p class="mt-1 font-medium text-[#2F2F32]" x-text="method || 'Not selected'"></p>
                    </div>

                    <div>
                        <p class="text-[#8A8A8A]">Target</p>
                        <p class="mt-1 font-medium text-[#2F2F32]" x-text="country || 'Not selected'"></p>
                    </div>

                    <div>
                        <p class="text-[#8A8A8A]">Audience</p>
                        <p class="mt-1 font-medium text-[#2F2F32]" x-text="audience || 'Not provided'"></p>
                    </div>

                    <div>
                        <p class="text-[#8A8A8A]">Sample Size</p>
                        <p class="mt-1 font-medium text-[#2F2F32]" x-text="sampleSize"></p>
                    </div>

                    <div>
                        <p class="text-[#8A8A8A]">Timeline</p>
                        <p class="mt-1 font-medium text-[#2F2F32]" x-text="timeline || 'Not selected'"></p>
                    </div>
                </div>
            </aside>
        </form>
    </div>
</section>