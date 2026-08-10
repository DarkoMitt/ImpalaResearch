@extends('layouts.app')

@section('content')
<div
    class="flex min-h-screen w-full bg-white"
    x-data="{
        search: '',
        filter: 'all',

        users: [
            {
                name: 'Jordan Davis',
                email: 'jordan.d@agency-nexus.com',
                type: 'Agency',
                plan: 'Semi-Yearly',
                status: 'Active',
                lastActive: '2m ago'
            },
            {
                name: 'Elena Rodriguez',
                email: 'e.rodriguez@brandpulse.io',
                type: 'Brand',
                plan: 'Monthly',
                status: 'Payment due',
                lastActive: '1h ago'
            },
            {
                name: 'Sam Kim',
                email: 'skim@design-studio.co',
                type: 'Agency',
                plan: 'Yearly',
                status: 'Suspended',
                lastActive: '2d ago'
            },
            {
                name: 'Arthur Vance',
                email: 'vance.a@global-tech.com',
                type: 'Brand',
                plan: 'Yearly',
                status: 'Active',
                lastActive: '4h ago'
            }
        ],

        get filteredUsers() {
            return this.users.filter(user => {
                const query = this.search.toLowerCase().trim();

                const matchesSearch =
                    !query ||
                    user.name.toLowerCase().includes(query) ||
                    user.email.toLowerCase().includes(query);

                const matchesFilter =
                    this.filter === 'all' ||
                    user.type.toLowerCase() === this.filter ||
                    (
                        this.filter === 'suspended' &&
                        user.status === 'Suspended'
                    );

                return matchesSearch && matchesFilter;
            });
        },

        inviteUser() {
            alert('Invite User will be connected to the backend later.');
        }
    }"
