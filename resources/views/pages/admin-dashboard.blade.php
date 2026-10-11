
@extends('layouts.app')

@section('content')

<div class="flex min-h-screen w-full bg-white">

    @include('partials.admin-sidebar')

    {{-- Main --}}
    <main
        class="min-h-screen min-w-0 flex-1 bg-gradient-to-br from-[#DCEAFF] via-[#F6F9FF] to-white px-[26px] py-[42px] lg:px-[34px]"
    >

        {{-- Header --}}
        <div class="flex flex-wrap items-start justify-between gap-4">

            <div>
                <h1 class="text-[25px] font-medium tracking-[-0.6px] text-[#171717]">
                    Platform overview
                </h1>

                <p class="mt-[6px] text-[12px] text-[#666666]">
                    Live overview of registered accounts on Impala Research.
                </p>
            </div>

            <div
                class="inline-flex items-center gap-[8px] rounded-full bg-[#F0F2F7] px-[14px] py-[8px] text-[11px] text-[#666666]"
            >
                <span class="h-[6px] w-[6px] rounded-full bg-[#3D9C54]"></span>
                Updated on page load
            </div>

        </div>

        {{-- Stats --}}
        <div class="mt-[34px] grid gap-[12px] sm:grid-cols-2 xl:grid-cols-4">

            {{-- Total Users --}}
            <div class="rounded-[14px] border border-[#BFC3C9] bg-white px-[18px] py-[20px]">

                <p class="text-[10px] uppercase text-[#666666]">
                    Total Users
                </p>

                <h2 class="mt-[20px] text-[22px] font-medium">
                    {{ number_format($totalUsers) }}
                </h2>

                <p class="mt-[10px] text-[10px] text-[#3D9C54]">
                    + {{ $newUsersThisMonth }} this month
                </p>
            </div>

            {{-- Individual Accounts --}}
            <div class="rounded-[14px] border border-[#BFC3C9] bg-white px-[18px] py-[20px]">

                <p class="text-[10px] uppercase text-[#666666]">
                    Individual Accounts
                </p>

                <h2 class="mt-[20px] text-[22px] font-medium">
                    {{ number_format($individualAccounts) }}
                </h2>

                <p class="mt-[10px] text-[10px] text-[#777777]">
                    {{ $individualPercentage }}% of all accounts
                </p>
            </div>

            {{-- Company Accounts --}}
            <div class="rounded-[14px] border border-[#BFC3C9] bg-white px-[18px] py-[20px]">

                <p class="text-[10px] uppercase text-[#666666]">
                    Company Accounts
                </p>

                <h2 class="mt-[20px] text-[22px] font-medium">
                    {{ number_format($companyAccounts) }}
                </h2>

                <p class="mt-[10px] text-[10px] text-[#777777]">
                    {{ $companyPercentage }}% of all accounts
                </p>
            </div>

            {{-- Admin Accounts --}}
            <div class="rounded-[14px] border border-[#BFC3C9] bg-white px-[18px] py-[20px]">

                <p class="text-[10px] uppercase text-[#666666]">
                    Admin Accounts
                </p>

                <h2 class="mt-[20px] text-[22px] font-medium">
                    {{ number_format($adminAccounts) }}
                </h2>

                <p class="mt-[10px] text-[10px] text-[#777777]">
                    Users with administrator access
                </p>
            </div>

        </div>

        {{-- Lower Grid --}}
        <div class="mt-[14px] grid gap-[14px] xl:grid-cols-[1fr_230px]">

            {{-- Recent Registrations --}}
            <section
                class="overflow-hidden rounded-[12px] border border-[#BFC3C9] bg-white"
            >

                <div
                    class="flex h-[48px] items-center justify-between border-b border-[#E6E6E6] px-[20px]"
                >
                    <h2 class="text-[13px] font-medium text-[#202939]">
                        Recent Registrations
                    </h2>

                    <a
                        href="{{ route('admin.users') }}"
                        class="text-[10px] text-[#4D7FCF] hover:underline"
                    >
                        View all
                    </a>
                </div>

                @forelse ($recentUsers as $user)

                    @php
                        $nameParts = preg_split(
                            '/\s+/',
                            trim($user->name)
                        );

                        $initials = collect($nameParts)
                            ->filter()
                            ->take(2)
                            ->map(
                                fn ($part) => mb_strtoupper(
                                    mb_substr($part, 0, 1)
                                )
                            )
                            ->implode('');
                    @endphp

                    <div
                        class="flex min-h-[78px] flex-wrap items-center justify-between gap-3 border-b border-[#EAEAEA] px-[18px] py-[14px] last:border-b-0"
                    >

                        <div class="flex min-w-0 items-center gap-[12px]">

                            {{-- Avatar --}}
                            <div
                                class="flex h-[34px] w-[34px] shrink-0 items-center justify-center rounded-[7px] bg-[#E9EDFF] text-[11px] font-medium text-[#4D7FCF]"
                            >
                                {{ $initials ?: 'U' }}
                            </div>

                            <div class="min-w-0">

                                <p class="truncate text-[12px] font-medium text-[#202939]">
                                    {{ $user->name }}
                                </p>

                                <p class="mt-[5px] break-all text-[10px] text-[#777777]">
                                    {{ $user->email }}
                                </p>

                                <p class="mt-[4px] text-[9px] text-[#999999]">
                                    Joined
                                    {{ $user->created_at?->format('M d, Y') ?? '—' }}
                                </p>

                            </div>
                        </div>

                        {{-- Account Type --}}
                        @if ($user->role === 'admin')

                            <span
                                class="rounded-full bg-[#E7EFFF] px-[12px] py-[5px] text-[9px] font-medium text-[#3775D6]"
                            >
                                ● Admin
                            </span>

                        @elseif ($user->account_type === 'company')

                            <span
                                class="rounded-full bg-[#E0F4E5] px-[12px] py-[5px] text-[9px] font-medium text-[#248743]"
                            >
                                ● Company
                            </span>

                        @else

                            <span
                                class="rounded-full bg-[#F0F0F0] px-[12px] py-[5px] text-[9px] font-medium text-[#555555]"
                            >
                                ● Individual
                            </span>

                        @endif

                    </div>

                @empty

                    <div class="px-[20px] py-[60px] text-center">

                        <p class="text-[12px] font-medium text-[#202939]">
                            No registered users yet
                        </p>

                        <p class="mt-[8px] text-[10px] text-[#777777]">
                            New registrations will appear here.
                        </p>

                    </div>

                @endforelse

            </section>

            {{-- Account Distribution --}}
            <aside
                class="rounded-[12px] border border-[#BFC3C9] bg-white px-[18px] py-[18px]"
            >

                <h2 class="text-[13px] font-medium text-[#202939]">
                    Account distribution
                </h2>

                <div class="mt-[28px] space-y-[26px] text-[12px]">

                    {{-- Individual --}}
                    <div class="flex justify-between gap-2">

                        <span>Individual</span>

                        <span class="text-[#555555]">
                            <strong>{{ $individualAccounts }}</strong>
                            <span class="text-[#888888]">accounts</span>
                        </span>

                    </div>

                    <div class="border-t border-dashed border-[#D9D9D9] pt-[24px]">

                        {{-- Company --}}
                        <div class="flex justify-between gap-2">

                            <span>Company</span>

                            <span class="text-[#555555]">
                                <strong>{{ $companyAccounts }}</strong>
                                <span class="text-[#888888]">accounts</span>
                            </span>

                        </div>

                    </div>

                    <div class="border-t border-dashed border-[#D9D9D9] pt-[24px]">

                        {{-- Admin --}}
                        <div class="flex justify-between gap-2">

                            <span>Admins</span>

                            <span class="text-[#555555]">
                                <strong>{{ $adminAccounts }}</strong>
                                <span class="text-[#888888]">accounts</span>
                            </span>

                        </div>

                    </div>

                </div>

                {{-- Account Type Distribution --}}
                <div class="mt-[38px]">

                    <div class="flex justify-between text-[11px]">

                        <span class="font-medium">
                            Individual Share
                        </span>

                        <span>
                            {{ $individualPercentage }}%
                        </span>

                    </div>

                    <div
                        class="mt-[9px] h-[6px] overflow-hidden rounded-full bg-[#E6ECF5]"
                    >
                        <div
                            class="h-full rounded-full bg-[#4D7FCF]"
                            style="width: {{ $individualPercentage }}%"
                        ></div>
                    </div>

                    <div class="mt-[22px] flex justify-between text-[11px]">

                        <span class="font-medium">
                            Company Share
                        </span>

                        <span>
                            {{ $companyPercentage }}%
                        </span>

                    </div>

                    <div
                        class="mt-[9px] h-[6px] overflow-hidden rounded-full bg-[#E6ECF5]"
                    >
                        <div
                            class="h-full rounded-full bg-[#5646E8]"
                            style="width: {{ $companyPercentage }}%"
                        ></div>
                    </div>

                </div>

                {{-- Users Link --}}
                <a
                    href="{{ route('admin.users') }}"
                    class="mt-[34px] flex h-[38px] w-full items-center justify-center rounded-[7px] border border-[#C5C5C5] text-[11px] transition hover:border-[#4D7FCF] hover:text-[#4D7FCF]"
                >
                    Manage users
                </a>

            </aside>

        </div>

    </main>
</div>

@endsection
