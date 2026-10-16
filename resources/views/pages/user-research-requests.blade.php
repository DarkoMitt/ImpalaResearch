
@extends('layouts.app')

@section('content')
@php
    $selectedStatus = $selectedStatus ?? '';
    $search = $search ?? '';

    /*
    |--------------------------------------------------------------------------
    | Statistics
    |--------------------------------------------------------------------------
    | Total uses the existing controller variable.
    | Status counts are calculated for the signed-in user's studies.
    */

    $userStudiesQuery = \App\Models\ResearchRequest::query()
        ->where('user_id', auth()->id());

    $submittedCount = (clone $userStudiesQuery)
        ->where('status', 'submitted')
        ->count();

    $inProgressCount = (clone $userStudiesQuery)
        ->whereIn('status', ['in_progress', 'in_field'])
        ->count();

    $reviewCount = (clone $userStudiesQuery)
        ->whereIn('status', ['review', 'under_review'])
        ->count();

    $completedCount = (clone $userStudiesQuery)
        ->where('status', 'completed')
        ->count();

    $rejectedCount = (clone $userStudiesQuery)
        ->where('status', 'rejected')
        ->count();

    $hasFilters = filled($search) || filled($selectedStatus);

    $statusOptions = [
        'submitted' => 'Submitted',
        'in_progress' => 'In Progress',
        'review' => 'Review',
        'completed' => 'Completed',
        'rejected' => 'Rejected',
    ];
@endphp

