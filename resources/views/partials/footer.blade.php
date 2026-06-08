<footer
    class="relative overflow-hidden bg-[#FAFAFA] border-t border-[#E5E5E5] px-8 py-20 lg:px-24"
    x-data="{
        email: '',
        error: '',
        subscribe() {
            const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!this.email.trim()) {
                this.error = 'Please enter your email first.';
                return;
            }

            if (!pattern.test(this.email)) {
                this.error = 'Please enter a valid email address.';
                return;
            }

            window.location.href = '{{ route('register') }}?email=' + encodeURIComponent(this.email);
        }
    }"
>
    <div class="mx-auto grid max-w-[1400px] gap-12 lg:grid-cols-[180px_1fr_180px]">

        {{-- Logo --}}
        <div>
            <img
                src="{{ asset('images/logo.svg') }}"
                alt="Impala"
                class="h-[55px] w-auto"
            >
        </div>

        {{-- Middle --}}
        <div>
            <nav class="flex gap-14 text-[18px] font-medium text-[#2F2F32]">
                <a href="{{ route('home') }}">Home</a>
                <a href="#infinity">Impala Infinity</a>
                <a href="#science">Science</a>
                <a href="{{ route('contact') }}">Contact</a>
            </nav>

            <p class="mt-8 max-w-[585px] text-[14px] leading-[1.5] text-[#2F2F32]">
                The goal of the funded project is the research and development of new innovative methods for the evaluation of gaze recognition and the associated expansion of the platform. This will enable the company to develop additional market segments with an expanded range of services. Copyright impala 2025
            </p>

            <div class="relative mt-8 flex max-w-[585px] gap-3">
                <input
                    type="email"
                    x-model="email"
                    @keydown.enter="subscribe()"
                    placeholder="Write your email"
                    class="h-[45px] w-[376px] border border-[#CFCFCF] px-4 text-[14px] text-[#2F2F32] outline-none focus:border-[#33B6FF]"
                >

                <button
                    type="button"
                    @click="subscribe()"
                    class="h-[45px] w-[140px] bg-[#33B6FF] text-[14px] text-white transition hover:bg-[#159fe4]"
                >
                    Subscribe
                </button>

                <div
                    x-show="error"
                    x-text="error"
                    x-transition
                    class="absolute -top-10 left-0 rounded border border-red-300 bg-red-50 px-3 py-2 text-[13px] text-red-600"
                ></div>
            </div>
        </div>

        {{-- Right side --}}
        <div class="flex items-center border-l border-[#D9D9D9] pl-6">
            <p class="text-[20px] font-bold leading-[1.1]">
                <span class="text-[#9B22FF]">
                    Place to<br>build
                </span>
                <br>
                <span class="text-[#2F2F32]">
                    better<br>brands
                </span>
            </p>
        </div>

    </div>
</footer>