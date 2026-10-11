
@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();

    $nameParts = preg_split('/\s+/', trim($user->name));

    $initials = collect($nameParts)
        ->filter()
        ->take(2)
        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
        ->implode('');
@endphp

<div class="flex min-h-screen w-full bg-white">

    @include('partials.user-sidebar')

    {{-- Main --}}
    <main
        class="min-h-screen flex-1 bg-gradient-to-br from-[#DCEAFF] via-[#F7FAFF] to-white px-[30px] py-[50px] lg:px-[42px]"
    >
        <div class="mx-auto w-full max-w-[980px]">

            {{-- Header --}}
            <h1
                class="text-[25px] font-medium tracking-[-0.6px] text-[#171717]"
            >
                Profile settings
            </h1>

            <p class="mt-[8px] text-[12px] text-[#666666]">
                Update your personal and company information.
            </p>

            {{-- Settings Grid --}}
            <div class="mt-[72px] grid gap-[28px] xl:grid-cols-2">

                {{-- PERSONAL INFORMATION --}}
                <section
                    class="rounded-[16px] border border-[#BFC3C9] bg-white px-[28px] py-[26px]"
                >
                    <h2 class="text-[13px] font-medium text-[#222222]">
                        Personal information
                    </h2>

                    <div class="mt-[14px] h-px w-full bg-[#DADADA]"></div>

                    {{-- Avatar --}}
                    <div class="mt-[28px] flex items-center gap-[18px]">

                        <div
                            class="flex h-[52px] w-[52px] items-center justify-center rounded-full bg-[#4D7FCF] text-[11px] font-medium text-white"
                        >
                            {{ $initials ?: 'U' }}
                        </div>

                        <span class="text-[10px] text-[#777777]">
                            Profile photo upload coming soon
                        </span>
                    </div>

                    {{-- Profile Form --}}
                    <form
                        method="POST"
                        action="{{ route('profile.update') }}"
                        class="mt-[30px]"
                    >
                        @csrf
                        @method('PATCH')

                        {{-- Full Name --}}
                        <label class="block">
                            <span class="text-[11px] text-[#333333]">
                                First Name and Surname
                            </span>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                required
                                maxlength="255"
                                autocomplete="name"
                                placeholder="First Name and Surname"
                                class="mt-[9px] h-[42px] w-full rounded-[4px] border border-[#BFC3C9] px-[13px] text-[11px] text-[#222222] outline-none transition placeholder:text-[#888888] focus:border-[#4D7FCF]"
                            >
                        </label>

                        @error('name')
                            <p class="mt-[7px] text-[10px] text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                        {{-- Email --}}
                        <label class="mt-[22px] block">
                            <span class="text-[11px] text-[#333333]">
                                Email address
                            </span>

                            <input
                                type="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                required
                                maxlength="255"
                                autocomplete="email"
                                placeholder="Enter email address"
                                class="mt-[9px] h-[42px] w-full rounded-[4px] border border-[#BFC3C9] px-[13px] text-[11px] text-[#222222] outline-none transition placeholder:text-[#888888] focus:border-[#4D7FCF]"
                            >
                        </label>

                        @error('email')
                            <p class="mt-[7px] text-[10px] text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                        {{-- Company --}}
                        <label class="mt-[22px] block">
                            <span class="text-[11px] text-[#333333]">
                                Company name
                                <span class="text-[#999999]">
                                    (optional)
                                </span>
                            </span>

                            <input
                                type="text"
                                name="company_name"
                                value="{{ old('company_name', $user->company_name) }}"
                                maxlength="255"
                                placeholder="Enter company name"
                                class="mt-[9px] h-[42px] w-full rounded-[4px] border border-[#BFC3C9] px-[13px] text-[11px] text-[#222222] outline-none transition placeholder:text-[#888888] focus:border-[#4D7FCF]"
                            >
                        </label>

                        @error('company_name')
                            <p class="mt-[7px] text-[10px] text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                        {{-- Account Type --}}
                        <div class="mt-[22px]">
                            <span class="text-[11px] text-[#333333]">
                                Account type
                            </span>

                            <div
                                class="mt-[9px] flex h-[42px] w-full items-center rounded-[4px] border border-[#E0E0E0] bg-[#F8F9FB] px-[13px] text-[11px] text-[#666666]"
                            >
                                {{ ucfirst($user->account_type ?? 'individual') }}
                            </div>

                            <p class="mt-[7px] text-[10px] text-[#999999]">
                                Account type cannot be changed here.
                            </p>
                        </div>

                        {{-- Success --}}
                        @if (session('profile_success'))
                            <p class="mt-[18px] text-[11px] text-green-600">
                                {{ session('profile_success') }}
                            </p>
                        @endif

                        {{-- Save --}}
                        <button
                            type="submit"
                            class="mt-[24px] h-[38px] cursor-pointer rounded-[7px] bg-black px-[22px] text-[10px] font-medium text-white transition hover:bg-[#222222]"
                        >
                            Save changes
                        </button>
                    </form>
                </section>

                {{-- CHANGE PASSWORD --}}
                <section
                    class="h-fit rounded-[16px] border border-[#BFC3C9] bg-white px-[28px] py-[26px]"
                >
                    <h2 class="text-[13px] font-medium text-[#222222]">
                        Change password
                    </h2>

                    <div class="mt-[14px] h-px w-full bg-[#DADADA]"></div>

                    <form
                        method="POST"
                        action="{{ route('password.update') }}"
                        class="mt-[26px]"
                    >
                        @csrf
                        @method('PUT')

                        {{-- Current Password --}}
                        <label class="block">
                            <span class="text-[11px] text-[#333333]">
                                Current Password
                            </span>

                            <input
                                type="password"
                                name="current_password"
                                required
                                autocomplete="current-password"
                                placeholder="Enter your current password"
                                class="mt-[9px] h-[42px] w-full rounded-[4px] border border-[#BFC3C9] px-[13px] text-[11px] outline-none transition placeholder:text-[#666666] focus:border-[#4D7FCF]"
                            >
                        </label>

                        @error('current_password', 'updatePassword')
                            <p class="mt-[7px] text-[10px] text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                        {{-- New Password --}}
                        <label class="mt-[22px] block">
                            <span class="text-[11px] text-[#333333]">
                                New Password
                            </span>

                            <input
                                type="password"
                                name="password"
                                required
                                minlength="8"
                                autocomplete="new-password"
                                placeholder="At least 8 characters"
                                class="mt-[9px] h-[42px] w-full rounded-[4px] border border-[#BFC3C9] px-[13px] text-[11px] outline-none transition placeholder:text-[#888888] focus:border-[#4D7FCF]"
                            >
                        </label>

                        @error('password', 'updatePassword')
                            <p class="mt-[7px] text-[10px] text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                        {{-- Confirm Password --}}
                        <label class="mt-[22px] block">
                            <span class="text-[11px] text-[#333333]">
                                Confirm new password
                            </span>

                            <input
                                type="password"
                                name="password_confirmation"
                                required
                                minlength="8"
                                autocomplete="new-password"
                                placeholder="Re-enter new password"
                                class="mt-[9px] h-[42px] w-full rounded-[4px] border border-[#BFC3C9] px-[13px] text-[11px] outline-none transition placeholder:text-[#888888] focus:border-[#4D7FCF]"
                            >
                        </label>

                        @error('password_confirmation', 'updatePassword')
                            <p class="mt-[7px] text-[10px] text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                        {{-- Success --}}
                        @if (session('status') === 'password-updated')
                            <p class="mt-[18px] text-[11px] text-green-600">
                                Password updated successfully.
                            </p>
                        @endif

                        {{-- Update --}}
                        <button
                            type="submit"
                            class="mt-[24px] h-[38px] cursor-pointer rounded-[7px] bg-black px-[22px] text-[10px] font-medium text-white transition hover:bg-[#222222]"
                        >
                            Update password
                        </button>
                    </form>
                </section>

            </div>
        </div>
    </main>
</div>
@endsection
