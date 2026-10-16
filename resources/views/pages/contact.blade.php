
@extends('layouts.app')

@section('content')
<section
    class="relative isolate overflow-hidden bg-white"
    x-data="{
        requestType: @js(old('request_type', 'demo')),
        phone: @js(old('phone', '')),

        cleanPhone() {
            let value = String(this.phone || '');

            const hasPlus = value.startsWith('+');

            const digits = value
                .replace(/[^0-9]/g, '')
                .slice(0, 15);

            this.phone = (hasPlus ? '+' : '') + digits;
        }
    }"
>
    {{-- Background decoration --}}
    <div class="pointer-events-none absolute -right-[170px] top-[150px] -z-10 h-[550px] w-[550px] rounded-full bg-[#33B6FF]/5"></div>
    <div class="pointer-events-none absolute -right-[100px] top-[225px] -z-10 h-[400px] w-[400px] rounded-full bg-[#33B6FF]/10"></div>
    <div class="pointer-events-none absolute -right-[35px] top-[290px] -z-10 h-[270px] w-[270px] rounded-full bg-[#33B6FF]/10"></div>

    <div class="mx-auto grid min-h-[690px] max-w-[1220px] grid-cols-1 items-center gap-14 px-6 py-16 md:px-8 lg:grid-cols-[0.9fr_1.1fr] lg:gap-20 lg:px-0 lg:py-20">

        {{-- Left Content --}}
        <div>
            <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-[#D9EDFF] bg-[#F3FAFF] px-4 py-2">
                <span class="h-2 w-2 shrink-0 rounded-full bg-[#33B6FF]"></span>
                <span class="text-[12px] font-semibold tracking-[0.04em] text-[#278DC7]">
                    LET'S CONNECT
                </span>
            </div>

            <h1 class="max-w-[540px] text-[40px] font-extrabold leading-[1.13] tracking-[-1.5px] text-[#2F2F32] sm:text-[48px] lg:text-[54px]">
                Ready to turn<br>
                insights into<br>
                <span class="text-[#33B6FF]">real change?</span>
            </h1>

            <p class="mt-7 max-w-[510px] text-[17px] leading-[1.8] text-[#666670]">
                Whether you're exploring our research solutions or
                ready to see Impala in action, our team is here to help.
                Tell us a little about your needs, and we'll take it
                from there.
            </p>

            <div class="mt-10 space-y-5">
                <div class="flex items-start gap-4">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#EEF8FF] text-[#33B6FF]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8m-8 4h5m-9 6 4-4h11a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v11a2 2 0 002 2h1z"/>
                        </svg>
                    </div>

                    <div>
                        <h3 class="text-[15px] font-bold text-[#303036]">
                            Talk to our team
                        </h3>
                        <p class="mt-1 text-[13px] leading-6 text-[#777780]">
                            Ask questions and explore how Impala can support your goals.
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#EEF8FF] text-[#33B6FF]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <rect x="3" y="5" width="18" height="14" rx="2"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 9h10M7 13h6"/>
                        </svg>
                    </div>

                    <div>
                        <h3 class="text-[15px] font-bold text-[#303036]">
                            Discover Impala
                        </h3>
                        <p class="mt-1 text-[13px] leading-6 text-[#777780]">
                            Request a personalized introduction to our platform.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Form --}}
        <div class="relative z-10">
            <div class="rounded-[20px] border border-[#E8EDF2] bg-white p-6 shadow-[0_18px_65px_rgba(30,70,100,0.08)] sm:p-9">

                <div class="mb-7">
                    <h2 class="text-[23px] font-extrabold tracking-[-0.5px] text-[#2F2F32]">
                        Get in touch
                    </h2>

                    <p class="mt-2 text-[13px] leading-6 text-[#85858C]">
                        Fill out the form below and our team will get back to you.
                    </p>

                    <p class="mt-2 text-[11px] text-[#85858C]">
                        Fields marked with
                        <span class="font-bold text-red-500">*</span>
                        are required.
                    </p>
                </div>

                {{-- Success Message --}}
                @if(session('success'))
                    <div
                        role="status"
                        class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-4 text-[13px] font-medium leading-6 text-green-800"
                    >
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Validation Summary --}}
                @if($errors->any())
                    <div
                        role="alert"
                        class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-4 text-[13px] text-red-700"
                    >
                        <p class="font-bold">
                            Please check the following:
                        </p>

                        <ul class="mt-2 list-inside list-disc space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('contact.store') }}"
                    class="space-y-6"
                >
                    @csrf

                    {{-- Request Type --}}
                    <div>
                        <label class="mb-3 block text-[12px] font-semibold text-[#4C4C54]">
                            What can we help you with?
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="hidden"
                            name="request_type"
                            :value="requestType"
                        >

                        <div class="grid grid-cols-2 gap-3">
                            <button
                                type="button"
                                @click="requestType = 'demo'"
                                :aria-pressed="requestType === 'demo'"
                                :class="requestType === 'demo'
                                    ? 'border-[#33B6FF] bg-[#EFF9FF] text-[#168ACB]'
                                    : 'border-[#E4E7EC] bg-white text-[#777780] hover:border-[#BBDFF5]'"
                                class="rounded-[10px] border px-3 py-3 text-[12px] font-bold transition sm:text-[13px]"
                            >
                                Request a Demo
                            </button>

                            <button
                                type="button"
                                @click="requestType = 'general'"
                                :aria-pressed="requestType === 'general'"
                                :class="requestType === 'general'
                                    ? 'border-[#33B6FF] bg-[#EFF9FF] text-[#168ACB]'
                                    : 'border-[#E4E7EC] bg-white text-[#777780] hover:border-[#BBDFF5]'"
                                class="rounded-[10px] border px-3 py-3 text-[12px] font-bold transition sm:text-[13px]"
                            >
                                General Inquiry
                            </button>
                        </div>
                    </div>

                    {{-- Form Fields --}}
                    <div class="grid gap-5 sm:grid-cols-2">

                        {{-- Full Name --}}
                        <div>
                            <label
                                for="contact-name"
                                class="mb-2 block text-[12px] font-semibold text-[#4C4C54]"
                            >
                                Full Name
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="contact-name"
                                name="name"
                                type="text"
                                value="{{ old('name') }}"
                                maxlength="255"
                                autocomplete="name"
                                required
                                placeholder="Your full name"
                                class="h-[46px] w-full rounded-[9px] border border-[#E0E4E9] bg-white px-4 text-[13px] text-[#303036] outline-none transition placeholder:text-[#ACACB4] focus:border-[#33B6FF] focus:ring-2 focus:ring-[#33B6FF]/10"
                            >
                        </div>

                        {{-- Email --}}
                        <div>
                            <label
                                for="contact-email"
                                class="mb-2 block text-[12px] font-semibold text-[#4C4C54]"
                            >
                                Email Address
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="contact-email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                maxlength="255"
                                autocomplete="email"
                                required
                                pattern="[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}"
                                title="Enter a valid email address, for example name@example.com."
                                placeholder="e-mail@example.com"
                                class="h-[46px] w-full rounded-[9px] border border-[#E0E4E9] bg-white px-4 text-[13px] text-[#303036] outline-none transition placeholder:text-[#ACACB4] focus:border-[#33B6FF] focus:ring-2 focus:ring-[#33B6FF]/10"
                            >
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label
                                for="contact-phone"
                                class="mb-2 block text-[12px] font-semibold text-[#4C4C54]"
                            >
                                Phone Number
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="contact-phone"
                                name="phone"
                                type="tel"
                                x-model="phone"
                                @input="cleanPhone()"
                                inputmode="tel"
                                pattern="\+?[0-9]{7,15}"
                                minlength="7"
                                maxlength="16"
                                autocomplete="tel"
                                required
                                title="Enter 7 to 15 digits, optionally starting with +."
                                placeholder="e.g. +38970123456"
                                class="h-[46px] w-full rounded-[9px] border border-[#E0E4E9] bg-white px-4 text-[13px] text-[#303036] outline-none transition placeholder:text-[#ACACB4] focus:border-[#33B6FF] focus:ring-2 focus:ring-[#33B6FF]/10"
                            >

                            <p class="mt-1.5 text-[11px] text-[#9999A1]">
                            </p>
                        </div>

                        {{-- Country Dropdown --}}
                        <div>
                            <label
                                for="contact-country"
                                class="mb-2 block text-[12px] font-semibold text-[#4C4C54]"
                            >
                                Country
                                <span class="text-red-500">*</span>
                            </label>

                            <select
                                id="contact-country"
                                name="country"
                                required
                                class="h-[46px] w-full rounded-[9px] border border-[#E0E4E9] bg-white px-4 text-[13px] text-[#303036] outline-none transition focus:border-[#33B6FF] focus:ring-2 focus:ring-[#33B6FF]/10"
                            >
                                <option value="" disabled @selected(!old('country'))>
                                    Select your country
                                </option>

                                @foreach(config('contact_countries', []) as $country)
                                    <option
                                        value="{{ $country }}"
                                        @selected(old('country') === $country)
                                    >
                                        {{ $country }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Company - Optional --}}
                        <div class="sm:col-span-2">
                            <label
                                for="contact-company"
                                class="mb-2 block text-[12px] font-semibold text-[#4C4C54]"
                            >
                                Company / Organization
                                <span class="ml-1 text-[11px] font-normal text-[#9999A1]">
                                    (Optional)
                                </span>
                            </label>

                            <input
                                id="contact-company"
                                name="company"
                                type="text"
                                value="{{ old('company') }}"
                                maxlength="255"
                                autocomplete="organization"
                                placeholder="Company name (optional)"
                                class="h-[46px] w-full rounded-[9px] border border-[#E0E4E9] bg-white px-4 text-[13px] text-[#303036] outline-none transition placeholder:text-[#ACACB4] focus:border-[#33B6FF] focus:ring-2 focus:ring-[#33B6FF]/10"
                            >
                        </div>

                        {{-- Message --}}
                        <div class="sm:col-span-2">
                            <label
                                for="contact-message"
                                class="mb-2 block text-[12px] font-semibold text-[#4C4C54]"
                            >
                                Tell us more about your needs
                                <span class="text-red-500">*</span>
                            </label>

                            <textarea
                                id="contact-message"
                                name="message"
                                rows="4"
                                maxlength="5000"
                                required
                                placeholder="Share a little about what you're looking for..."
                                class="w-full resize-y rounded-[9px] border border-[#E0E4E9] bg-white px-4 py-3 text-[13px] leading-6 text-[#303036] outline-none transition placeholder:text-[#ACACB4] focus:border-[#33B6FF] focus:ring-2 focus:ring-[#33B6FF]/10"
                            >{{ old('message') }}</textarea>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <button
                        type="submit"
                        class="flex h-[49px] w-full items-center justify-center gap-2 rounded-[9px] bg-[#33B6FF] text-[13px] font-bold tracking-[0.02em] text-white transition hover:bg-[#159FE4] focus:outline-none focus:ring-4 focus:ring-[#33B6FF]/20"
                    >
                        <span x-text="requestType === 'demo' ? 'REQUEST A DEMO' : 'SEND MESSAGE'">
                            REQUEST A DEMO
                        </span>

                        <svg
                            class="h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5 12h14m-6-6 6 6-6 6"
                            />
                        </svg>
                    </button>

                    <p class="text-center text-[11px] leading-5 text-[#9999A1]">
                        By submitting this form, you agree to be contacted by our team regarding your inquiry.
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>

@include('partials.footer')
@endsection
