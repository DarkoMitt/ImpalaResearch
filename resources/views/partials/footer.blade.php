<footer class="bg-[#F7F8FA] px-5 pb-[36px] pt-[54px] md:px-8">
    <div class="mx-auto w-full max-w-[1050px]">

        {{-- CTA --}}
        <div class="flex flex-col items-center text-center">
            <h2
                class="text-[28px] font-semibold leading-tight tracking-[-0.7px] text-[#111111] md:text-[30px]"
            >
                Ready to start your research?
            </h2>

            <a
                href="#start-study"
                class="mt-[28px] inline-flex h-[48px] w-[257px] items-center justify-center gap-2 rounded-full border border-[#BDBDBD] bg-transparent text-[14px] font-normal text-[#111111] transition hover:border-[#4D7FCF] hover:text-[#4D7FCF]"
            >
                Get started

                <span class="text-[16px] leading-none">
                    ›
                </span>
            </a>
        </div>

        {{-- Divider --}}
        <div class="mt-[28px] h-px w-full bg-[#4DA3FF]"></div>

        {{-- Footer navigation --}}
        <div
            class="grid gap-10 pt-[35px] sm:grid-cols-2 lg:grid-cols-[2fr_1fr_1fr_1fr]"
        >
            {{-- Company description --}}
            <div class="max-w-[260px]">
                <h3 class="text-[13px] font-medium text-[#111111]">
                    Impala Research
                </h3>

                <p class="mt-[18px] text-[12px] leading-[1.35] text-[#2F2F32]">
                    Neuromarketing studies, delivered as a
                    subscription — six methodologies, one
                    dashboard.
                </p>
            </div>

            {{-- Product --}}
            <div>
                <h3
                    class="text-[12px] font-medium uppercase tracking-[0.02em] text-[#2F2F32]"
                >
                    Product
                </h3>

                <div class="mt-[22px] flex flex-col gap-[18px] text-[12px] text-[#2F2F32]">
                    <a
                        href="#method"
                        class="transition hover:text-[#4D7FCF]"
                    >
                        Methodologies
                    </a>

                    <a
                        href="#pricing"
                        class="transition hover:text-[#4D7FCF]"
                    >
                        Pricing
                    </a>

                    <a
                        href="#case-studies"
                        class="transition hover:text-[#4D7FCF]"
                    >
                        Case Studies
                    </a>

                    <a
                        href="#sample-report"
                        class="transition hover:text-[#4D7FCF]"
                    >
                        Sample report
                    </a>
                </div>
            </div>

            {{-- Company --}}
            <div>
                <h3
                    class="text-[12px] font-medium uppercase tracking-[0.02em] text-[#2F2F32]"
                >
                    Company
                </h3>

                <div class="mt-[22px] flex flex-col gap-[18px] text-[12px] text-[#2F2F32]">
                    <a
                        href="#about"
                        class="transition hover:text-[#4D7FCF]"
                    >
                        About
                    </a>

                    <a
                        href="#careers"
                        class="transition hover:text-[#4D7FCF]"
                    >
                        Careers
                    </a>

                    <a
                        href="{{ route('contact') }}"
                        class="transition hover:text-[#4D7FCF]"
                    >
                        Contact us
                    </a>
                </div>
            </div>

            {{-- Legal --}}
            <div>
                <h3
                    class="text-[12px] font-medium uppercase tracking-[0.02em] text-[#2F2F32]"
                >
                    Legal
                </h3>

                <div class="mt-[22px] flex flex-col gap-[18px] text-[12px] text-[#2F2F32]">
                    <a
                        href="#privacy-policy"
                        class="transition hover:text-[#4D7FCF]"
                    >
                        Privacy policy
                    </a>

                    <a
                        href="#terms"
                        class="transition hover:text-[#4D7FCF]"
                    >
                        Terms of service
                    </a>

                    <a
                        href="#data-protection"
                        class="transition hover:text-[#4D7FCF]"
                    >
                        Data protection
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>