>
    @include('partials.admin-sidebar')

    {{-- Main --}}
    <main
        class="min-h-screen flex-1 bg-gradient-to-br from-[#DCEAFF] via-[#F7FAFF] to-white px-[30px] py-[44px] lg:px-[42px]"
    >
        {{-- Header --}}
        <div class="flex items-start justify-between">

            <div>
                <h1
                    class="text-[25px] font-medium tracking-[-0.6px] text-[#171717]"
                >
                    Users
                </h1>

                <p class="mt-[6px] text-[12px] text-[#666666]">
                    1,284 accounts across all plans.
                </p>
            </div>

            <button
                type="button"
                @click="inviteUser()"
                class="h-[42px] rounded-[7px] bg-[#4D7FCF] px-[24px] text-[12px] font-medium text-white transition hover:bg-[#416FB4]"
            >
                Invite user
            </button>
        </div>

        {{-- Search + filters --}}
        <div
            class="mt-[42px] flex flex-col gap-[20px] lg:flex-row lg:items-center lg:justify-between"
        >
            {{-- Search --}}
            <div class="relative w-full max-w-[280px]">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    class="absolute left-[12px] top-1/2 h-[16px] w-[16px] -translate-y-1/2 text-[#888888]"
                >
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                </svg>

                <input
                    type="text"
                    x-model="search"
                    placeholder="Search team members..."
                    class="h-[40px] w-full rounded-[7px] border border-[#C4C7CC] bg-white pl-[38px] pr-[14px] text-[11px] outline-none placeholder:text-[#8A8A8A] focus:border-[#4D7FCF]"
                >
            </div>

            {{-- Filters --}}
            <div class="flex flex-wrap items-center gap-[10px]">

                <button
                    type="button"
                    @click="filter = 'all'"
                    :class="filter === 'all'
                        ? 'bg-black text-white'
                        : 'border border-[#BFC3C9] bg-white text-[#555555]'"
                    class="h-[38px] rounded-[9px] px-[20px] text-[11px] transition"
                >
                    All
                </button>

                <button
                    type="button"
                    @click="filter = 'agency'"
                    :class="filter === 'agency'
                        ? 'bg-black text-white'
                        : 'border border-[#BFC3C9] bg-white text-[#555555]'"
                    class="h-[38px] rounded-[9px] px-[20px] text-[11px] transition"
                >
                    Agency
                </button>

                <button
                    type="button"
                    @click="filter = 'brand'"
                    :class="filter === 'brand'
                        ? 'bg-black text-white'
                        : 'border border-[#BFC3C9] bg-white text-[#555555]'"
                    class="h-[38px] rounded-[9px] px-[20px] text-[11px] transition"
                >
                    Brand
                </button>

                <button
                    type="button"
                    @click="filter = 'suspended'"
                    :class="filter === 'suspended'
                        ? 'bg-black text-white'
                        : 'border border-[#BFC3C9] bg-white text-[#555555]'"
                    class="h-[38px] rounded-[9px] px-[20px] text-[11px] transition"
                >
                    Suspended
                </button>
            </div>
        </div>

        {{-- Users table --}}
        <section
            class="mt-[28px] overflow-hidden rounded-[4px] border border-[#BFC3C9] bg-white"
        >
            {{-- Header --}}
            <div
                class="grid grid-cols-[1.6fr_2.2fr_1fr_1fr_1.2fr_0.7fr] items-center border-b border-[#D5D8DD] bg-[#F8F9FC] px-[22px] py-[14px] text-[9px] font-medium uppercase tracking-[0.04em] text-[#555555]"
            >
                <div>Name</div>
                <div>Email</div>
                <div>Account Type</div>
                <div>Current Plan</div>
                <div>Status</div>
                <div>Actions</div>
            </div>

            {{-- Rows --}}
            <template
                x-for="user in filteredUsers"
                :key="user.email"
            >
                <div
                    class="grid min-h-[92px] grid-cols-[1.6fr_2.2fr_1fr_1fr_1.2fr_0.7fr] items-center border-b border-[#E1E3E7] px-[22px] text-[11px] last:border-b-0"
                >
                    {{-- User --}}
                    <div class="flex items-center gap-[14px]">

                        <div
                            class="flex h-[34px] w-[34px] shrink-0 items-center justify-center rounded-full bg-[#E3E7EF] text-[11px] font-medium text-[#303746]"
                        >
                            JD
                        </div>

                        <div>
                            <p
                                class="font-semibold text-[#172235]"
                                x-text="user.name"
                            ></p>

                            <p class="mt-[3px] font-mono text-[9px] leading-[1.2] text-[#555555]">
                                Last active:
                                <span x-text="user.lastActive"></span>
                            </p>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div
                        class="break-all pr-[16px] text-[#4D4D4D]"
                        x-text="user.email"
                    ></div>

                    {{-- Account type --}}
                    <div>
                        <span
                            class="rounded-[6px] bg-[#E3ECFF] px-[9px] py-[5px] text-[#536477]"
                            x-text="user.type"
                        ></span>
                    </div>

                    {{-- Plan --}}
                    <div
                        class="text-[#26354B]"
                        x-text="user.plan"
                    ></div>

                    {{-- Status --}}
                    <div>
                        <template x-if="user.status === 'Active'">
                            <span
                                class="rounded-full bg-[#EEF0FF] px-[9px] py-[5px] text-[9px] text-[#4B4BF5]"
                            >
                                ● Active
                            </span>
                        </template>

                        <template x-if="user.status === 'Payment due'">
                            <span
                                class="rounded-full bg-[#FFE6E6] px-[9px] py-[5px] text-[9px] text-[#D51F1F]"
                            >
                                ● Payment due
                            </span>
                        </template>

                        <template x-if="user.status === 'Suspended'">
                            <span
                                class="rounded-full bg-[#EFEFEF] px-[9px] py-[5px] text-[9px] text-[#555555]"
                            >
                                ● Suspended
                            </span>
                        </template>
                    </div>

                    {{-- Actions --}}
                    <div>
                        <button
                            type="button"
                            @click="alert('User actions will be connected to backend later.')"
                            class="text-[18px] tracking-[2px] text-[#666666]"
                        >
                            ⋯
                        </button>
                    </div>
                </div>
            </template>

            {{-- Empty search --}}
            <div
                x-show="filteredUsers.length === 0"
                class="px-[20px] py-[60px] text-center text-[12px] text-[#777777]"
            >
                No users found.
            </div>

            {{-- Footer --}}
            <div
                class="flex min-h-[70px] items-center justify-between border-t border-[#D9DCE1] bg-[#F8F9FC] px-[22px]"
            >
                <p class="text-[10px] text-[#555555]">
                    Showing
                    <span x-text="filteredUsers.length"></span>
                    of 1,284 results
                </p>

                <div class="flex gap-[8px]">
                    <button
                        type="button"
                        class="h-[38px] rounded-[8px] border border-[#C3C7CE] px-[20px] text-[11px] text-[#7B7B7B]"
                    >
                        Previous
                    </button>

                    <button
                        type="button"
                        class="h-[38px] rounded-[8px] border border-[#C3C7CE] px-[20px] text-[11px] text-[#222222]"
                    >
                        Next
                    </button>
                </div>
            </div>
        </section>
    </main>
</div>
@endsection