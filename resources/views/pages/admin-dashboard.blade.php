
@extends('layouts.app')

@section('content')
<div class="flex min-h-screen w-full bg-[#F5F8FF]">

    @include('partials.admin-sidebar')

    <main class="min-w-0 flex-1 bg-gradient-to-br from-[#DDEAFF] via-[#F4F8FF] to-[#FFFFFF] px-5 py-8 lg:px-9 lg:py-10">

        {{-- Header --}}
        <div class="flex flex-wrap items-center justify-between gap-5">

            <div>
                <div class="mb-3 flex items-center gap-2 text-[11px] font-medium text-[#8A9BB8]">
                    <span>Admin Workspace</span>
                    <span>/</span>
                    <span class="text-[#3478F6]">Overview</span>
                </div>

                <h1 class="text-[29px] font-bold tracking-[-1px] text-[#0B1B39]">
                    Platform Overview
                </h1>

                <p class="mt-2 text-[12px] text-[#7184A5]">
                    Monitor your platform, users and research activity in one place.
                </p>
            </div>

            <div class="inline-flex items-center gap-2 rounded-full border border-[#DCE7F7] bg-white/80 px-4 py-2.5 shadow-sm">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="absolute inline-flex h-full w-full rounded-full bg-[#22B573] opacity-25"></span>
                    <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-[#22B573]"></span>
                </span>
                <span class="text-[11px] font-semibold text-[#50627F]">
                    Updated on page load
                </span>
            </div>

        </div>

        {{-- User Statistics --}}
        <div class="mt-9 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">

            {{-- Total Users --}}
            <div class="relative overflow-hidden rounded-[19px] border border-[#C8DEFF] bg-gradient-to-br from-white to-[#EDF6FF] p-6 shadow-[0_2px_5px_rgba(30,64,120,0.06)]">
                <div class="pointer-events-none absolute -bottom-14 -right-12 h-40 w-40 rounded-full bg-[#DCEEFF]/70"></div>

                <div class="relative flex items-start justify-between gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-[15px] bg-[#E2EFFF] text-[#3478FF]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zM22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                        </svg>
                    </div>

                    <span class="rounded-full bg-[#E4F6EC] px-3 py-1 text-[10px] font-semibold text-[#17985C]">
                        +{{ number_format($newUsersThisMonth) }} this month
                    </span>
                </div>

                <div class="relative mt-6">
                    <p class="text-[12px] font-semibold text-[#516581]">Total Users</p>
                    <h2 class="mt-2 text-[34px] font-bold tracking-[-1px] text-[#0B1B39]">
                        {{ number_format($totalUsers) }}
                    </h2>
                    <p class="mt-2 text-[11px] text-[#8799B3]">Registered platform accounts</p>
                </div>
            </div>

            {{-- Individual --}}
            <div class="relative overflow-hidden rounded-[19px] border border-[#D7DCFF] bg-gradient-to-br from-white to-[#F0F1FF] p-6 shadow-[0_2px_5px_rgba(30,64,120,0.06)]">
                <div class="pointer-events-none absolute -bottom-14 -right-12 h-40 w-40 rounded-full bg-[#E5E8FF]/70"></div>

                <div class="relative flex items-start justify-between">
                    <div class="flex h-12 w-12 items-center justify-center rounded-[15px] bg-[#E8E9FF] text-[#6958EA]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <circle cx="12" cy="8" r="4"/>
                            <path stroke-linecap="round" d="M4 21v-2a8 8 0 0116 0v2"/>
                        </svg>
                    </div>

                    <span class="rounded-full bg-white/80 px-3 py-1 text-[10px] font-semibold text-[#6958EA]">
                        {{ $individualPercentage }}%
                    </span>
                </div>

                <div class="relative mt-6">
                    <p class="text-[12px] font-semibold text-[#516581]">Individual Accounts</p>
                    <h2 class="mt-2 text-[34px] font-bold tracking-[-1px] text-[#0B1B39]">
                        {{ number_format($individualAccounts) }}
                    </h2>
                    <p class="mt-2 text-[11px] text-[#8799B3]">Individual research customers</p>
                </div>
            </div>

            {{-- Company --}}
            <div class="relative overflow-hidden rounded-[19px] border border-[#C9ECDC] bg-gradient-to-br from-white to-[#EBFBF3] p-6 shadow-[0_2px_5px_rgba(30,64,120,0.06)]">
                <div class="pointer-events-none absolute -bottom-14 -right-12 h-40 w-40 rounded-full bg-[#D8F7E8]/70"></div>

                <div class="relative flex items-start justify-between">
                    <div class="flex h-12 w-12 items-center justify-center rounded-[15px] bg-[#DDF8E9] text-[#13A36B]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <rect x="4" y="3" width="16" height="18" rx="2"/>
                            <path stroke-linecap="round" d="M9 7h1m4 0h1M9 11h1m4 0h1M9 15h1m4 0h1M10 21v-3h4v3"/>
                        </svg>
                    </div>

                    <span class="rounded-full bg-white/80 px-3 py-1 text-[10px] font-semibold text-[#119B64]">
                        {{ $companyPercentage }}%
                    </span>
                </div>

                <div class="relative mt-6">
                    <p class="text-[12px] font-semibold text-[#516581]">Company Accounts</p>
                    <h2 class="mt-2 text-[34px] font-bold tracking-[-1px] text-[#0B1B39]">
                        {{ number_format($companyAccounts) }}
                    </h2>
                    <p class="mt-2 text-[11px] text-[#8799B3]">Business research customers</p>
                </div>
            </div>

            {{-- Admin --}}
            <div class="relative overflow-hidden rounded-[19px] border border-[#F8E0B9] bg-gradient-to-br from-white to-[#FFF8E9] p-6 shadow-[0_2px_5px_rgba(30,64,120,0.06)]">
                <div class="pointer-events-none absolute -bottom-14 -right-12 h-40 w-40 rounded-full bg-[#FFF0D0]/70"></div>

                <div class="relative flex items-start justify-between">
                    <div class="flex h-12 w-12 items-center justify-center rounded-[15px] bg-[#FFF0CF] text-[#D38B0C]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7l8-4z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/>
                        </svg>
                    </div>

                    <span class="rounded-full bg-white/80 px-3 py-1 text-[10px] font-semibold text-[#C78A15]">
                        Admin Access
                    </span>
                </div>

                <div class="relative mt-6">
                    <p class="text-[12px] font-semibold text-[#516581]">Admin Accounts</p>
                    <h2 class="mt-2 text-[34px] font-bold tracking-[-1px] text-[#0B1B39]">
                        {{ number_format($adminAccounts) }}
                    </h2>
                    <p class="mt-2 text-[11px] text-[#8799B3]">Platform administrators</p>
                </div>
            </div>

        </div>

        {{-- Registrations and Distribution --}}
        <div class="mt-6 grid items-start gap-5 xl:grid-cols-[minmax(0,1fr)_310px]">

            {{-- Recent Registrations --}}
            <section class="overflow-hidden rounded-[19px] border border-[#DFE8F6] bg-white shadow-[0_2px_5px_rgba(30,64,120,0.06)]">

                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#EDF1F8] px-6 py-5">

                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#EAF2FF] text-[#3478FF]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zM22 21v-2a4 4 0 00-3-3.87"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-[15px] font-bold text-[#102243]">Recent Registrations</h2>
                            <p class="mt-1 text-[11px] text-[#8A9AB3]">Latest users joining the platform</p>
                        </div>
                    </div>

                    <a href="{{ route('admin.users') }}"
                       class="inline-flex items-center gap-2 rounded-[10px] bg-[#EDF4FF] px-4 py-2.5 text-[11px] font-semibold text-[#3478FF] transition hover:bg-[#DFEBFF]">
                        View All
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6l6 6-6 6"/>
                        </svg>
                    </a>
                </div>

                <div class="divide-y divide-[#EDF1F8]">
                    @forelse ($recentUsers as $user)

                        @php
                            $nameParts = preg_split('/\s+/', trim($user->name ?? ''));

                            $initials = collect($nameParts)
                                ->filter()
                                ->take(2)
                                ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
                                ->implode('');

                            $isAdmin = $user->role === 'admin';
                            $isCompany = !$isAdmin && $user->account_type === 'company';
                        @endphp

                        <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-4 transition hover:bg-[#F8FAFF]">

                            <div class="flex min-w-0 items-center gap-3.5">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-[13px]
                                    {{ $isAdmin ? 'bg-[#E8E8FF] text-[#6655E8]' : ($isCompany ? 'bg-[#DFF7EA] text-[#13A36B]' : 'bg-[#E5EFFF] text-[#3478FF]') }}
                                    text-[12px] font-bold">
                                    {{ $initials ?: 'U' }}
                                </div>

                                <div class="min-w-0">
                                    <p class="truncate text-[12px] font-bold text-[#102243]">
                                        {{ $user->name }}
                                    </p>
                                    <p class="mt-1 break-all text-[11px] text-[#7184A5]">
                                        {{ $user->email }}
                                    </p>
                                    <p class="mt-1.5 text-[10px] text-[#9AA9BD]">
                                        Joined {{ $user->created_at?->format('M d, Y') ?? '—' }}
                                    </p>
                                </div>
                            </div>

                            @if ($isAdmin)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#EFECFF] px-3 py-1.5 text-[10px] font-semibold text-[#6655E8]">
                                    <span class="h-1.5 w-1.5 rounded-full bg-[#6655E8]"></span>
                                    Admin
                                </span>
                            @elseif ($isCompany)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#E4F8ED] px-3 py-1.5 text-[10px] font-semibold text-[#159866]">
                                    <span class="h-1.5 w-1.5 rounded-full bg-[#159866]"></span>
                                    Company
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#EDF4FF] px-3 py-1.5 text-[10px] font-semibold text-[#3478FF]">
                                    <span class="h-1.5 w-1.5 rounded-full bg-[#3478FF]"></span>
                                    Individual
                                </span>
                            @endif

                        </div>

                    @empty

                        <div class="flex flex-col items-center px-6 py-14 text-center">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#EDF4FF] text-[#3478FF]">
                                <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8z"/>
                                </svg>
                            </div>
                            <p class="mt-4 text-[13px] font-bold text-[#102243]">No registered users yet</p>
                            <p class="mt-1.5 text-[11px] text-[#8A9AB3]">New registrations will appear here.</p>
                        </div>

                    @endforelse
                </div>

            </section>

            {{-- Account Distribution --}}
            <aside class="overflow-hidden rounded-[19px] border border-[#DFE8F6] bg-white shadow-[0_2px_5px_rgba(30,64,120,0.06)]">

                <div class="flex items-center gap-3 border-b border-[#EDF1F8] px-5 py-5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#EEEAFE] text-[#7557E9]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v9h9A9 9 0 0012 3zM9 4a9 9 0 1011 11H9V4z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-[14px] font-bold text-[#102243]">Account Distribution</h2>
                        <p class="mt-1 text-[10px] text-[#8A9AB3]">User breakdown by account type</p>
                    </div>
                </div>

                <div class="space-y-4 p-5">

                    <div class="flex items-center justify-between rounded-[13px] bg-[#F2F6FF] px-4 py-3.5">
                        <div class="flex items-center gap-2.5">
                            <span class="h-2.5 w-2.5 rounded-full bg-[#397CF6]"></span>
                            <span class="text-[12px] font-semibold text-[#516581]">Individual</span>
                        </div>
                        <span class="text-[13px] font-bold text-[#102243]">{{ number_format($individualAccounts) }}</span>
                    </div>

                    <div class="flex items-center justify-between rounded-[13px] bg-[#EFFBF4] px-4 py-3.5">
                        <div class="flex items-center gap-2.5">
                            <span class="h-2.5 w-2.5 rounded-full bg-[#16A36A]"></span>
                            <span class="text-[12px] font-semibold text-[#516581]">Company</span>
                        </div>
                        <span class="text-[13px] font-bold text-[#102243]">{{ number_format($companyAccounts) }}</span>
                    </div>

                    <div class="flex items-center justify-between rounded-[13px] bg-[#F4F0FF] px-4 py-3.5">
                        <div class="flex items-center gap-2.5">
                            <span class="h-2.5 w-2.5 rounded-full bg-[#7957E9]"></span>
                            <span class="text-[12px] font-semibold text-[#516581]">Admins</span>
                        </div>
                        <span class="text-[13px] font-bold text-[#102243]">{{ number_format($adminAccounts) }}</span>
                    </div>

                    <div class="border-t border-[#EDF1F8] pt-5">

                        <div class="flex justify-between text-[11px]">
                            <span class="font-semibold text-[#516581]">Individual Share</span>
                            <span class="font-bold text-[#3478FF]">{{ $individualPercentage }}%</span>
                        </div>

                        <div class="mt-2.5 h-2.5 overflow-hidden rounded-full bg-[#E9EFF8]">
                            <div class="h-full rounded-full bg-[#397CF6] transition-all duration-500"
                                 style="width: {{ max(0, min(100, (float) $individualPercentage)) }}%"></div>
                        </div>

                        <div class="mt-6 flex justify-between text-[11px]">
                            <span class="font-semibold text-[#516581]">Company Share</span>
                            <span class="font-bold text-[#16A36A]">{{ $companyPercentage }}%</span>
                        </div>

                        <div class="mt-2.5 h-2.5 overflow-hidden rounded-full bg-[#E9EFF8]">
                            <div class="h-full rounded-full bg-[#16A36A] transition-all duration-500"
                                 style="width: {{ max(0, min(100, (float) $companyPercentage)) }}%"></div>
                        </div>

                    </div>

                    <a href="{{ route('admin.users') }}"
                       class="flex h-11 w-full items-center justify-center gap-2 rounded-[11px] bg-[#397CF6] text-[11px] font-bold text-white shadow-[0_3px_8px_rgba(57,124,246,0.2)] transition hover:bg-[#2869E3]">
                        Manage Users
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6l6 6-6 6"/>
                        </svg>
                    </a>

                </div>

            </aside>

        </div>

        {{-- Research Requests Overview --}}
        <div class="mt-10">

            <div class="flex flex-wrap items-center justify-between gap-4">

                <div>
                    <div class="mb-2 inline-flex items-center gap-2 rounded-full bg-[#EAF2FF] px-3 py-1.5 text-[10px] font-bold text-[#3478FF]">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#3478FF]"></span>
                        Research Activity
                    </div>

                    <h2 class="text-[22px] font-bold tracking-[-0.5px] text-[#0B1B39]">
                        Research Requests Overview
                    </h2>

                    <p class="mt-2 text-[12px] text-[#7184A5]">
                        Track research studies and their progress across the platform.
                    </p>
                </div>

                <a href="{{ route('admin.research-requests.index') }}"
                   class="inline-flex items-center gap-2 rounded-[11px] border border-[#CFDFFF] bg-white px-4 py-3 text-[11px] font-bold text-[#3478FF] shadow-sm transition hover:bg-[#EDF4FF]">
                    Manage Research Requests
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6l6 6-6 6"/>
                    </svg>
                </a>

            </div>

            {{-- Research Statistics --}}
            @php
                $researchStats = [
                    [
                        'label' => 'Total Requests',
                        'value' => $totalResearchRequests,
                        'description' => 'All research studies',
                        'bg' => 'bg-gradient-to-br from-white to-[#EDF6FF]',
                        'border' => 'border-[#C8DEFF]',
                        'iconBg' => 'bg-[#E2EFFF]',
                        'color' => 'text-[#3478FF]',
                        'circle' => 'bg-[#DCEEFF]/70',
                        'icon' => 'total',
                    ],
                    [
                        'label' => 'Submitted',
                        'value' => $submittedResearchRequests,
                        'description' => 'Awaiting approval',
                        'bg' => 'bg-gradient-to-br from-white to-[#F0F1FF]',
                        'border' => 'border-[#D7DCFF]',
                        'iconBg' => 'bg-[#E8E9FF]',
                        'color' => 'text-[#6958EA]',
                        'circle' => 'bg-[#E5E8FF]/70',
                        'icon' => 'submitted',
                    ],
                    [
                        'label' => 'In Progress',
                        'value' => $inProgressResearchRequests,
                        'description' => 'Active studies',
                        'bg' => 'bg-gradient-to-br from-white to-[#EBFBF3]',
                        'border' => 'border-[#C9ECDC]',
                        'iconBg' => 'bg-[#DDF8E9]',
                        'color' => 'text-[#13A36B]',
                        'circle' => 'bg-[#D8F7E8]/70',
                        'icon' => 'progress',
                    ],
                    [
                        'label' => 'Review',
                        'value' => $reviewResearchRequests,
                        'description' => 'Awaiting completion',
                        'bg' => 'bg-gradient-to-br from-white to-[#FFF8E9]',
                        'border' => 'border-[#F8E0B9]',
                        'iconBg' => 'bg-[#FFF0CF]',
                        'color' => 'text-[#D38B0C]',
                        'circle' => 'bg-[#FFF0D0]/70',
                        'icon' => 'review',
                    ],
                    [
                        'label' => 'Completed',
                        'value' => $completedResearchRequests,
                        'description' => 'Finished studies',
                        'bg' => 'bg-gradient-to-br from-white to-[#EDFCF7]',
                        'border' => 'border-[#BDEBD7]',
                        'iconBg' => 'bg-[#D7F8E9]',
                        'color' => 'text-[#0B9B69]',
                        'circle' => 'bg-[#D7F8E9]/70',
                        'icon' => 'completed',
                    ],
                    [
                        'label' => 'Rejected',
                        'value' => $rejectedResearchRequests,
                        'description' => 'Declined studies',
                        'bg' => 'bg-gradient-to-br from-white to-[#FFF0F1]',
                        'border' => 'border-[#F5D3D7]',
                        'iconBg' => 'bg-[#FFE1E4]',
                        'color' => 'text-[#E24E61]',
                        'circle' => 'bg-[#FFE1E4]/70',
                        'icon' => 'rejected',
                    ],
                ];
            @endphp

            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-6">

                @foreach ($researchStats as $stat)
                    <div class="relative overflow-hidden rounded-[18px] border {{ $stat['border'] }} {{ $stat['bg'] }} p-5 shadow-[0_2px_5px_rgba(30,64,120,0.05)]">

                        <div class="pointer-events-none absolute -bottom-12 -right-12 h-32 w-32 rounded-full {{ $stat['circle'] }}"></div>

                        <div class="relative flex h-11 w-11 items-center justify-center rounded-[13px] {{ $stat['iconBg'] }} {{ $stat['color'] }}">

                            @switch($stat['icon'])

                                @case('total')
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <rect x="4" y="4" width="16" height="16" rx="3"/>
                                        <path stroke-linecap="round" d="M8 9h8M8 13h8M8 17h5"/>
                                    </svg>
                                    @break

                                @case('submitted')
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/>
                                    </svg>
                                    @break

                                @case('progress')
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="9"/>
                                        <path stroke-linecap="round" d="M12 7v5l3 2"/>
                                    </svg>
                                    @break

                                @case('review')
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 3h8l4 4v14H4V3h4zM8 12h8M8 16h5"/>
                                    </svg>
                                    @break

                                @case('completed')
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="9"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12l3 3 5-6"/>
                                    </svg>
                                    @break

                                @case('rejected')
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="9"/>
                                        <path stroke-linecap="round" d="M9 9l6 6m0-6l-6 6"/>
                                    </svg>
                                    @break

                            @endswitch

                        </div>

                        <div class="relative mt-5">
                            <p class="text-[11px] font-semibold text-[#516581]">{{ $stat['label'] }}</p>
                            <h3 class="mt-2 text-[29px] font-bold tracking-[-1px] {{ $stat['color'] }}">
                                {{ number_format($stat['value']) }}
                            </h3>
                            <p class="mt-2 text-[10px] text-[#8799B3]">{{ $stat['description'] }}</p>
                        </div>

                    </div>
                @endforeach

            </div>

            {{-- Recent Research Requests --}}
            <section class="mt-6 overflow-hidden rounded-[19px] border border-[#DFE8F6] bg-white shadow-[0_2px_5px_rgba(30,64,120,0.06)]">

                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#EDF1F8] px-6 py-5">

                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#EAF2FF] text-[#3478FF]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <rect x="4" y="4" width="16" height="16" rx="3"/>
                                <path stroke-linecap="round" d="M8 9h8M8 13h8M8 17h5"/>
                            </svg>
                        </div>

                        <div>
                            <h3 class="text-[15px] font-bold text-[#102243]">Recent Research Requests</h3>
                            <p class="mt-1 text-[11px] text-[#8A9AB3]">The latest 5 studies submitted to the platform</p>
                        </div>
                    </div>

                    <a href="{{ route('admin.research-requests.index') }}"
                       class="inline-flex items-center gap-2 rounded-[10px] bg-[#EDF4FF] px-4 py-2.5 text-[11px] font-semibold text-[#3478FF] transition hover:bg-[#DFEBFF]">
                        View All
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6l6 6-6 6"/>
                        </svg>
                    </a>
                </div>

                <div class="overflow-x-auto">

                    <table class="w-full min-w-[900px] text-left">

                        <thead class="border-b border-[#E3EAF5] bg-[#F5F8FE]">
                            <tr class="text-[10px] font-bold uppercase tracking-[0.04em] text-[#7184A5]">
                                <th class="px-5 py-5">Request</th>
                                <th class="px-5 py-5">User</th>
                                <th class="px-5 py-5">Research Goal</th>
                                <th class="px-5 py-5">Respondents</th>
                                <th class="px-5 py-5">Status</th>
                                <th class="px-5 py-5">Submitted</th>
                                <th class="px-5 py-5">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-[#EDF1F8]">

                            @forelse ($recentResearchRequests as $researchRequest)

                                @php
                                    $status = $researchRequest->status ?? 'submitted';

                                    $statusLabel = match ($status) {
                                        'submitted' => 'Submitted',
                                        'in_progress' => 'In Progress',
                                        'review' => 'Review',
                                        'completed' => 'Completed',
                                        'rejected' => 'Rejected',
                                        default => ucfirst(str_replace('_', ' ', $status)),
                                    };

                                    $statusClass = match ($status) {
                                        'submitted' => 'bg-[#E8F1FF] text-[#3478FF]',
                                        'in_progress' => 'bg-[#E4F8ED] text-[#159866]',
                                        'review' => 'bg-[#FFF3D9] text-[#C78A15]',
                                        'completed' => 'bg-[#DFF8EC] text-[#0B9B69]',
                                        'rejected' => 'bg-[#FFE8EB] text-[#D9475A]',
                                        default => 'bg-[#F1F4F8] text-[#7184A5]',
                                    };

                                    $dotClass = match ($status) {
                                        'submitted' => 'bg-[#3478FF]',
                                        'in_progress' => 'bg-[#159866]',
                                        'review' => 'bg-[#C78A15]',
                                        'completed' => 'bg-[#0B9B69]',
                                        'rejected' => 'bg-[#D9475A]',
                                        default => 'bg-[#7184A5]',
                                    };
                                @endphp

                                <tr class="transition hover:bg-[#F8FAFF]">

                                    <td class="px-5 py-5">
                                        <p class="whitespace-nowrap font-mono text-[12px] font-bold text-[#102243]">
                                            Study {{ $researchRequest->study_number }}
                                        </p>
                                        <p class="mt-1.5 whitespace-nowrap font-mono text-[10px] text-[#8A9AB3]">
                                            {{ $researchRequest->public_request_code }}
                                        </p>
                                    </td>

                                    <td class="px-5 py-5">
                                        <p class="text-[12px] font-semibold text-[#102243]">
                                            {{ $researchRequest->user?->name ?? $researchRequest->full_name ?? 'Unknown user' }}
                                        </p>
                                        <p class="mt-1.5 text-[10px] text-[#8A9AB3]">
                                            {{ $researchRequest->user?->email ?? $researchRequest->email }}
                                        </p>
                                    </td>

                                    <td class="px-5 py-5">
                                        <span class="text-[12px] font-medium text-[#516581]">
                                            {{ $researchRequest->goal }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-5">
                                        <span class="inline-flex rounded-[9px] bg-[#F0F4FB] px-3 py-2 text-[11px] font-bold text-[#516581]">
                                            {{ number_format((int) $researchRequest->sample_size) }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-5">
                                        <span class="inline-flex items-center gap-2 whitespace-nowrap rounded-full px-3 py-2 text-[10px] font-semibold {{ $statusClass }}">
                                            <span class="h-1.5 w-1.5 rounded-full {{ $dotClass }}"></span>
                                            {{ $statusLabel }}
                                        </span>
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-5 text-[11px] font-medium text-[#7184A5]">
                                        {{ $researchRequest->created_at?->format('M d, Y') ?? '—' }}
                                    </td>

                                    <td class="px-5 py-5">
                                        <a href="{{ route('admin.research-requests.show', $researchRequest) }}"
                                           class="inline-flex items-center gap-2 whitespace-nowrap rounded-[10px] border border-[#BED4FF] bg-[#F0F5FF] px-3.5 py-2.5 text-[11px] font-bold text-[#3478FF] transition hover:bg-[#E1EDFF]">
                                            View Details
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6l6 6-6 6"/>
                                            </svg>
                                        </a>
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="7" class="px-6 py-16 text-center">
                                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#EDF4FF] text-[#3478FF]">
                                            <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <rect x="4" y="4" width="16" height="16" rx="3"/>
                                                <path stroke-linecap="round" d="M8 9h8M8 13h8"/>
                                            </svg>
                                        </div>
                                        <p class="mt-4 text-[13px] font-bold text-[#102243]">No research requests yet</p>
                                        <p class="mt-2 text-[11px] text-[#8A9AB3]">
                                            New research studies will appear here.
                                        </p>
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>
                    </table>

                </div>

            </section>

        </div>

        {{-- Footer --}}
        <div class="mt-8 flex flex-wrap items-center justify-between gap-3 border-t border-[#DCE7F5] pt-5">
            <p class="text-[10px] text-[#91A0B6]">
                Impala Research · Administration Workspace
            </p>
            <p class="text-[10px] text-[#91A0B6]">
                Platform overview
            </p>
        </div>

    </main>

</div>
@endsection
