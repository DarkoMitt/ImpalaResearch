
@extends('layouts.app')

@section('content')

<div class="flex min-h-screen w-full bg-white">

    @include('partials.admin-sidebar')

    {{-- Main --}}
    <main
        class="min-h-screen min-w-0 flex-1 bg-gradient-to-br from-[#DCEAFF] via-[#F7FAFF] to-white px-[30px] py-[44px] lg:px-[42px]"
    >

        {{-- Header --}}
        <div class="flex flex-wrap items-start justify-between gap-4">

            <div>
                <h1
                    class="text-[25px] font-medium tracking-[-0.6px] text-[#171717]"
                >
                    Users
                </h1>

                <p class="mt-[6px] text-[12px] text-[#666666]">
                    {{ number_format($totalUsers) }}
                    {{ $totalUsers === 1 ? 'registered account' : 'registered accounts' }}
                    on Impala Research.
                </p>
            </div>

            {{-- Invite User: future feature --}}
            <span
                class="inline-flex h-[42px] items-center rounded-[7px] border border-[#C5D4EB] bg-white px-[18px] text-[11px] text-[#777777]"
                title="User invitations will be available later"
            >
                Invite user · Coming soon
            </span>

        </div>

        {{-- Search and Filters --}}
        <div
            class="mt-[42px] flex flex-col gap-[20px] lg:flex-row lg:items-center lg:justify-between"
        >

            {{-- Search --}}
            <form
                method="GET"
                action="{{ route('admin.users') }}"
                class="flex w-full items-center gap-[8px] lg:max-w-[380px]"
            >
                <input
                    type="hidden"
                    name="filter"
                    value="{{ $filter }}"
                >

                <div class="relative min-w-0 flex-1">

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
                        type="search"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search users..."
                        class="h-[40px] w-full rounded-[7px] border border-[#C4C7CC] bg-white pl-[38px] pr-[14px] text-[11px] outline-none placeholder:text-[#8A8A8A] focus:border-[#4D7FCF]"
                    >
                </div>

                <button
                    type="submit"
                    class="h-[40px] rounded-[7px] bg-[#4D7FCF] px-[15px] text-[11px] font-medium text-white transition hover:bg-[#416FB4]"
                >
                    Search
                </button>

                @if ($search !== '')
                    <a
                        href="{{ route('admin.users', ['filter' => $filter]) }}"
                        class="text-[11px] text-[#777777] hover:text-[#4D7FCF]"
                    >
                        Clear
                    </a>
                @endif
            </form>

            {{-- Filters --}}
            <div class="flex flex-wrap items-center gap-[10px]">

                @php
                    $filters = [
                        'all' => 'All',
                        'individual' => 'Individual',
                        'company' => 'Company',
                        'admin' => 'Admin',
                        'user' => 'User',
                    ];
                @endphp

                @foreach ($filters as $value => $label)
                    <a
                        href="{{ route('admin.users', array_filter([
                            'filter' => $value,
                            'search' => $search,
                        ], fn ($item) => $item !== '')) }}"
                        class="inline-flex h-[38px] items-center justify-center rounded-[9px] px-[18px] text-[11px] transition
                        {{ $filter === $value
                            ? 'bg-black text-white'
                            : 'border border-[#BFC3C9] bg-white text-[#555555] hover:border-[#4D7FCF]' }}"
                    >
                        {{ $label }}
                    </a>
                @endforeach

            </div>
        </div>

        {{-- Users Table --}}
        <section
            class="mt-[28px] overflow-hidden rounded-[4px] border border-[#BFC3C9] bg-white"
        >

            <div class="w-full overflow-x-auto">

                <div class="min-w-[900px]">

                    {{-- Table Header --}}
                    <div
                        class="grid grid-cols-[1.5fr_2fr_1fr_1fr_1.3fr_1fr] items-center border-b border-[#D5D8DD] bg-[#F8F9FC] px-[22px] py-[14px] text-[9px] font-medium uppercase tracking-[0.04em] text-[#555555]"
                    >
                        <div>Name</div>
                        <div>Email</div>
                        <div>Account Type</div>
                        <div>Role</div>
                        <div>Company</div>
                        <div>Joined</div>
                    </div>

                    {{-- Real Users --}}
                    @forelse ($users as $user)

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

                            $isAdmin = $user->role === 'admin';
                            $isCompany = $user->account_type === 'company';
                        @endphp

                        <div
                            class="grid min-h-[92px] grid-cols-[1.5fr_2fr_1fr_1fr_1.3fr_1fr] items-center border-b border-[#E1E3E7] px-[22px] text-[11px] last:border-b-0"
                        >

                            {{-- Name --}}
                            <div class="flex min-w-0 items-center gap-[12px] pr-3">

                                <div
                                    class="flex h-[34px] w-[34px] shrink-0 items-center justify-center rounded-full bg-[#E3E7EF] text-[11px] font-medium text-[#303746]"
                                >
                                    {{ $initials ?: 'U' }}
                                </div>

                                <div class="min-w-0">

                                    <p
                                        class="truncate font-semibold text-[#172235]"
                                        title="{{ $user->name }}"
                                    >
                                        {{ $user->name }}
                                    </p>

                                    <p class="mt-[3px] font-mono text-[9px] text-[#777777]">
                                        ID #{{ $user->id }}
                                    </p>

                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="break-all pr-[16px] text-[#4D4D4D]">
                                {{ $user->email }}
                            </div>

                            {{-- Account Type --}}
                            <div>
                                @if ($isCompany)
                                    <span
                                        class="rounded-[6px] bg-[#E3ECFF] px-[9px] py-[5px] text-[10px] text-[#536477]"
                                    >
                                        Company
                                    </span>
                                @else
                                    <span
                                        class="rounded-[6px] bg-[#F0F0F0] px-[9px] py-[5px] text-[10px] text-[#555555]"
                                    >
                                        Individual
                                    </span>
                                @endif
                            </div>

                            {{-- Role --}}
                            <div>
                                @if ($isAdmin)
                                    <span
                                        class="rounded-full bg-[#E7EFFF] px-[10px] py-[5px] text-[10px] font-medium text-[#3775D6]"
                                    >
                                        ● Admin
                                    </span>
                                @else
                                    <span
                                        class="rounded-full bg-[#EEF5EE] px-[10px] py-[5px] text-[10px] text-[#35824B]"
                                    >
                                        ● User
                                    </span>
                                @endif
                            </div>

                            {{-- Company --}}
                            <div
                                class="break-words pr-3 text-[#26354B]"
                                title="{{ $user->company_name ?? '' }}"
                            >
                                {{ $user->company_name ?: '—' }}
                            </div>

                            {{-- Joined --}}
                            <div class="text-[#555555]">
                                {{ $user->created_at?->format('M d, Y') ?? '—' }}
                            </div>

                        </div>

                    @empty

                        {{-- Empty State --}}
                        <div
                            class="flex min-h-[240px] flex-col items-center justify-center px-[20px] py-[50px] text-center"
                        >
                            <div
                                class="flex h-[50px] w-[50px] items-center justify-center rounded-full bg-[#EDF3FF]"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="#4D7FCF"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <path d="m21 21-4.3-4.3"></path>
                                </svg>
                            </div>

                            <h3 class="mt-[15px] text-[13px] font-medium text-[#192231]">
                                No users found
                            </h3>

                            <p class="mt-[8px] text-[11px] text-[#777777]">
                                Try another search term or filter.
                            </p>

                            <a
                                href="{{ route('admin.users') }}"
                                class="mt-[18px] text-[11px] text-[#4D7FCF] hover:underline"
                            >
                                Clear all filters
                            </a>
                        </div>

                    @endforelse

                </div>
            </div>

            {{-- Footer / Pagination --}}
            <div
                class="flex min-h-[70px] flex-wrap items-center justify-between gap-4 border-t border-[#D9DCE1] bg-[#F8F9FC] px-[22px] py-[14px]"
            >

                <p class="text-[10px] text-[#555555]">
                    @if ($users->total() > 0)
                        Showing {{ $users->firstItem() }}
                        – {{ $users->lastItem() }}
                        of {{ $users->total() }} results
                    @else
                        Showing 0 results
                    @endif
                </p>

                <div class="flex items-center gap-[8px]">

                    {{-- Previous --}}
                    @if ($users->onFirstPage())
                        <span
                            class="inline-flex h-[38px] cursor-not-allowed items-center rounded-[8px] border border-[#D8DCE2] px-[20px] text-[11px] text-[#AAAAAA]"
                        >
                            Previous
                        </span>
                    @else
                        <a
                            href="{{ $users->previousPageUrl() }}"
                            class="inline-flex h-[38px] items-center rounded-[8px] border border-[#C3C7CE] bg-white px-[20px] text-[11px] text-[#222222] transition hover:border-[#4D7FCF]"
                        >
                            Previous
                        </a>
                    @endif

                    <span class="px-[8px] text-[10px] text-[#777777]">
                        {{ $users->currentPage() }}
                        /
                        {{ $users->lastPage() }}
                    </span>

                    {{-- Next --}}
                    @if ($users->hasMorePages())
                        <a
                            href="{{ $users->nextPageUrl() }}"
                            class="inline-flex h-[38px] items-center rounded-[8px] border border-[#C3C7CE] bg-white px-[20px] text-[11px] text-[#222222] transition hover:border-[#4D7FCF]"
                        >
                            Next
                        </a>
                    @else
                        <span
                            class="inline-flex h-[38px] cursor-not-allowed items-center rounded-[8px] border border-[#D8DCE2] px-[20px] text-[11px] text-[#AAAAAA]"
                        >
                            Next
                        </span>
                    @endif

                </div>
            </div>

        </section>

    </main>
</div>

@endsection
