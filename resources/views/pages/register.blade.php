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
                    Create your account
                </h2>

                <form
                    class="mt-[42px]"
                    x-data="{
                        email: '{{ request('email') }}',
                        password: '',
                        repeatPassword: '',
                        error: '',

                        register() {
                            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                            const passwordRegex =
                                /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.#_-])[A-Za-z\d@$!%*?&.#_-]{8,}$/;

                            this.error = '';

                            if (!this.email.trim()) {
                                this.error = 'Email is required.';
                                return;
                            }

                            if (!emailRegex.test(this.email.trim())) {
                                this.error = 'Please enter a valid email address.';
                                return;
                            }

                            if (!passwordRegex.test(this.password)) {
                                this.error =
                                    'Password must contain at least 8 characters, uppercase, lowercase, number and symbol.';
                                return;
                            }

                            if (this.password !== this.repeatPassword) {
                                this.error = 'Passwords do not match.';
                                return;
                            }

                            window.location.href = '{{ route('pricing') }}';
                        }
                    }"
                    @submit.prevent="register()"
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
                    <label class="mt-[20px] block">
                        <span class="text-[12px] text-[#222222]">
                            Password
                        </span>

                        <input
                            type="password"
                            x-model="password"
                            placeholder="Enter Password"
                            class="mt-[9px] h-[44px] w-full rounded-[3px] border border-[#BFBFBF] px-[13px] text-[12px] outline-none placeholder:text-[#8D8D8D] focus:border-[#4D7FCF]"
                        >

                        <p class="mt-[6px] text-[10px] leading-[1.4] text-[#777777]">
                            Use 8 or more characters with uppercase, lowercase,
                            number & symbol.
                        </p>
                    </label>

                    {{-- Repeat --}}
                    <label class="mt-[20px] block">
                        <span class="text-[12px] text-[#222222]">
                            Repeat Password
                        </span>

                        <input
                            type="password"
                            x-model="repeatPassword"
                            placeholder="Repeat Password"
                            class="mt-[9px] h-[44px] w-full rounded-[3px] border border-[#BFBFBF] px-[13px] text-[12px] outline-none placeholder:text-[#8D8D8D] focus:border-[#4D7FCF]"
                        >
                    </label>

                    {{-- Error --}}
                    <p
                        x-show="error"
                        x-cloak
                        x-text="error"
                        class="mt-[14px] text-center text-[11px] leading-[1.45] text-red-600"
                    ></p>

                    {{-- Sign up --}}
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