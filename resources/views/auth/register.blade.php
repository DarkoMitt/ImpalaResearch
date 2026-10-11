
@extends('layouts.app')

@section('content')
<section class="min-h-screen w-full bg-white">
    <div class="grid min-h-screen w-full lg:grid-cols-2">

        {{-- LEFT SIDE --}}
        <div
            class="relative flex min-h-screen flex-col bg-gradient-to-br from-[#487DCF] via-[#315FB2] to-[#142B78] px-10 py-9 text-white md:px-14 lg:px-[72px]"
        >
            {{-- Back to homepage --}}
            <a
                href="{{ route('home') }}"
                class="inline-flex w-fit items-center gap-1 rounded-full border border-white/70 px-[14px] py-[6px] text-[12px] transition hover:bg-white/10"
            >
                ‹ Get back
            </a>

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
                alt="Impala Research"
                class="h-[40px] w-auto"
            >

            <div class="my-auto w-full max-w-[370px] py-10">

                <h2 class="text-center text-[20px] font-medium text-[#111111]">
                    Create your account
                </h2>

                <p class="mt-2 text-center text-[12px] text-[#777777]">
                    Join Impala Research and start exploring.
                </p>

                {{-- General validation errors --}}
                @if ($errors->any())
                    <div
                        role="alert"
                        class="mt-6 rounded-[4px] border border-red-200 bg-red-50 px-4 py-3 text-[11px] text-red-700"
                    >
                        Please check the highlighted fields and try again.
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('register') }}"
                    class="mt-[35px]"
                >
                    @csrf

                    {{-- Full name --}}
                    <label for="name" class="block">
                        <span class="text-[12px] text-[#222222]">
                            Full name
                        </span>
                    </label>

                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                        maxlength="255"
                        placeholder="Enter your full name"
                        aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                        class="mt-[9px] h-[44px] w-full rounded-[3px] border px-[13px] text-[12px] outline-none placeholder:text-[#8D8D8D] focus:border-[#4D7FCF] {{ $errors->has('name') ? 'border-red-500' : 'border-[#BFBFBF]' }}"
                    >

                    @error('name')
                        <p class="mt-[6px] text-[11px] text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                    {{-- Email --}}
                    <label for="email" class="mt-[20px] block">
                        <span class="text-[12px] text-[#222222]">
                            Email address
                        </span>
                    </label>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="username"
                        maxlength="255"
                        placeholder="Enter your email"
                        aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                        class="mt-[9px] h-[44px] w-full rounded-[3px] border px-[13px] text-[12px] outline-none placeholder:text-[#8D8D8D] focus:border-[#4D7FCF] {{ $errors->has('email') ? 'border-red-500' : 'border-[#BFBFBF]' }}"
                    >

                    @error('email')
                        <p class="mt-[6px] text-[11px] text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                    {{-- Password --}}
                    <label for="password" class="mt-[20px] block">
                        <span class="text-[12px] text-[#222222]">
                            Password
                        </span>
                    </label>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="Create a password"
                        aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                        class="mt-[9px] h-[44px] w-full rounded-[3px] border px-[13px] text-[12px] outline-none placeholder:text-[#8D8D8D] focus:border-[#4D7FCF] {{ $errors->has('password') ? 'border-red-500' : 'border-[#BFBFBF]' }}"
                    >

                    <p class="mt-[6px] text-[10px] text-[#888888]">
                        Use a secure password that meets the account requirements.
                    </p>

                    @error('password')
                        <p class="mt-[6px] text-[11px] text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                    {{-- Confirm password --}}
                    <label for="password_confirmation" class="mt-[20px] block">
                        <span class="text-[12px] text-[#222222]">
                            Confirm password
                        </span>
                    </label>

                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Confirm your password"
                        class="mt-[9px] h-[44px] w-full rounded-[3px] border border-[#BFBFBF] px-[13px] text-[12px] outline-none placeholder:text-[#8D8D8D] focus:border-[#4D7FCF]"
                    >

                    @error('password_confirmation')
                        <p class="mt-[6px] text-[11px] text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="mt-[30px] h-[44px] w-full rounded-[3px] border border-[#BFBFBF] bg-white text-[12px] text-[#222222] transition hover:border-[#4D7FCF] hover:text-[#4D7FCF]"
                    >
                        Create account
                    </button>

                    {{-- Login link --}}
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
