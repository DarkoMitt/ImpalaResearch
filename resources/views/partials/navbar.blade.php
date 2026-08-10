<header class="w-full bg-white">
    <nav
        class="mx-auto flex h-[78px] w-full max-w-[1173px] items-center justify-between px-5 md:px-8 lg:px-0"
    >
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center">
            <img
                src="{{ asset('images/logo.svg') }}"
                alt="Impala Logo"
                class="h-[38px] w-auto"
            >
        </a>

        {{-- Desktop navigation --}}
        <div class="hidden items-center gap-[34px] text-[14px] font-normal text-[#111111] md:flex">
            <a
                href="{{ route('home') }}"
                class="transition-colors hover:text-[#4D7FCF]"
            >
                Home
            </a>

            <a
                href="#infinity"
                class="transition-colors hover:text-[#4D7FCF]"
            >
                Impala Infinity
            </a>

            <a
                href="#science"
                class="transition-colors hover:text-[#4D7FCF]"
            >
                Science
            </a>

            <a
                href="{{ route('contact') }}"
                class="transition-colors hover:text-[#4D7FCF]"
            >
                Contact
            </a>
        </div>

        {{-- Actions --}}
        <div class="hidden items-center gap-4 md:flex">
            <a
                href="{{ route('login') }}"
                class="inline-flex h-[34px] min-w-[76px] items-center justify-center rounded-full border border-[#BDBDBD] px-5 text-[13px] font-normal text-[#111111] transition hover:border-[#4D7FCF] hover:text-[#4D7FCF]"
            >
                Log in
            </a>

            <a
                href="#start-study"
                class="inline-flex h-[34px] min-w-[103px] items-center justify-center gap-2 rounded-full border border-[#BDBDBD] px-5 text-[13px] font-normal text-[#111111] transition hover:border-[#4D7FCF] hover:text-[#4D7FCF]"
            >
                Get started

                <span class="text-[15px] leading-none">
                    ›
                </span>
            </a>
        </div>

        {{-- Mobile --}}
        <div
            x-data="{ open: false }"
            class="relative md:hidden"
        >
            <button
                type="button"
                @click="open = !open"
                class="flex h-10 w-10 items-center justify-center"
                aria-label="Open menu"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.5"
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                </svg>
            </button>

            <div
                x-show="open"
                x-cloak
                @click.outside="open = false"
                class="absolute right-0 top-12 z-50 w-[220px] rounded-xl border border-[#E7E7E7] bg-white p-5 shadow-lg"
            >
                <div class="flex flex-col gap-4 text-[14px] text-[#111111]">
                    <a href="{{ route('home') }}">Home</a>
                    <a href="#infinity">Impala Infinity</a>
                    <a href="#science">Science</a>
                    <a href="{{ route('contact') }}">Contact</a>

                    <div class="mt-2 border-t border-[#EEEEEE] pt-4">
                        <a
                            href="{{ route('login') }}"
                            class="mb-3 flex h-10 items-center justify-center rounded-full border border-[#BDBDBD]"
                        >
                            Log in
                        </a>

                        <a
                            href="#start-study"
                            class="flex h-10 items-center justify-center gap-2 rounded-full border border-[#BDBDBD]"
                        >
                            Get started
                            <span>›</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>