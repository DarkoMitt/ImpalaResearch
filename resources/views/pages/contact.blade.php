@extends('layouts.app')

@section('content')
<section
    class="relative overflow-hidden bg-white"
    x-data="{
        scheduled: false,
        error: '',
        name: '',
        email: '',
        phone: '',
        country: '',
        company: '',

        scheduleDemo() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            this.error = '';
            this.scheduled = false;

            if (!this.name.trim()) {
                this.error = 'Please enter your name.';
                return;
            }

            if (!this.email.trim()) {
                this.error = 'Please enter your email.';
                return;
            }

            if (!emailRegex.test(this.email)) {
                this.error = 'Please enter a valid email address.';
                return;
            }

            if (!this.phone.trim()) {
                this.error = 'Please enter your phone number.';
                return;
            }

            if (!/^[0-9]+$/.test(this.phone)) {
                this.error = 'Phone number must contain numbers only.';
                return;
            }

            if (this.phone.length < 9) {
                this.error = 'Phone number must be at least 9 digits.';
                return;
            }

            if (!this.country) {
                this.error = 'Please select a country.';
                return;
            }

            if (!this.company.trim()) {
                this.error = 'Please enter your company name.';
                return;
            }

            this.scheduled = true;
        }
    }"
>
    <div class="mx-auto grid min-h-[620px] max-w-[1220px] grid-cols-1 items-center gap-14 px-8 py-10 lg:grid-cols-[0.9fr_1.1fr] lg:px-0">
        <div>
            <h1 class="max-w-[520px] text-[42px] font-extrabold leading-[1.12] tracking-[-1.2px] text-[#2F2F32] md:text-[52px]">
                Ready to turn<br>
                insights into real<br>
                change?
            </h1>

            <p class="mt-7 max-w-[560px] text-[20px] leading-[1.55] tracking-[-0.3px] text-[#2F2F32]">
                Whether you're looking to evaluate teams,
                develop leaders, or improve decision-making —
                Impala helps you act on real data, not
                assumptions. Request a demo or talk to our team
                and see how the Impala process can work for you.
            </p>
        </div>

        <div class="relative z-10">
            <form class="grid gap-x-7 gap-y-7 md:grid-cols-2">
                <input
                    type="text"
                    x-model="name"
                    placeholder="Your Name"
                    class="h-[44px] border-0 border-b border-[#707070] bg-transparent px-0 text-[20px] text-[#2F2F32] outline-none placeholder:text-[#A0A0A0] focus:border-[#33B6FF]"
                >

                <input
                    type="email"
                    x-model="email"
                    placeholder="Your Email"
                    class="h-[44px] border-0 border-b border-[#707070] bg-transparent px-0 text-[20px] text-[#2F2F32] outline-none placeholder:text-[#A0A0A0] focus:border-[#33B6FF]"
                >

                <input
                    type="text"
                    inputmode="numeric"
                    x-model="phone"
                    @input="phone = phone.replace(/[^0-9]/g, '')"
                    placeholder="Phone Number"
                    class="h-[44px] border-0 border-b border-[#707070] bg-transparent px-0 text-[20px] text-[#2F2F32] outline-none placeholder:text-[#A0A0A0] focus:border-[#33B6FF]"
                >

                <select
                    x-model="country"
                    :class="country ? 'text-[#2F2F32] : 'text-[#A0A0A0] font-normal'"
                    class="h-[44px] w-full border-0 border-b border-[#707070] bg-transparent px-0 text-[20px] outline-none focus:border-[#33B6FF]"
                >
                    <option value="" disabled selected>Country</option>
                    <option value="North Macedonia">North Macedonia</option>
                    <option value="United States">United States</option>
                    <option value="Germany">Germany</option>
                    <option value="United Kingdom">United Kingdom</option>
                </select>

                <input
                    type="text"
                    x-model="company"
                    placeholder="Company Name"
                    class="h-[44px] border-0 border-b border-[#707070] bg-transparent px-0 text-[20px] text-[#2F2F32] outline-none placeholder:text-[#A0A0A0] focus:border-[#33B6FF]"
                >

                <div></div>

                <input
                    type="text"
                    x-model="message"
                    placeholder="Message"
                    class="h-[44px] border-0 border-b border-[#707070] bg-transparent px-0 text-[20px] text-[#2F2F32] outline-none placeholder:text-[#A0A0A0] focus:border-[#33B6FF] md:col-span-2"
                >

                <div class="md:col-span-2">
                    <button
                        type="button"
                        @click="scheduleDemo()"
                        class="mt-1 h-[50px] w-[260px] rounded-[3px] bg-[#33B6FF] text-[16px] font-bold text-white transition hover:bg-[#159fe4]"
                    >
                        SCHEDULE A DEMO
                    </button>

                    <p
                        x-show="error"
                        x-text="error"
                        x-transition
                        class="mt-4 text-[14px] font-medium text-red-600"
                    ></p>

                    <p
                        x-show="scheduled"
                        x-transition
                        class="mt-4 text-[14px] font-medium text-green-600"
                    >
                        Demo scheduled.
                    </p>
                </div>
            </form>
        </div>
    </div>

    <div class="pointer-events-none absolute right-[-130px] top-[180px] h-[430px] w-[430px] rounded-full bg-[#33B6FF]/10"></div>
    <div class="pointer-events-none absolute right-[-90px] top-[225px] h-[340px] w-[340px] rounded-full bg-[#33B6FF]/15"></div>
    <div class="pointer-events-none absolute right-[-50px] top-[270px] h-[250px] w-[250px] rounded-full bg-[#33B6FF]/20"></div>
</section>

@include('partials.footer')
@endsection