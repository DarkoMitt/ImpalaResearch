@extends('layouts.app')

@section('content')
<div
    class="flex min-h-screen w-full bg-white"
    x-data="{
        fullName: 'Sara Krstic',
        email: 'sara@brightlensmedia.com',
        company: 'Bright Lens Media',

        currentPassword: '',
        newPassword: '',
        confirmPassword: '',

        profileError: '',
        profileSuccess: '',

        passwordError: '',
        passwordSuccess: '',

        saveProfile() {
            this.profileError = '';
            this.profileSuccess = '';

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!this.fullName.trim()) {
                this.profileError = 'Full name is required.';
                return;
            }

            if (!this.email.trim()) {
                this.profileError = 'Email address is required.';
                return;
            }

            if (!emailRegex.test(this.email.trim())) {
                this.profileError = 'Please enter a valid email address.';
                return;
            }

            if (!this.company.trim()) {
                this.profileError = 'Company name is required.';
                return;
            }

            this.profileSuccess = 'Profile changes saved successfully.';
        },

        updatePassword() {
            this.passwordError = '';
            this.passwordSuccess = '';

            if (!this.currentPassword.trim()) {
                this.passwordError = 'Current password is required.';
                return;
            }

            if (!this.newPassword.trim()) {
                this.passwordError = 'New password is required.';
                return;
            }

            if (this.newPassword.length < 8) {
                this.passwordError = 'New password must be at least 8 characters.';
                return;
            }

            if (this.newPassword !== this.confirmPassword) {
                this.passwordError = 'Passwords do not match.';
                return;
            }

            this.passwordSuccess = 'Password updated successfully.';

            this.currentPassword = '';
            this.newPassword = '';
            this.confirmPassword = '';
        }
    }"
