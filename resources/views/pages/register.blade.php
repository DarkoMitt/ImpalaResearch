
@extends('layouts.app')

@section('content')
<section class="min-h-screen w-full bg-white">
    <div class="grid min-h-screen w-full lg:grid-cols-2">

        {{-- LEFT SIDE --}}
        <div class="relative flex min-h-screen flex-col bg-gradient-to-br from-[#487DCF] via-[#315FB2] to-[#142B78] px-10 py-9 text-white md:px-14 lg:px-[72px]">

            <a
                href="{{ route('home') }}"
                class="inline-flex w-fit items-center gap-1 rounded-full border border-white/70 px-[14px] py-[6px] text-[12px] transition hover:bg-white/10"
            >
                ‹ Get back
            </a>

            <div class="my-auto">
                <h1 class="text-[42px] font-semibold leading-[1.48] tracking-[-1px] md:text-[46px]">
                    Impala tests.<br>
                    We develop.<br>
                    You grow.
                </h1>

                <p class="mt-[26px] max-w-[430px] text-[15px] leading-[1.55] text-white/95">
                    A process that combines testing, development, and result validation.
                </p>
            </div>
        </div>

        {{-- RIGHT SIDE --}}
        <div class="flex min-h-screen flex-col items-center bg-white px-8 py-10 md:px-12">

            <img
                src="{{ asset('images/logo.svg') }}"
                alt="Impala"
                class="h-[40px] w-auto"
            >

            <div class="my-auto w-full max-w-[370px] py-10">

                <h2 class="text-center text-[20px] font-medium text-[#111111]">
                    Create your account
                </h2>

                <form
                    method="POST"
                    action="{{ route('register') }}"
                    class="mt-[35px]"
                >
                    @csrf

                    {{-- FULL NAME --}}
                    <label class="block">
                        <span class="text-[12px] text-[#222222]">
                            Full name
                        </span>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            autocomplete="name"
                            maxlength="255"
                            placeholder="Enter your full name"
                            class="mt-[9px] h-[44px] w-full rounded-[3px] border border-[#BFBFBF] px-[13px] text-[12px] outline-none placeholder:text-[#8D8D8D] focus:border-[#4D7FCF]"
                        >
                    </label>

                    @error('name')
                        <p class="mt-[6px] text-[11px] text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                    {{-- EMAIL --}}
                    <label class="mt-[20px] block">
                        <span class="text-[12px] text-[#222222]">
                            Email
                        </span>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', request('email')) }}"
                            required
                            autocomplete="email"
                            maxlength="255"
                            placeholder="Enter your email"
                            class="mt-[9px] h-[44px] w-full rounded-[3px] border border-[#BFBFBF] px-[13px] text-[12px] outline-none placeholder:text-[#8D8D8D] focus:border-[#4D7FCF]"
                        >
                    </label>

                    @error('email')
                        <p class="mt-[6px] text-[11px] text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                    {{-- PASSWORD --}}
                    <label class="mt-[20px] block">
                        <span class="text-[12px] text-[#222222]">
                            Password
                        </span>

                        <input
                            type="password"
                            name="password"
                            required
                            minlength="8"
                            autocomplete="new-password"
                            placeholder="Enter Password"
                            class="mt-[9px] h-[44px] w-full rounded-[3px] border border-[#BFBFBF] px-[13px] text-[12px] outline-none placeholder:text-[#8D8D8D] focus:border-[#4D7FCF]"
                        >

                        <p class="mt-[6px] text-[10px] leading-[1.4] text-[#777777]">
                            Use 8 or more characters with uppercase, lowercase,
                            number &amp; symbol.
                        </p>
                    </label>

                    @error('password')
                        <p class="mt-[6px] text-[11px] text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                    {{-- CONFIRM PASSWORD --}}
                    <label class="mt-[20px] block">
                        <span class="text-[12px] text-[#222222]">
                            Repeat Password
                        </span>

                        <input
                            type="password"
                            name="password_confirmation"
                            required
                            minlength="8"
                            autocomplete="new-password"
                            placeholder="Repeat Password"
                            class="mt-[9px] h-[44px] w-full rounded-[3px] border border-[#BFBFBF] px-[13px] text-[12px] outline-none placeholder:text-[#8D8D8D] focus:border-[#4D7FCF]"
                        >
                    </label>

                    @if ($errors->any())
                        <p class="mt-[14px] text-center text-[11px] leading-[1.45] text-red-600">
                            Please check the highlighted fields and try again.
                        </p>
                    @endif

                    {{-- SUBMIT --}}
                    <button
                        type="submit"
                        class="mt-[30px] h-[44px] w-full rounded-[3px] bg-[#4D7FCF] text-[12px] font-medium text-white transition hover:bg-[#416FB4]"
                    >
                        Sign up
                    </button>

                    <p class="mt-[16px] text-center text-[11px] text-[#333333]">
                        Already have an account?

                        <a
                            href="{{ route('login') }}"
                            class="text-[#4D7FCF] hover:underline"
                        >
                            Log in
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
