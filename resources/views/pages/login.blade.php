@extends('layouts.app')

@section('content')
<section class="min-h-screen w-full bg-white">
    <div class="grid min-h-screen w-full lg:grid-cols-2">

        {{-- LEFT SIDE --}}
        <div
            class="relative flex min-h-screen flex-col bg-gradient-to-br from-[#487DCF] via-[#315FB2] to-[#142B78] px-10 py-9 text-white md:px-14 lg:px-[72px]"
        >
            {{-- Back --}}
            <a
                href="{{ route('home') }}"
                class="inline-flex w-fit items-center gap-1 rounded-full border border-white/70 px-[14px] py-[6px] text-[12px] transition hover:bg-white/10"
            >
                ‹ Get back
            </a>

            {{-- Left text --}}
            <div class="my-auto">
                <h1
                    class="text-[42px] font-semibold leading-[1.48] tracking-[-1px] md:text-[46px]"
                >
                    Impala tests.<br>
                    We develop.<br>
                    You grow.
                </h1>

                <p
                    class="mt-[26px] max-w-[430px] text-[15px] leading-[1.55] text-white/95"
                >
                    A process that combines testing, development, and result validation.
                </p>
            </div>
        </div>

        {{-- RIGHT SIDE --}}
        <div
            class="flex min-h-screen flex-col items-center bg-white px-8 py-10 md:px-12"
        >
            {{-- Logo --}}
            <img
                src="{{ asset('images/logo.svg') }}"
                alt="Impala"
                class="h-[40px] w-auto"
            >

            {{-- Form wrapper --}}
            <div class="my-auto w-full max-w-[370px]">

                <h2
                    class="text-center text-[20px] font-medium text-[#111111]"
                >
                    Welcome back!
                </h2>

                <form
                    class="mt-[54px]"
                    x-data="{
                        email: '',
                        password: '',
                        remember: false,
                        error: '',

                        login() {
                            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                            this.error = '';

                            if (!this.email.trim()) {
                                this.error = 'Email is required.';
                                return;
                            }

                            if (!emailRegex.test(this.email.trim())) {
                                this.error = 'Please enter a valid email address.';
                                return;
                            }

                            if (!this.password.trim()) {
                                this.error = 'Password is required.';
                                return;
                            }

                            if (this.password.length < 8) {
                                this.error = 'Password must be at least 8 characters.';
                                return;
                            }

                            if (
                                this.email.trim().toLowerCase() === 'admin@gmail.com' &&
                                this.password === 'Admin123.'
                            ) {
                                window.location.href = '{{ route('admin.dashboard') }}';
                                return;
                            }

                            window.location.href = '{{ route('pricing') }}';
                        }
                    }"
                    @submit.prevent="login()"
                >
                    {{-- Email --}}
                    <label class="block">
                        <span class="text-[12px] text-[#222222]">
                            Email
                        </span>

                        <input
                            type="email"
                            x-model="email"
                            placeholder="Enter your email"
                            class="mt-[9px] h-[44px] w-full rounded-[3px] border border-[#BFBFBF] px-[13px] text-[12px] outline-none placeholder:text-[#8D8D8D] focus:border-[#4D7FCF]"
                        >
                    </label>

                    {{-- Password --}}
                    <label class="mt-[24px] block">
                        <span class="text-[12px] text-[#222222]">
                            Password
                        </span>

                        <input
                            type="password"
                            x-model="password"
                            placeholder="Enter Password"
                            class="mt-[9px] h-[44px] w-full rounded-[3px] border border-[#BFBFBF] px-[13px] text-[12px] outline-none placeholder:text-[#8D8D8D] focus:border-[#4D7FCF]"
                        >
                    </label>

                    {{-- Remember / Forgot --}}
                    <div class="mt-[14px] flex items-center justify-between text-[11px]">
                        <label class="flex items-center gap-[10px] text-[#555555]">
                            <input
                                type="checkbox"
                                x-model="remember"
                                class="h-[15px] w-[15px] accent-[#4D7FCF]"
                            >
                            Remember me?
                        </label>

                        <a
                            href="#"
                            class="text-[#4D7FCF] hover:underline"
                        >
                            Forgot password?
                        </a>
                    </div>

                    {{-- Error --}}
                    <p
                        x-show="error"
                        x-cloak
                        x-text="error"
                        class="mt-[14px] text-center text-[11px] text-red-600"
                    ></p>

                    {{-- Login --}}
                    <button
                        type="submit"
                        class="mt-[38px] h-[44px] w-full rounded-[3px] border border-[#BFBFBF] bg-white text-[12px] text-[#222222] transition hover:border-[#4D7FCF] hover:text-[#4D7FCF]"
                    >
                        Log in
                    </button>

                    {{-- Sign up --}}
                    <p class="mt-[16px] text-center text-[11px] text-[#333333]">
                        Don't have an account?

                        <a
                            href="{{ route('register') }}"
                            class="text-[#4D7FCF] hover:underline"
                        >
                            Sign up
                        </a>
                    </p>
                </form>
            </div>
        </div>

    </div>
</section>
@endsection