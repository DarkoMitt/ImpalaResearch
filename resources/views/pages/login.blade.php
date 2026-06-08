@extends('layouts.app')

@section('content')
<section
    class="grid min-h-screen grid-cols-1 lg:grid-cols-2"
    x-data="{ success: false }"
>
    <div class="relative flex flex-col bg-[#7DCEF4] px-10 pt-10 lg:px-20 lg:pt-12">

        <a href="{{ route('home') }}" class="text-white underline">
            ← Home page
        </a>

        <div class="flex flex-1 flex-col justify-center">
            <h1 class="text-[44px] font-bold leading-[1.45] text-white">
                Impala tests.<br>
                We develop.<br>
                You grow.
            </h1>

            <p class="mt-8 max-w-[420px] text-[18px] leading-[1.5] text-white">
                A process that combines testing,
                development, and result validation.
            </p>
        </div>
    </div>

    <div class="flex flex-col items-center justify-center px-8">
        <img src="{{ asset('images/logo.svg') }}" alt="Impala" class="h-[70px] w-auto">

        <h2 class="mt-16 text-[24px] font-bold">
            Welcome back!
        </h2>

        <form
            class="mt-8 w-full max-w-[420px]"
            x-data="{
                email: '',
                password: '',
                success: false,
                error: '',

                login() {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                    this.error = '';
                    this.success = false;

                    if (!this.email.trim()) {
                        this.error = 'Email is required.';
                        return;
                    }

                    if (!emailRegex.test(this.email)) {
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

                    this.success = true;
                }
            }"
        >
            <label class="block">
                <span class="text-[14px]">Email</span>

                <input
                    type="email"
                    x-model="email"
                    placeholder="Enter your email"
                    class="mt-2 h-[58px] w-full rounded-[3px] border border-[#CFCFCF] px-4 outline-none focus:border-[#33B6FF]"
                >
            </label>

            <label class="mt-5 block">
                <span class="text-[14px]">Password</span>

                <input
                    type="password"
                    x-model="password"
                    placeholder="Enter Password"
                    class="mt-2 h-[58px] w-full rounded-[3px] border border-[#CFCFCF] px-4 outline-none focus:border-[#33B6FF]"
                >
            </label>

            <div class="mt-4 flex items-center justify-between text-[13px]">
                <label class="flex items-center gap-2">
                    <input type="checkbox" class="h-4 w-4 accent-[#33B6FF]">
                    Remember me
                </label>

                <a href="#" class="text-[#33B6FF]">
                    Forgot password?
                </a>
            </div>

            <button
                type="button"
                @click="login()"
                class="mt-6 h-[55px] w-full rounded-[3px] bg-[#33B6FF] text-white transition hover:bg-[#159fe4]"
            >
                Log in
            </button>

            <p
                x-show="error"
                x-text="error"
                class="mt-4 text-center text-[14px] font-medium text-red-600"
            ></p>

            <p
                x-show="success"
                class="mt-4 text-center text-[14px] font-medium text-green-600"
            >
                Successful login.
            </p>

            <p class="mt-6 text-center text-[14px]">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-[#33B6FF]">
                    Sign up
                </a>
            </p>
        </form>
    </div>
</section>
@endsection