>
    @include('partials.user-sidebar')

    {{-- Main --}}
    <main
        class="min-h-screen flex-1 bg-gradient-to-br from-[#DCEAFF] via-[#F7FAFF] to-white px-[30px] py-[50px] lg:px-[42px]"
    >
        {{-- Header --}}
        <div class="mx-auto w-full max-w-[980px]">
            <h1
                class="text-[25px] font-medium tracking-[-0.6px] text-[#171717]"
            >
                Profile settings
            </h1>

            <p class="mt-[8px] text-[12px] text-[#666666]">
                Update your personal and company information.
            </p>

            {{-- Settings grid --}}
            <div class="mt-[72px] grid gap-[28px] xl:grid-cols-2">

                {{-- PERSONAL INFORMATION --}}
                <section
                    class="rounded-[16px] border border-[#BFC3C9] bg-white px-[28px] py-[26px]"
                >
                    <h2 class="text-[13px] font-medium text-[#222222]">
                        Personal informations
                    </h2>

                    <div class="mt-[14px] h-px w-full bg-[#DADADA]"></div>

                    {{-- Avatar --}}
                    <div class="mt-[28px] flex items-center gap-[18px]">
                        <div
                            class="flex h-[52px] w-[52px] items-center justify-center rounded-full bg-[#4D7FCF] text-[11px] font-medium text-white"
                        >
                            SK
                        </div>

                        <button
                            type="button"
                            @click="alert('Photo upload will be connected later.')"
                            class="h-[32px] rounded-[8px] border border-[#AEB3BB] px-[14px] text-[10px] text-[#333333] transition hover:border-[#4D7FCF] hover:text-[#4D7FCF]"
                        >
                            Change photo
                        </button>
                    </div>

                    {{-- Full name --}}
                    <label class="mt-[30px] block">
                        <span class="text-[11px] text-[#333333]">
                            First Name and Surname
                        </span>

                        <input
                            type="text"
                            x-model="fullName"
                            placeholder="First Name and Surname"
                            class="mt-[9px] h-[42px] w-full rounded-[4px] border border-[#BFC3C9] px-[13px] text-[11px] text-[#222222] outline-none transition placeholder:text-[#888888] focus:border-[#4D7FCF]"
                        >
                    </label>

                    {{-- Email --}}
                    <label class="mt-[22px] block">
                        <span class="text-[11px] text-[#333333]">
                            Email address
                        </span>

                        <input
                            type="email"
                            x-model="email"
                            placeholder="Enter email address"
                            class="mt-[9px] h-[42px] w-full rounded-[4px] border border-[#BFC3C9] px-[13px] text-[11px] text-[#222222] outline-none transition placeholder:text-[#888888] focus:border-[#4D7FCF]"
                        >
                    </label>

                    {{-- Company --}}
                    <label class="mt-[22px] block">
                        <span class="text-[11px] text-[#333333]">
                            Company name
                        </span>

                        <input
                            type="text"
                            x-model="company"
                            placeholder="Enter company name"
                            class="mt-[9px] h-[42px] w-full rounded-[4px] border border-[#BFC3C9] px-[13px] text-[11px] text-[#222222] outline-none transition placeholder:text-[#888888] focus:border-[#4D7FCF]"
                        >
                    </label>

                    {{-- Error --}}
                    <p
                        x-show="profileError"
                        x-cloak
                        x-text="profileError"
                        class="mt-[14px] text-[10px] text-red-600"
                    ></p>

                    {{-- Success --}}
                    <p
                        x-show="profileSuccess"
                        x-cloak
                        x-text="profileSuccess"
                        class="mt-[14px] text-[10px] text-green-600"
                    ></p>

                    {{-- Save --}}
                    <button
                        type="button"
                        @click="saveProfile()"
                        class="mt-[24px] h-[38px] rounded-[7px] bg-black px-[22px] text-[10px] font-medium text-white transition hover:bg-[#222222]"
                    >
                        Save changes
                    </button>
                </section>

                {{-- CHANGE PASSWORD --}}
                <section
                    class="h-fit rounded-[16px] border border-[#BFC3C9] bg-white px-[28px] py-[26px]"
                >
                    <h2 class="text-[13px] font-medium text-[#222222]">
                        Change password
                    </h2>

                    <div class="mt-[14px] h-px w-full bg-[#DADADA]"></div>

                    {{-- Current --}}
                    <label class="mt-[26px] block">
                        <span class="text-[11px] text-[#333333]">
                            Current Password
                        </span>

                        <input
                            type="password"
                            x-model="currentPassword"
                            placeholder="Enter your current password"
                            class="mt-[9px] h-[42px] w-full rounded-[4px] border border-[#BFC3C9] px-[13px] text-[11px] outline-none transition placeholder:text-[#666666] focus:border-[#4D7FCF]"
                        >
                    </label>

                    {{-- New --}}
                    <label class="mt-[22px] block">
                        <span class="text-[11px] text-[#333333]">
                            New Password
                        </span>

                        <input
                            type="password"
                            x-model="newPassword"
                            placeholder="At least 8 characters"
                            class="mt-[9px] h-[42px] w-full rounded-[4px] border border-[#BFC3C9] px-[13px] text-[11px] outline-none transition placeholder:text-[#888888] focus:border-[#4D7FCF]"
                        >
                    </label>

                    {{-- Confirm --}}
                    <label class="mt-[22px] block">
                        <span class="text-[11px] text-[#333333]">
                            Confirm new password
                        </span>

                        <input
                            type="password"
                            x-model="confirmPassword"
                            placeholder="Re-enter new password"
                            class="mt-[9px] h-[42px] w-full rounded-[4px] border border-[#BFC3C9] px-[13px] text-[11px] outline-none transition placeholder:text-[#888888] focus:border-[#4D7FCF]"
                        >
                    </label>

                    {{-- Error --}}
                    <p
                        x-show="passwordError"
                        x-cloak
                        x-text="passwordError"
                        class="mt-[14px] text-[10px] text-red-600"
                    ></p>

                    {{-- Success --}}
                    <p
                        x-show="passwordSuccess"
                        x-cloak
                        x-text="passwordSuccess"
                        class="mt-[14px] text-[10px] text-green-600"
                    ></p>

                    {{-- Update --}}
                    <button
                        type="button"
                        @click="updatePassword()"
                        class="mt-[24px] h-[38px] rounded-[7px] bg-black px-[22px] text-[10px] font-medium text-white transition hover:bg-[#222222]"
                    >
                        Update password
                    </button>
                </section>

            </div>
        </div>
    </main>
</div>
@endsection