<div class="flex min-h-screen w-full bg-white">

    @include('partials.user-sidebar')

    <main class="min-w-0 flex-1 bg-gradient-to-br from-[#DCEAFF] via-[#F5F9FF] to-white px-5 py-8 lg:px-9 lg:py-10">

        {{-- Page Header --}}
        <div class="flex flex-wrap items-start justify-between gap-5">

            <div>
                <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-[#CFE0FF] bg-white/80 px-3 py-1.5 text-[10px] font-bold text-[#3478FF]">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#3478FF]"></span>
                    Research Workspace
                </div>

                <h1 class="text-[28px] font-bold tracking-[-1px] text-[#0B1B39] lg:text-[32px]">
                    My Research Requests
                </h1>

                <p class="mt-2 text-[12px] text-[#7184A5]">
                    Manage, monitor and track all your research studies in one place.
                </p>
            </div>

            <a
                href="{{ route('home') }}#start-study"
                class="inline-flex h-11 shrink-0 items-center justify-center gap-2 rounded-[11px] bg-[#397CF6] px-5 text-[12px] font-bold text-white shadow-[0_4px_12px_rgba(57,124,246,0.22)] transition hover:bg-[#2869E3]"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" d="M12 5v14M5 12h14"/>
                </svg>
                Create New Study
            </a>

        </div>

        {{-- Statistics --}}
        <div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-6">

            {{-- Total --}}
            <div class="relative overflow-hidden rounded-[17px] border border-[#C9DEFF] bg-gradient-to-br from-white to-[#EDF5FF] p-5 shadow-[0_2px_5px_rgba(30,64,120,0.06)]">
                <div class="pointer-events-none absolute -bottom-8 -right-8 h-24 w-24 rounded-full bg-[#DCEBFF]/60"></div>

                <div class="relative">
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-[10px] font-bold uppercase leading-[1.5] tracking-[0.03em] text-[#7184A5]">
                            Total Requests
                        </p>

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-[11px] bg-[#E0EDFF] text-[#3478FF]">
                            <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <rect x="4" y="4" width="16" height="16" rx="3"/>
                                <path stroke-linecap="round" d="M8 9h8M8 13h8M8 17h5"/>
                            </svg>
                        </div>
                    </div>

                    <p class="mt-5 text-[27px] font-bold tracking-[-0.7px] text-[#102243]">
                        {{ number_format($totalStudies) }}
                    </p>

                    <p class="mt-1.5 text-[10px] text-[#8A9AB3]">
                        All research studies
                    </p>
                </div>
            </div>

            {{-- Submitted --}}
            <div class="relative overflow-hidden rounded-[17px] border border-[#D5E2FF] bg-gradient-to-br from-white to-[#F1F5FF] p-5 shadow-[0_2px_5px_rgba(30,64,120,0.06)]">
                <div class="pointer-events-none absolute -bottom-8 -right-8 h-24 w-24 rounded-full bg-[#E6EDFF]/60"></div>

                <div class="relative">
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-[10px] font-bold uppercase leading-[1.5] tracking-[0.03em] text-[#7184A5]">
                            Submitted
                        </p>

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-[11px] bg-[#E6EEFF] text-[#5D82E9]">
                            <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 12l7 7L21 5"/>
                                <path stroke-linecap="round" d="M4 5h9"/>
                            </svg>
                        </div>
                    </div>

                    <p class="mt-5 text-[27px] font-bold tracking-[-0.7px] text-[#102243]">
                        {{ number_format($submittedCount) }}
                    </p>

                    <p class="mt-1.5 text-[10px] text-[#8A9AB3]">
                        Awaiting processing
                    </p>
                </div>
            </div>

            {{-- In Progress --}}
            <div class="relative overflow-hidden rounded-[17px] border border-[#C8EBDD] bg-gradient-to-br from-white to-[#EFFBF5] p-5 shadow-[0_2px_5px_rgba(30,64,120,0.06)]">
                <div class="pointer-events-none absolute -bottom-8 -right-8 h-24 w-24 rounded-full bg-[#DAF6E8]/60"></div>

                <div class="relative">
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-[10px] font-bold uppercase leading-[1.5] tracking-[0.03em] text-[#7184A5]">
                            In Progress
                        </p>

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-[11px] bg-[#DDF7EA] text-[#159866]">
                            <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="18" height="18" rx="3"/>
                                <path stroke-linecap="round" d="M8 16v-4M12 16V8M16 16v-6"/>
                            </svg>
                        </div>
                    </div>

                    <p class="mt-5 text-[27px] font-bold tracking-[-0.7px] text-[#102243]">
                        {{ number_format($inProgressCount) }}
                    </p>

                    <p class="mt-1.5 text-[10px] text-[#8A9AB3]">
                        Currently in field
                    </p>
                </div>
            </div>

            {{-- Review --}}
            <div class="relative overflow-hidden rounded-[17px] border border-[#F1DFC2] bg-gradient-to-br from-white to-[#FFF8ED] p-5 shadow-[0_2px_5px_rgba(30,64,120,0.06)]">
                <div class="pointer-events-none absolute -bottom-8 -right-8 h-24 w-24 rounded-full bg-[#FFF0D7]/60"></div>

                <div class="relative">
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-[10px] font-bold uppercase leading-[1.5] tracking-[0.03em] text-[#7184A5]">
                            In Review
                        </p>

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-[11px] bg-[#FFF0D7] text-[#D79727]">
                            <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="7"/>
                                <path stroke-linecap="round" d="M16 16l5 5M8 11h6M11 8v6"/>
                            </svg>
                        </div>
                    </div>

                    <p class="mt-5 text-[27px] font-bold tracking-[-0.7px] text-[#102243]">
                        {{ number_format($reviewCount) }}
                    </p>

                    <p class="mt-1.5 text-[10px] text-[#8A9AB3]">
                        Under evaluation
                    </p>
                </div>
            </div>

            {{-- Completed --}}
            <div class="relative overflow-hidden rounded-[17px] border border-[#C4EBD9] bg-gradient-to-br from-white to-[#EBFCF4] p-5 shadow-[0_2px_5px_rgba(30,64,120,0.06)]">
                <div class="pointer-events-none absolute -bottom-8 -right-8 h-24 w-24 rounded-full bg-[#D8F7E6]/60"></div>

                <div class="relative">
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-[10px] font-bold uppercase leading-[1.5] tracking-[0.03em] text-[#7184A5]">
                            Completed
                        </p>

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-[11px] bg-[#D8F8E9] text-[#0D9D6D]">
                            <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="9"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12l3 3 5-6"/>
                            </svg>
                        </div>
                    </div>

                    <p class="mt-5 text-[27px] font-bold tracking-[-0.7px] text-[#102243]">
                        {{ number_format($completedCount) }}
                    </p>

                    <p class="mt-1.5 text-[10px] text-[#8A9AB3]">
                        Finished studies
                    </p>
                </div>
            </div>

            {{-- Rejected --}}
            <div class="relative overflow-hidden rounded-[17px] border border-[#F5D4DB] bg-gradient-to-br from-white to-[#FFF2F4] p-5 shadow-[0_2px_5px_rgba(30,64,120,0.06)]">
                <div class="pointer-events-none absolute -bottom-8 -right-8 h-24 w-24 rounded-full bg-[#FFE5E9]/60"></div>

                <div class="relative">
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-[10px] font-bold uppercase leading-[1.5] tracking-[0.03em] text-[#7184A5]">
                            Rejected
                        </p>

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-[11px] bg-[#FFE5E9] text-[#D9475A]">
                            <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="9"/>
                                <path stroke-linecap="round" d="M9 9l6 6M15 9l-6 6"/>
                            </svg>
                        </div>
                    </div>

                    <p class="mt-5 text-[27px] font-bold tracking-[-0.7px] text-[#102243]">
                        {{ number_format($rejectedCount) }}
                    </p>

                    <p class="mt-1.5 text-[10px] text-[#8A9AB3]">
                        Not approved
                    </p>
                </div>
            </div>

        </div>

        {{-- Search & Filters Panel --}}
        <section class="mt-6 overflow-hidden rounded-[19px] border border-[#DFE8F6] bg-white shadow-[0_2px_5px_rgba(30,64,120,0.06)]">

            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#EDF1F8] px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-[12px] bg-[#EAF2FF] text-[#3478FF]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="7"/>
                            <path stroke-linecap="round" d="M16 16l5 5"/>
                        </svg>
                    </div>

                    <div>
                        <h2 class="text-[14px] font-bold text-[#102243]">
                            Search & Filter
                        </h2>

                        <p class="mt-1 text-[11px] text-[#8A9AB3]">
                            Find the research request you're looking for
                        </p>
                    </div>
                </div>

                @if ($hasFilters)
                    <span class="inline-flex items-center gap-2 rounded-full bg-[#EAF2FF] px-3 py-1.5 text-[10px] font-bold text-[#3478FF]">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#3478FF]"></span>
                        Filters Applied
                    </span>
                @endif
            </div>

            <form
                method="GET"
                action="{{ route('my-studies.index') }}"
                class="flex flex-wrap items-end gap-4 p-6"
            >
                {{-- Search --}}
                <div class="min-w-[220px] flex-1">
                    <label for="search" class="mb-2 block text-[11px] font-bold text-[#516581]">
                        Search Studies
                    </label>

                    <div class="relative">
                        <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-[#91A0B6]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="7"/>
                            <path stroke-linecap="round" d="M16 16l5 5"/>
                        </svg>

                        <input
                            id="search"
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search by Study #, Request ID, goal or country..."
                            class="h-11 w-full rounded-[11px] border border-[#DCE5F3] bg-[#FAFCFF] pl-10 pr-4 text-[12px] text-[#102243] outline-none transition placeholder:text-[#A3B0C2] focus:border-[#3478FF] focus:bg-white focus:ring-2 focus:ring-[#3478FF]/10"
                        >
                    </div>
                </div>

                {{-- Status --}}
                <div class="w-full sm:w-[190px]">
                    <label for="status" class="mb-2 block text-[11px] font-bold text-[#516581]">
                        Status
                    </label>

                    <select
                        id="status"
                        name="status"
                        class="h-11 w-full cursor-pointer rounded-[11px] border border-[#DCE5F3] bg-[#FAFCFF] px-3.5 text-[12px] text-[#516581] outline-none transition focus:border-[#3478FF] focus:bg-white focus:ring-2 focus:ring-[#3478FF]/10"
                    >
                        <option value="">All Statuses</option>

                        @foreach ($statusOptions as $value => $label)
                            <option value="{{ $value }}" @selected($selectedStatus === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button
                    type="submit"
                    class="inline-flex h-11 cursor-pointer items-center justify-center gap-2 rounded-[11px] bg-[#397CF6] px-5 text-[11px] font-bold text-white transition hover:bg-[#2869E3]"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="7"/>
                        <path stroke-linecap="round" d="M16 16l5 5"/>
                    </svg>
                    Search
                </button>

                <a
                    href="{{ route('my-studies.index') }}"
                    class="inline-flex h-11 items-center justify-center gap-2 rounded-[11px] border border-[#DCE5F3] bg-white px-5 text-[11px] font-bold text-[#7184A5] transition hover:bg-[#F5F8FF] hover:text-[#3478FF]"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" d="M3 12a9 9 0 111.7 5.3M3 17v-5h5"/>
                    </svg>
                    Reset
                </a>

            </form>
        </section>

        {{-- Research Requests Table --}}
        <section class="mt-6 overflow-hidden rounded-[19px] border border-[#DFE8F6] bg-white shadow-[0_2px_5px_rgba(30,64,120,0.06)]">

            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#EDF1F8] px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-[13px] bg-[#EDEAFF] text-[#7557E9]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <rect x="4" y="4" width="16" height="16" rx="3"/>
                            <path stroke-linecap="round" d="M8 9h8M8 13h8M8 17h5"/>
                        </svg>
                    </div>

                    <div>
                        <h2 class="text-[14px] font-bold text-[#102243]">
                            All Research Requests
                        </h2>

                        <p class="mt-1 text-[11px] text-[#8A9AB3]">
                            View the details and progress of your studies
                        </p>
                    </div>
                </div>

                <span class="rounded-full bg-[#F1F5FF] px-3 py-1.5 text-[10px] font-bold text-[#637DA5]">
                    {{ number_format($studies->total()) }} {{ $studies->total() === 1 ? 'result' : 'results' }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[850px] text-left">

                    <thead class="border-b border-[#E8EEF8] bg-[#F7FAFF]">
                        <tr class="text-[10px] font-bold uppercase tracking-[0.05em] text-[#8A9AB3]">
                            <th class="px-6 py-4 font-bold">Request ID</th>
                            <th class="px-6 py-4 font-bold">Research Goal</th>
                            <th class="px-6 py-4 font-bold">Country</th>
                            <th class="px-6 py-4 font-bold">Respondents</th>
                            <th class="px-6 py-4 font-bold">Status</th>
                            <th class="px-6 py-4 text-right font-bold">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#EDF1F8]">

                        @forelse ($studies as $study)
                            @php
                                $status = strtolower($study->status ?? 'submitted');

                                $statusLabel = match ($status) {
                                    'submitted' => 'Submitted',
                                    'in_progress', 'in_field' => 'In Field',
                                    'review', 'under_review' => 'In Review',
                                    'completed' => 'Completed',
                                    'rejected' => 'Rejected',
                                    default => ucwords(str_replace('_', ' ', $status)),
                                };

                                $statusClass = match ($status) {
                                    'submitted' => 'bg-[#E8F1FF] text-[#3478FF]',
                                    'in_progress', 'in_field' => 'bg-[#E2F8EE] text-[#159866]',
                                    'review', 'under_review' => 'bg-[#FFF3DC] text-[#C68B16]',
                                    'completed' => 'bg-[#DFF8EC] text-[#0B9B69]',
                                    'rejected' => 'bg-[#FFE8EB] text-[#D9475A]',
                                    default => 'bg-[#F0F2F6] text-[#7B879B]',
                                };

                                $statusDot = match ($status) {
                                    'submitted' => 'bg-[#3478FF]',
                                    'in_progress', 'in_field' => 'bg-[#159866]',
                                    'review', 'under_review' => 'bg-[#C68B16]',
                                    'completed' => 'bg-[#0B9B69]',
                                    'rejected' => 'bg-[#D9475A]',
                                    default => 'bg-[#7B879B]',
                                };
                            @endphp

                            <tr class="group transition hover:bg-[#F8FBFF]">

                                {{-- Request ID --}}
                                <td class="px-6 py-5 align-middle">
                                    <p class="whitespace-nowrap font-mono text-[11px] font-bold text-[#102243]">
                                        Study {{ $study->study_number }}
                                    </p>

                                    <p class="mt-1.5 whitespace-nowrap font-mono text-[10px] text-[#91A0B6]">
                                        {{ $study->public_request_code }}
                                    </p>
                                </td>

                                {{-- Research Goal --}}
                                <td class="max-w-[280px] px-6 py-5 align-middle">
                                    <a
                                        href="{{ route('my-studies.show', $study) }}"
                                        class="block break-words text-[12px] font-bold text-[#102243] transition group-hover:text-[#3478FF]"
                                    >
                                        {{ $study->goal }}
                                    </a>

                                    <p class="mt-1.5 text-[10px] text-[#91A0B6]">
                                        {{ $study->created_at?->format('M d, Y') ?? '—' }}
                                    </p>
                                </td>

                                {{-- Country --}}
                                <td class="px-6 py-5 align-middle">
                                    <span class="inline-flex items-center gap-2 text-[11px] font-medium text-[#516581]">
                                        <svg class="h-3.5 w-3.5 shrink-0 text-[#91A0B6]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="9"/>
                                            <path stroke-linecap="round" d="M3 12h18M12 3a15 15 0 010 18M12 3a15 15 0 000 18"/>
                                        </svg>
                                        {{ $study->country ?: '—' }}
                                    </span>
                                </td>

                                {{-- Respondents --}}
                                <td class="px-6 py-5 align-middle">
                                    <span class="text-[12px] font-bold text-[#102243]">
                                        {{ number_format((int) $study->sample_size) }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-5 align-middle">
                                    <span class="inline-flex items-center gap-2 whitespace-nowrap rounded-full px-3 py-2 text-[10px] font-bold {{ $statusClass }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>
                                        {{ $statusLabel }}
                                    </span>
                                </td>

                                {{-- Action --}}
                                <td class="px-6 py-5 text-right align-middle">
                                    <a
                                        href="{{ route('my-studies.show', $study) }}"
                                        class="inline-flex h-9 items-center justify-center gap-2 whitespace-nowrap rounded-[9px] border border-[#D7E6FF] bg-[#EFF5FF] px-3.5 text-[10px] font-bold text-[#3478FF] transition hover:border-[#3478FF] hover:bg-[#E5EFFF]"
                                    >
                                        View Details

                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6l6 6-6 6"/>
                                        </svg>
                                    </a>
                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">

                                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-[18px] bg-[#EAF2FF] text-[#3478FF]">
                                        <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <rect x="4" y="4" width="16" height="16" rx="3"/>
                                            <path stroke-linecap="round" d="M8 9h8M8 13h8M8 17h5"/>
                                        </svg>
                                    </div>

                                    <h3 class="mt-5 text-[15px] font-bold text-[#102243]">
                                        {{ $hasFilters ? 'No matching research requests' : 'No research requests yet' }}
                                    </h3>

                                    <p class="mx-auto mt-2 max-w-[340px] text-[11px] leading-[1.8] text-[#7184A5]">
                                        {{ $hasFilters
                                            ? 'Try adjusting your search terms or filters to find the studies you are looking for.'
                                            : 'Start your first research study to explore customer behavior and gain valuable insights.' }}
                                    </p>

                                    <a
                                        href="{{ $hasFilters ? route('my-studies.index') : route('home') . '#start-study' }}"
                                        class="mt-5 inline-flex h-10 items-center justify-center gap-2 rounded-[10px] bg-[#397CF6] px-5 text-[11px] font-bold text-white transition hover:bg-[#2869E3]"
                                    >
                                        {{ $hasFilters ? 'Clear Filters' : 'Create New Study' }}
                                    </a>

                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="flex flex-wrap items-center justify-between gap-4 border-t border-[#EDF1F8] bg-[#FAFCFF] px-6 py-5">

                <p class="text-[11px] text-[#8294B0]">
                    Showing
                    <span class="font-bold text-[#102243]">{{ $studies->firstItem() ?? 0 }}</span>
                    –
                    <span class="font-bold text-[#102243]">{{ $studies->lastItem() ?? 0 }}</span>
                    of
                    <span class="font-bold text-[#102243]">{{ number_format($studies->total()) }}</span>
                    results
                </p>

                <div class="flex items-center gap-2">

                    @if ($studies->onFirstPage())
                        <span class="inline-flex h-9 items-center justify-center rounded-[9px] border border-[#E2E8F2] bg-[#F5F7FB] px-4 text-[10px] font-semibold text-[#B1BDD0]">
                            Previous
                        </span>
                    @else
                        <a
                            href="{{ $studies->previousPageUrl() }}"
                            class="inline-flex h-9 items-center justify-center rounded-[9px] border border-[#DCE5F3] bg-white px-4 text-[10px] font-bold text-[#516581] transition hover:border-[#3478FF] hover:text-[#3478FF]"
                        >
                            Previous
                        </a>
                    @endif

                    <span class="min-w-[65px] text-center text-[10px] font-semibold text-[#8294B0]">
                        {{ $studies->currentPage() }} / {{ $studies->lastPage() }}
                    </span>

                    @if ($studies->hasMorePages())
                        <a
                            href="{{ $studies->nextPageUrl() }}"
                            class="inline-flex h-9 items-center justify-center rounded-[9px] border border-[#DCE5F3] bg-white px-4 text-[10px] font-bold text-[#516581] transition hover:border-[#3478FF] hover:text-[#3478FF]"
                        >
                            Next
                        </a>
                    @else
                        <span class="inline-flex h-9 items-center justify-center rounded-[9px] border border-[#E2E8F2] bg-[#F5F7FB] px-4 text-[10px] font-semibold text-[#B1BDD0]">
                            Next
                        </span>
                    @endif

                </div>
            </div>

        </section>

        {{-- Footer --}}
        <div class="mt-8 flex flex-wrap items-center justify-between gap-3 border-t border-[#DCE7F5] pt-5">
            <p class="text-[10px] text-[#91A0B6]">
                Impala Research · Your Research Workspace
            </p>

            <p class="text-[10px] text-[#91A0B6]">
                Research Requests
            </p>
        </div>

    </main>
</div>
@endsection
