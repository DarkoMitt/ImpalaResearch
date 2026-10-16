
@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();

    $nameParts = preg_split('/\s+/', trim($user->name ?? ''));

    $initials = collect($nameParts)
        ->filter()
        ->take(2)
        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
        ->implode('');

    $accountType = ucfirst($user->account_type ?? 'individual');

    $inputClass = 'mt-2 block h-12 w-full rounded-[11px] border border-[#DCE5F3] bg-[#FAFCFF] px-4 text-[12px] text-[#102243] outline-none transition placeholder:text-[#A3B0C2] focus:border-[#3478FF] focus:bg-white focus:ring-2 focus:ring-[#3478FF]/10';

    $labelClass = 'block text-[11px] font-bold text-[#516581]';
@endphp

<div class="flex min-h-screen w-full bg-white">

    @include('partials.user-sidebar')

    <main class="min-w-0 flex-1 bg-gradient-to-br from-[#DCEAFF] via-[#F5F9FF] to-white px-5 py-8 lg:px-9 lg:py-10">

        <div class="mx-auto w-full max-w-[1200px]">

            {{-- Page Header --}}
            <div class="flex flex-wrap items-start justify-between gap-5">

                <div>
                    <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-[#CFE0FF] bg-white/80 px-3 py-1.5 text-[10px] font-bold text-[#3478FF]">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#3478FF]"></span>
                        Account Settings
                    </div>

                    <h1 class="text-[28px] font-bold tracking-[-1px] text-[#0B1B39] lg:text-[32px]">
                        Profile Settings
                    </h1>

                    <p class="mt-2 text-[12px] text-[#7184A5]">
                        Manage your personal information, account details and security.
                    </p>
                </div>

                <div class="hidden items-center gap-2 rounded-[11px] border border-[#D6E4F7] bg-white/80 px-4 py-3 sm:flex">
                    <span class="h-2 w-2 rounded-full bg-[#20B985]"></span>
                    <span class="text-[11px] font-semibold text-[#7184A5]">
                        Account Active
                    </span>
                </div>

            </div>

            {{-- Profile Summary --}}
            <section class="relative mt-8 overflow-hidden rounded-[19px] border border-[#C9DEFF] bg-gradient-to-r from-white via-[#F5F9FF] to-[#E8F2FF] p-6 shadow-[0_2px_6px_rgba(30,64,120,0.06)] lg:p-7">

                <div class="pointer-events-none absolute -right-12 -top-16 h-52 w-52 rounded-full bg-[#DCEBFF]/60"></div>
                <div class="pointer-events-none absolute bottom-[-75px] right-40 h-40 w-40 rounded-full bg-[#E5F0FF]/70"></div>

                <div class="relative flex flex-wrap items-center gap-5">

                    <div class="flex h-[70px] w-[70px] shrink-0 items-center justify-center rounded-[20px] border border-[#C9DDFF] bg-[#DCEAFF] text-[22px] font-bold text-[#3478FF] shadow-sm">
                        {{ $initials ?: 'U' }}
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2.5">
                            <h2 class="break-words text-[20px] font-bold tracking-[-0.5px] text-[#102243]">
                                {{ $user->name }}
                            </h2>

                            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#E1F8ED] px-2.5 py-1 text-[9px] font-bold text-[#13996A]">
                                <span class="h-1.5 w-1.5 rounded-full bg-[#19B981]"></span>
                                Active
                            </span>
                        </div>

                        <p class="mt-1.5 break-all text-[12px] text-[#7184A5]">
                            {{ $user->email }}
                        </p>

                        <div class="mt-3 flex flex-wrap items-center gap-2">

                            <span class="inline-flex items-center gap-1.5 rounded-full border border-[#D8E5F8] bg-white/80 px-3 py-1.5 text-[10px] font-semibold text-[#637DA5]">
                                <svg class="h-3.5 w-3.5 text-[#3478FF]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <circle cx="12" cy="8" r="4"/>
                                    <path stroke-linecap="round" d="M4 21v-2a8 8 0 0116 0v2"/>
                                </svg>
                                {{ $accountType }} Account
                            </span>

                            @if ($user->company_name)
                                <span class="inline-flex items-center gap-1.5 rounded-full border border-[#D8E5F8] bg-white/80 px-3 py-1.5 text-[10px] font-semibold text-[#637DA5]">
                                    <svg class="h-3.5 w-3.5 text-[#3478FF]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <rect x="4" y="3" width="16" height="18" rx="2"/>
                                        <path stroke-linecap="round" d="M9 8h1M14 8h1M9 12h1M14 12h1M9 21v-5h6v5"/>
                                    </svg>
                                    {{ $user->company_name }}
                                </span>
                            @endif

                        </div>
                    </div>

                    <div class="hidden h-12 w-12 shrink-0 items-center justify-center rounded-[14px] bg-white/70 text-[#3478FF] lg:flex">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                            <circle cx="12" cy="8" r="4"/>
                            <path stroke-linecap="round" d="M4 21v-2a8 8 0 0116 0v2"/>
                        </svg>
                    </div>

                </div>

            </section>

            {{-- Settings Grid --}}
            <div class="mt-6 grid items-start gap-6 xl:grid-cols-2">

                {{-- PERSONAL INFORMATION --}}
                <section class="overflow-hidden rounded-[19px] border border-[#DFE8F6] bg-white shadow-[0_2px_6px_rgba(30,64,120,0.06)]">

                    {{-- Card Header --}}
                    <div class="flex items-center gap-3 border-b border-[#EDF1F8] px-6 py-5">

                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-[13px] bg-[#EAF2FF] text-[#3478FF]">
                            <svg class="h-[21px] w-[21px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <circle cx="12" cy="8" r="4"/>
                                <path stroke-linecap="round" d="M4 21v-2a8 8 0 0116 0v2"/>
                            </svg>
                        </div>

                        <div class="min-w-0">
                            <h2 class="text-[14px] font-bold text-[#102243]">
                                Personal Information
                            </h2>

                            <p class="mt-1 text-[11px] text-[#8A9AB3]">
                                Update your profile and company details
                            </p>
                        </div>

                    </div>

                    <div class="p-6">

                        {{-- Avatar Preview --}}
                        <div class="flex items-center gap-4 rounded-[13px] border border-[#E3ECF9] bg-[#F7FAFF] p-4">

                            <div class="flex h-[54px] w-[54px] shrink-0 items-center justify-center rounded-full bg-[#DCEAFF] text-[16px] font-bold text-[#3478FF]">
                                {{ $initials ?: 'U' }}
                            </div>

                            <div class="min-w-0">
                                <p class="text-[12px] font-bold text-[#102243]">
                                    Profile Avatar
                                </p>

                                <p class="mt-1 text-[10px] leading-[1.7] text-[#91A0B6]">
                                    Profile photo upload will be available soon.
                                </p>
                            </div>

                            <span class="ml-auto hidden shrink-0 rounded-[7px] bg-[#EDF2F9] px-2.5 py-1.5 text-[9px] font-bold text-[#8A9AB3] sm:inline-flex">
                                Coming Soon
                            </span>

                        </div>

                        {{-- Profile Form --}}
                        <form
                            method="POST"
                            action="{{ route('profile.update') }}"
                            class="mt-6"
                        >
                            @csrf
                            @method('PATCH')

                            {{-- Full Name --}}
                            <div>
                                <label for="profile-name" class="{{ $labelClass }}">
                                    First Name and Surname
                                    <span class="text-[#E05665]">*</span>
                                </label>

                                <input
                                    id="profile-name"
                                    type="text"
                                    name="name"
                                    value="{{ old('name', $user->name) }}"
                                    required
                                    maxlength="255"
                                    autocomplete="name"
                                    placeholder="Enter your full name"
                                    class="{{ $inputClass }} @error('name') !border-[#E05665] @enderror"
                                >

                                @error('name')
                                    <p class="mt-2 text-[10px] font-medium text-[#D9475A]">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="mt-5">
                                <label for="profile-email" class="{{ $labelClass }}">
                                    Email Address
                                    <span class="text-[#E05665]">*</span>
                                </label>

                                <input
                                    id="profile-email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email', $user->email) }}"
                                    required
                                    maxlength="255"
                                    autocomplete="email"
                                    placeholder="Enter your email address"
                                    class="{{ $inputClass }} @error('email') !border-[#E05665] @enderror"
                                >

                                @error('email')
                                    <p class="mt-2 text-[10px] font-medium text-[#D9475A]">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Company --}}
                            <div class="mt-5">
                                <label for="profile-company" class="{{ $labelClass }}">
                                    Company Name
                                    <span class="ml-1 font-medium text-[#A3B0C2]">(optional)</span>
                                </label>

                                <input
                                    id="profile-company"
                                    type="text"
                                    name="company_name"
                                    value="{{ old('company_name', $user->company_name) }}"
                                    maxlength="255"
                                    placeholder="Enter your company name"
                                    class="{{ $inputClass }} @error('company_name') !border-[#E05665] @enderror"
                                >

                                @error('company_name')
                                    <p class="mt-2 text-[10px] font-medium text-[#D9475A]">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Account Type --}}
                            <div class="mt-5">
                                <label class="{{ $labelClass }}">
                                    Account Type
                                </label>

                                <div class="mt-2 flex h-12 items-center justify-between rounded-[11px] border border-[#E2E8F2] bg-[#F5F8FC] px-4">
                                    <div class="flex items-center gap-2.5">
                                        <svg class="h-4 w-4 text-[#91A0B6]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <circle cx="12" cy="8" r="4"/>
                                            <path stroke-linecap="round" d="M4 21v-2a8 8 0 0116 0v2"/>
                                        </svg>

                                        <span class="text-[12px] font-semibold text-[#7184A5]">
                                            {{ $accountType }}
                                        </span>
                                    </div>

                                    <svg class="h-4 w-4 text-[#B1BDD0]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <rect x="5" y="10" width="14" height="11" rx="2"/>
                                        <path stroke-linecap="round" d="M8 10V7a4 4 0 018 0v3"/>
                                    </svg>
                                </div>

                                <p class="mt-2 text-[10px] text-[#91A0B6]">
                                    Your account type cannot be changed from this page.
                                </p>
                            </div>

                            {{-- Success Message --}}
                            @if (session('profile_success'))
                                <div class="mt-5 flex items-start gap-3 rounded-[11px] border border-[#BDE9D3] bg-[#EEFBF4] px-4 py-3.5">

                                    <svg class="mt-0.5 h-[18px] w-[18px] shrink-0 text-[#159866]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="9"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12l3 3 5-6"/>
                                    </svg>

                                    <p class="text-[11px] font-semibold leading-[1.7] text-[#159866]">
                                        {{ session('profile_success') }}
                                    </p>
                                </div>
                            @endif

                            {{-- Submit --}}
                            <div class="mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-[#EDF1F8] pt-5">

                                <p class="text-[10px] text-[#91A0B6]">
                                    Keep your information up to date.
                                </p>

                                <button
                                    type="submit"
                                    class="inline-flex h-11 cursor-pointer items-center justify-center gap-2 rounded-[11px] bg-[#397CF6] px-5 text-[11px] font-bold text-white shadow-[0_4px_12px_rgba(57,124,246,0.18)] transition hover:bg-[#2869E3]"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 4h12l3 3v13H4V4h1z"/>
                                        <path stroke-linecap="round" d="M8 4v6h8V4M8 20v-7h8v7"/>
                                    </svg>

                                    Save Changes
                                </button>

                            </div>

                        </form>

                    </div>

                </section>

                {{-- CHANGE PASSWORD --}}
                <section class="overflow-hidden rounded-[19px] border border-[#DFE8F6] bg-white shadow-[0_2px_6px_rgba(30,64,120,0.06)]">

                    {{-- Card Header --}}
                    <div class="flex items-center gap-3 border-b border-[#EDF1F8] px-6 py-5">

                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-[13px] bg-[#F0EAFF] text-[#7956E8]">
                            <svg class="h-[21px] w-[21px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <rect x="5" y="10" width="14" height="11" rx="2"/>
                                <path stroke-linecap="round" d="M8 10V7a4 4 0 018 0v3"/>
                            </svg>
                        </div>

                        <div class="min-w-0">
                            <h2 class="text-[14px] font-bold text-[#102243]">
                                Change Password
                            </h2>

                            <p class="mt-1 text-[11px] text-[#8A9AB3]">
                                Update your password to keep your account secure
                            </p>
                        </div>

                    </div>

                    <div class="p-6">

                        {{-- Security Notice --}}
                        <div class="flex items-start gap-3 rounded-[13px] border border-[#DDD7FA] bg-gradient-to-r from-[#F7F4FF] to-[#FAF9FF] p-4">

                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-[10px] bg-[#EDE6FF] text-[#7956E8]">
                                <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 2l8 4v6c0 5-3.4 8-8 10-4.6-2-8-5-8-10V6l8-4z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/>
                                </svg>
                            </div>

                            <div>
                                <p class="text-[11px] font-bold text-[#5544A6]">
                                    Account Security
                                </p>

                                <p class="mt-1 text-[10px] leading-[1.8] text-[#8577B6]">
                                    Choose a strong, unique password that you don't use on other websites.
                                </p>
                            </div>

                        </div>

                        {{-- Password Form --}}
                        <form
                            method="POST"
                            action="{{ route('password.update') }}"
                            class="mt-6"
                        >
                            @csrf
                            @method('PUT')

                            {{-- Current Password --}}
                            <div>
                                <label for="current-password" class="{{ $labelClass }}">
                                    Current Password
                                    <span class="text-[#E05665]">*</span>
                                </label>

                                <input
                                    id="current-password"
                                    type="password"
                                    name="current_password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Enter your current password"
                                    class="{{ $inputClass }} @error('current_password', 'updatePassword') !border-[#E05665] @enderror"
                                >

                                @error('current_password', 'updatePassword')
                                    <p class="mt-2 text-[10px] font-medium text-[#D9475A]">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- New Password --}}
                            <div class="mt-5">
                                <label for="new-password" class="{{ $labelClass }}">
                                    New Password
                                    <span class="text-[#E05665]">*</span>
                                </label>

                                <input
                                    id="new-password"
                                    type="password"
                                    name="password"
                                    required
                                    minlength="8"
                                    autocomplete="new-password"
                                    placeholder="At least 8 characters"
                                    class="{{ $inputClass }} @error('password', 'updatePassword') !border-[#E05665] @enderror"
                                >

                                @error('password', 'updatePassword')
                                    <p class="mt-2 text-[10px] font-medium text-[#D9475A]">
                                        {{ $message }}
                                    </p>
                                @enderror

                                <p class="mt-2 flex items-center gap-1.5 text-[10px] text-[#91A0B6]">
                                    <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="9"/>
                                        <path stroke-linecap="round" d="M12 11v5M12 8h.01"/>
                                    </svg>
                                    Use at least 8 characters.
                                </p>
                            </div>

                            {{-- Confirm Password --}}
                            <div class="mt-5">
                                <label for="confirm-password" class="{{ $labelClass }}">
                                    Confirm New Password
                                    <span class="text-[#E05665]">*</span>
                                </label>

                                <input
                                    id="confirm-password"
                                    type="password"
                                    name="password_confirmation"
                                    required
                                    minlength="8"
                                    autocomplete="new-password"
                                    placeholder="Re-enter your new password"
                                    class="{{ $inputClass }} @error('password_confirmation', 'updatePassword') !border-[#E05665] @enderror"
                                >

                                @error('password_confirmation', 'updatePassword')
                                    <p class="mt-2 text-[10px] font-medium text-[#D9475A]">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Success Message --}}
                            @if (session('status') === 'password-updated')
                                <div class="mt-5 flex items-start gap-3 rounded-[11px] border border-[#BDE9D3] bg-[#EEFBF4] px-4 py-3.5">

                                    <svg class="mt-0.5 h-[18px] w-[18px] shrink-0 text-[#159866]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="9"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12l3 3 5-6"/>
                                    </svg>

                                    <p class="text-[11px] font-semibold text-[#159866]">
                                        Password updated successfully.
                                    </p>

                                </div>
                            @endif

                            {{-- Submit --}}
                            <div class="mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-[#EDF1F8] pt-5">

                                <p class="text-[10px] text-[#91A0B6]">
                                    Your password is stored securely.
                                </p>

                                <button
                                    type="submit"
                                    class="inline-flex h-11 cursor-pointer items-center justify-center gap-2 rounded-[11px] bg-[#397CF6] px-5 text-[11px] font-bold text-white shadow-[0_4px_12px_rgba(57,124,246,0.18)] transition hover:bg-[#2869E3]"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <rect x="5" y="10" width="14" height="11" rx="2"/>
                                        <path stroke-linecap="round" d="M8 10V7a4 4 0 018 0v3"/>
                                    </svg>

                                    Update Password
                                </button>

                            </div>

                        </form>

                        {{-- Security Tips --}}
                        <div class="mt-6 rounded-[13px] border border-[#E5ECF7] bg-[#FAFCFF] p-4">

                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 text-[#3478FF]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 2l8 4v6c0 5-3.4 8-8 10-4.6-2-8-5-8-10V6l8-4z"/>
                                </svg>

                                <h3 class="text-[11px] font-bold text-[#102243]">
                                    Password Recommendations
                                </h3>
                            </div>

                            <div class="mt-3 space-y-2">

                                <div class="flex items-center gap-2 text-[10px] text-[#7184A5]">
                                    <span class="h-1.5 w-1.5 rounded-full bg-[#3478FF]"></span>
                                    Use a mix of letters, numbers and symbols.
                                </div>

                                <div class="flex items-center gap-2 text-[10px] text-[#7184A5]">
                                    <span class="h-1.5 w-1.5 rounded-full bg-[#3478FF]"></span>
                                    Avoid using personal information.
                                </div>

                                <div class="flex items-center gap-2 text-[10px] text-[#7184A5]">
                                    <span class="h-1.5 w-1.5 rounded-full bg-[#3478FF]"></span>
                                    Don't reuse passwords across accounts.
                                </div>

                            </div>

                        </div>

                    </div>

                </section>

            </div>

            {{-- Footer --}}
            <div class="mt-8 flex flex-wrap items-center justify-between gap-3 border-t border-[#DCE7F5] pt-5">
                <p class="text-[10px] text-[#91A0B6]">
                    Impala Research · Account Settings
                </p>

                <p class="text-[10px] text-[#91A0B6]">
                    Profile & Security
                </p>
            </div>

        </div>

    </main>

</div>
@endsection
