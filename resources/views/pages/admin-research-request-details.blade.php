
@extends('layouts.app')

@section('content')
@php
    $studyNumber = $researchRequest->study_number;
    $publicRequestCode = $researchRequest->public_request_code;
    $status = $researchRequest->status ?? 'submitted';

    $statusLabels = [
        'submitted' => 'Submitted',
        'in_progress' => 'In Progress',
        'review' => 'Review',
        'completed' => 'Completed',
        'rejected' => 'Rejected',
    ];

    $statusLabel = $statusLabels[$status]
        ?? ucfirst(str_replace('_', ' ', $status));

    $statusClasses = [
        'submitted' => 'bg-[#E8F1FF] text-[#3478FF]',
        'in_progress' => 'bg-[#E4F8ED] text-[#159866]',
        'review' => 'bg-[#FFF3D9] text-[#C78A15]',
        'completed' => 'bg-[#DFF8EC] text-[#0B9B69]',
        'rejected' => 'bg-[#FFE8EB] text-[#D9475A]',
    ];

    $statusDots = [
        'submitted' => 'bg-[#3478FF]',
        'in_progress' => 'bg-[#159866]',
        'review' => 'bg-[#C78A15]',
        'completed' => 'bg-[#0B9B69]',
        'rejected' => 'bg-[#D9475A]',
    ];

    $statusClass = $statusClasses[$status] ?? 'bg-[#F1F4F8] text-[#7184A5]';
    $statusDot = $statusDots[$status] ?? 'bg-[#7184A5]';

    $nextActions = match ($status) {
        'submitted' => [
            'in_progress' => 'Approve Study',
            'rejected' => 'Reject Study',
        ],
        'in_progress' => [
            'review' => 'Move to Review',
        ],
        'review' => [
            'completed' => 'Complete Study',
        ],
        default => [],
    };

    $requesterName = $researchRequest->user?->name
        ?? $researchRequest->full_name
        ?? 'Unknown User';

    $requesterEmail = $researchRequest->user?->email
        ?? $researchRequest->email
        ?? null;

    $initials = collect(preg_split('/\s+/', trim($requesterName)))
        ->filter()
        ->take(2)
        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
        ->implode('');
@endphp

<div class="flex min-h-screen w-full bg-[#F5F8FF]">

    @include('partials.admin-sidebar')

    <main class="min-w-0 flex-1 bg-gradient-to-br from-[#DDEAFF] via-[#F4F8FF] to-white px-5 py-8 lg:px-9 lg:py-10">

        {{-- Breadcrumb --}}
        <div class="flex flex-wrap items-center gap-2 text-[11px] font-medium text-[#8A9BB8]">
            <a href="{{ route('admin.dashboard') }}" class="transition hover:text-[#3478FF]">
                Admin Workspace
            </a>

            <span>/</span>

            <a href="{{ route('admin.research-requests.index') }}" class="transition hover:text-[#3478FF]">
                Research Requests
            </a>

            <span>/</span>

            <span class="text-[#3478FF]">Study {{ $studyNumber }}</span>
        </div>

        {{-- Header --}}
        <div class="mt-7 flex flex-wrap items-start justify-between gap-5">
            <div class="min-w-0 flex-1">

                <div class="mb-3 flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center rounded-full border border-[#D4E3FF] bg-white px-3 py-1.5 font-mono text-[10px] font-semibold text-[#5276AD]">
                        Study {{ $studyNumber }}
                    </span>

                    <span class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-[10px] font-bold {{ $statusClass }}">
                        <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>
                        {{ $statusLabel }}
                    </span>
                </div>

                <h1 class="break-words text-[28px] font-bold tracking-[-1px] text-[#0B1B39] lg:text-[32px]">
                    {{ $researchRequest->goal }}
                </h1>

                <p class="mt-2 text-[12px] text-[#7184A5]">
                    Review study information, requester details and workflow status.
                </p>

                <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-2 text-[11px] text-[#8A9AB3]">
                    <span class="font-mono">
                        {{ $publicRequestCode }}
                    </span>

                    <span class="hidden h-1 w-1 rounded-full bg-[#B3C1D6] sm:block"></span>

                    <span>
                        Submitted {{ $researchRequest->created_at?->format('M d, Y \a\t H:i') ?? '—' }}
                    </span>
                </div>
            </div>

            <a
                href="{{ route('admin.research-requests.index') }}"
                class="inline-flex h-11 shrink-0 items-center gap-2 rounded-[11px] border border-[#D9E5F5] bg-white px-4 text-[11px] font-bold text-[#516581] shadow-sm transition hover:border-[#3478FF] hover:text-[#3478FF]"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m6 6l-6-6 6-6"/>
                </svg>
                Back to Requests
            </a>
        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="mt-6 flex items-start gap-3 rounded-[13px] border border-[#BCEAD2] bg-[#EDFCF4] px-5 py-4 text-[12px] font-medium text-[#15845B]">
                <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12l3 3 5-6"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="mt-6 rounded-[13px] border border-[#F5C7CD] bg-[#FFF0F2] px-5 py-4 text-[12px] text-[#C33F52]">
                <p class="font-bold">Please review the following errors:</p>

                <ul class="mt-2 list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Overview Cards --}}
        <div class="mt-8 grid gap-4 md:grid-cols-3">

            {{-- Study Number --}}
            <div class="relative overflow-hidden rounded-[18px] border border-[#C9DEFF] bg-gradient-to-br from-white to-[#EDF5FF] p-5 shadow-[0_2px_5px_rgba(30,64,120,0.06)]">
                <div class="pointer-events-none absolute -bottom-12 -right-10 h-32 w-32 rounded-full bg-[#DCEBFF]/70"></div>

                <div class="relative flex items-center gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-[14px] bg-[#E0EDFF] text-[#3478FF]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <rect x="4" y="4" width="16" height="16" rx="3"/>
                            <path stroke-linecap="round" d="M8 9h8M8 13h8M8 17h5"/>
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <p class="text-[11px] font-semibold text-[#7184A5]">
                            Study Number
                        </p>
                        <p class="mt-1 font-mono text-[20px] font-bold text-[#102243]">
                            {{ $studyNumber }}
                        </p>
                        <p class="mt-1 text-[10px] text-[#8A9AB3]">
                            Personal study reference
                        </p>
                    </div>
                </div>
            </div>

            {{-- Respondents --}}
            <div class="relative overflow-hidden rounded-[18px] border border-[#D9D3FA] bg-gradient-to-br from-white to-[#F2EFFF] p-5 shadow-[0_2px_5px_rgba(30,64,120,0.06)]">
                <div class="pointer-events-none absolute -bottom-12 -right-10 h-32 w-32 rounded-full bg-[#E8E1FF]/70"></div>

                <div class="relative flex items-center gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-[14px] bg-[#EAE4FF] text-[#7958E8]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 20v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9 10a4 4 0 100-8 4 4 0 000 8zM22 20v-2a4 4 0 00-3-3.87M16 2.13a4 4 0 010 7.75"/>
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <p class="text-[11px] font-semibold text-[#7184A5]">
                            Target Respondents
                        </p>
                        <p class="mt-1 text-[20px] font-bold text-[#102243]">
                            {{ number_format((int) $researchRequest->sample_size) }}
                        </p>
                        <p class="mt-1 text-[10px] text-[#8A9AB3]">
                            Requested sample size
                        </p>
                    </div>
                </div>
            </div>

            {{-- Target Market --}}
            <div class="relative overflow-hidden rounded-[18px] border border-[#BCEBD8] bg-gradient-to-br from-white to-[#EBFCF5] p-5 shadow-[0_2px_5px_rgba(30,64,120,0.06)]">
                <div class="pointer-events-none absolute -bottom-12 -right-10 h-32 w-32 rounded-full bg-[#D6F8E7]/70"></div>

                <div class="relative flex items-center gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-[14px] bg-[#D8F8E9] text-[#0D9D6D]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9"/>
                            <path stroke-linecap="round" d="M3 12h18M12 3a15 15 0 010 18M12 3a15 15 0 000 18"/>
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <p class="text-[11px] font-semibold text-[#7184A5]">
                            Target Market
                        </p>
                        <p class="mt-1 break-words text-[17px] font-bold text-[#102243]">
                            {{ $researchRequest->country ?: 'Not specified' }}
                        </p>
                        <p class="mt-1 text-[10px] text-[#8A9AB3]">
                            Selected research location
                        </p>
                    </div>
                </div>
            </div>

        </div>

        {{-- Main Layout --}}
        <div class="mt-6 grid items-start gap-5 xl:grid-cols-[minmax(0,1fr)_340px]">

            {{-- Left Column --}}
            <div class="min-w-0 space-y-5">

                {{-- Study Information --}}
                <section class="overflow-hidden rounded-[19px] border border-[#DFE8F6] bg-white shadow-[0_2px_5px_rgba(30,64,120,0.06)]">

                    <div class="flex items-center gap-3 border-b border-[#EDF1F8] px-6 py-5">
                        <div class="flex h-11 w-11 items-center justify-center rounded-[13px] bg-[#EAF2FF] text-[#3478FF]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <rect x="4" y="4" width="16" height="16" rx="3"/>
                                <path stroke-linecap="round" d="M8 9h8M8 13h8M8 17h5"/>
                            </svg>
                        </div>

                        <div>
                            <h2 class="text-[14px] font-bold text-[#102243]">
                                Study Information
                            </h2>
                            <p class="mt-1 text-[11px] text-[#8A9AB3]">
                                Research requirements and study configuration
                            </p>
                        </div>
                    </div>

                    <div class="p-6">

                        <div class="grid gap-3 sm:grid-cols-2">

                            {{-- Research Goal --}}
                            <div class="rounded-[13px] border border-[#EDF1F8] bg-[#F8FAFE] p-4">
                                <p class="text-[10px] font-bold uppercase tracking-[0.04em] text-[#8294B0]">
                                    Research Goal
                                </p>
                                <p class="mt-2 break-words text-[12px] font-semibold text-[#102243]">
                                    {{ $researchRequest->goal }}
                                </p>
                            </div>

                            {{-- Target Market --}}
                            <div class="rounded-[13px] border border-[#EDF1F8] bg-[#F8FAFE] p-4">
                                <p class="text-[10px] font-bold uppercase tracking-[0.04em] text-[#8294B0]">
                                    Target Market
                                </p>
                                <p class="mt-2 text-[12px] font-semibold text-[#102243]">
                                    {{ $researchRequest->country ?: 'Not specified' }}
                                </p>
                            </div>

                            {{-- Target Audience --}}
                            <div class="rounded-[13px] border border-[#EDF1F8] bg-[#F8FAFE] p-4">
                                <p class="text-[10px] font-bold uppercase tracking-[0.04em] text-[#8294B0]">
                                    Target Audience
                                </p>
                                <p class="mt-2 break-words text-[12px] font-semibold text-[#102243]">
                                    {{ $researchRequest->audience ?: 'Not specified' }}
                                </p>
                            </div>

                            {{-- Sample Size --}}
                            <div class="rounded-[13px] border border-[#EDF1F8] bg-[#F8FAFE] p-4">
                                <p class="text-[10px] font-bold uppercase tracking-[0.04em] text-[#8294B0]">
                                    Sample Size
                                </p>
                                <p class="mt-2 text-[12px] font-semibold text-[#102243]">
                                    {{ number_format((int) $researchRequest->sample_size) }} respondents
                                </p>
                            </div>

                            {{-- Timeline --}}
                            <div class="rounded-[13px] border border-[#EDF1F8] bg-[#F8FAFE] p-4">
                                <p class="text-[10px] font-bold uppercase tracking-[0.04em] text-[#8294B0]">
                                    Timeline
                                </p>
                                <p class="mt-2 text-[12px] font-semibold text-[#102243]">
                                    {{ $researchRequest->timeline ?: 'Not specified' }}
                                </p>
                            </div>

                            {{-- Current Status --}}
                            <div class="rounded-[13px] border border-[#EDF1F8] bg-[#F8FAFE] p-4">
                                <p class="text-[10px] font-bold uppercase tracking-[0.04em] text-[#8294B0]">
                                    Current Status
                                </p>
                                <div class="mt-2">
                                    <span class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-[10px] font-bold {{ $statusClass }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                            </div>

                        </div>

                        {{-- Research Methods --}}
                        <div class="mt-6 border-t border-[#EDF1F8] pt-6">
                            <h3 class="text-[12px] font-bold text-[#102243]">
                                Selected Research Methods
                            </h3>

                            <p class="mt-1 text-[11px] text-[#8A9AB3]">
                                Methods selected during the study submission.
                            </p>

                            <div class="mt-4 flex flex-wrap gap-2">
                                @forelse (($researchRequest->methods ?? []) as $method)
                                    <span class="inline-flex items-center gap-2 rounded-[9px] border border-[#D7E5FF] bg-[#EFF5FF] px-3 py-2 text-[11px] font-semibold text-[#3478FF]">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12l4 4L19 6"/>
                                        </svg>
                                        {{ $method }}
                                    </span>
                                @empty
                                    <p class="text-[12px] text-[#8A9AB3]">
                                        No methods selected.
                                    </p>
                                @endforelse
                            </div>
                        </div>

                        {{-- Additional Notes --}}
                        <div class="mt-6 border-t border-[#EDF1F8] pt-6">
                            <h3 class="text-[12px] font-bold text-[#102243]">
                                Additional Notes
                            </h3>

                            <div class="mt-3 rounded-[13px] border border-[#EDF1F8] bg-[#F8FAFE] p-4">
                                <p class="whitespace-pre-wrap break-words text-[12px] leading-[1.8] text-[#516581]">{{ $researchRequest->notes ?: 'No additional notes provided.' }}</p>
                            </div>
                        </div>

                        {{-- Rejection Reason --}}
                        @if ($status === 'rejected')
                            <div class="mt-6 border-t border-[#EDF1F8] pt-6">
                                <h3 class="flex items-center gap-2 text-[12px] font-bold text-[#D9475A]">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="9"/>
                                        <path stroke-linecap="round" d="M12 7v6m0 4h.01"/>
                                    </svg>
                                    Rejection Reason
                                </h3>

                                <div class="mt-3 rounded-[13px] border border-[#F7D7DD] bg-[#FFF3F5] p-4">
                                    <p class="whitespace-pre-wrap break-words text-[12px] leading-[1.8] text-[#9D4352]">{{ $researchRequest->rejection_reason ?: 'No reason provided.' }}</p>
                                </div>
                            </div>
                        @endif

                    </div>
                </section>

                {{-- Contact Information --}}
                <section class="overflow-hidden rounded-[19px] border border-[#DFE8F6] bg-white shadow-[0_2px_5px_rgba(30,64,120,0.06)]">

                    <div class="flex items-center gap-3 border-b border-[#EDF1F8] px-6 py-5">
                        <div class="flex h-11 w-11 items-center justify-center rounded-[13px] bg-[#EDEAFF] text-[#7557E9]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <circle cx="12" cy="8" r="4"/>
                                <path stroke-linecap="round" d="M4 21v-2a8 8 0 0116 0v2"/>
                            </svg>
                        </div>

                        <div>
                            <h2 class="text-[14px] font-bold text-[#102243]">
                                Contact Information
                            </h2>
                            <p class="mt-1 text-[11px] text-[#8A9AB3]">
                                Personal and company details provided by the requester
                            </p>
                        </div>
                    </div>

                    <div class="p-6">

                        <div class="mb-5 flex items-center gap-4 rounded-[14px] bg-gradient-to-r from-[#F0F5FF] to-[#F9FBFF] p-4">
                            <div class="flex h-13 w-13 shrink-0 items-center justify-center rounded-full bg-[#DDEAFF] px-3 py-4 text-[15px] font-bold text-[#3478FF]">
                                {{ $initials }}
                            </div>

                            <div class="min-w-0">
                                <p class="break-words text-[14px] font-bold text-[#102243]">
                                    {{ $requesterName }}
                                </p>
                                <p class="mt-1 text-[11px] text-[#7184A5]">
                                    Research Requester
                                </p>
                            </div>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">

                            <div class="rounded-[13px] border border-[#EDF1F8] bg-[#F8FAFE] p-4">
                                <p class="text-[10px] font-bold uppercase tracking-[0.04em] text-[#8294B0]">
                                    Full Name
                                </p>
                                <p class="mt-2 break-words text-[12px] font-semibold text-[#102243]">
                                    {{ $researchRequest->full_name ?: $requesterName }}
                                </p>
                            </div>

                            <div class="rounded-[13px] border border-[#EDF1F8] bg-[#F8FAFE] p-4">
                                <p class="text-[10px] font-bold uppercase tracking-[0.04em] text-[#8294B0]">
                                    Account Email
                                </p>

                                @if ($requesterEmail)
                                    <a href="mailto:{{ $requesterEmail }}" class="mt-2 block break-all text-[12px] font-semibold text-[#3478FF] hover:underline">
                                        {{ $requesterEmail }}
                                    </a>
                                @else
                                    <p class="mt-2 text-[12px] font-semibold text-[#102243]">
                                        Not provided
                                    </p>
                                @endif
                            </div>

                            <div class="rounded-[13px] border border-[#EDF1F8] bg-[#F8FAFE] p-4">
                                <p class="text-[10px] font-bold uppercase tracking-[0.04em] text-[#8294B0]">
                                    Phone Number
                                </p>
                                <p class="mt-2 text-[12px] font-semibold text-[#102243]">
                                    {{ $researchRequest->phone ?: 'Not provided' }}
                                </p>
                            </div>

                            <div class="rounded-[13px] border border-[#EDF1F8] bg-[#F8FAFE] p-4">
                                <p class="text-[10px] font-bold uppercase tracking-[0.04em] text-[#8294B0]">
                                    Company / Organization
                                </p>
                                <p class="mt-2 break-words text-[12px] font-semibold text-[#102243]">
                                    {{ $researchRequest->company ?: 'Not specified' }}
                                </p>
                            </div>

                        </div>

                    </div>
                </section>

            </div>

            {{-- Right Column --}}
            <aside class="min-w-0 space-y-5">

                {{-- Manage Request --}}
                <section
                    x-data="{ rejectOpen: @js($errors->has('rejection_reason')) }"
                    class="overflow-hidden rounded-[19px] border border-[#DFE8F6] bg-white shadow-[0_2px_5px_rgba(30,64,120,0.06)]"
                >

                    <div class="border-b border-[#DCE9FC] bg-gradient-to-r from-[#F0F6FF] to-white px-5 py-5">
                        <div class="flex items-center gap-3">
                            <div class="flex h-11 w-11 items-center justify-center rounded-[13px] bg-[#DCEAFF] text-[#3478FF]">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 20h9M16 4l4 4-11 11H5v-4L16 4z"/>
                                </svg>
                            </div>

                            <div>
                                <h2 class="text-[14px] font-bold text-[#102243]">
                                    Manage Request
                                </h2>
                                <p class="mt-1 text-[11px] text-[#8A9AB3]">
                                    Update the research workflow
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5">

                        <p class="text-[11px] leading-[1.8] text-[#7184A5]">
                            Status changes will be reflected on the user's research dashboard.
                        </p>

                        <div class="mt-5 rounded-[12px] border border-[#EDF1F8] bg-[#F8FAFE] p-4">
                            <p class="text-[10px] font-bold uppercase tracking-[0.04em] text-[#8294B0]">
                                Current Status
                            </p>

                            <div class="mt-3">
                                <span class="inline-flex items-center gap-2 rounded-full px-3 py-2 text-[11px] font-bold {{ $statusClass }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>
                                    {{ $statusLabel }}
                                </span>
                            </div>
                        </div>

                        @if (count($nextActions))
                            <form
                                method="POST"
                                action="{{ route('admin.research-requests.update-status', $researchRequest) }}"
                                class="mt-5 space-y-3"
                            >
                                @csrf
                                @method('PATCH')

                                @foreach ($nextActions as $value => $label)

                                    @if ($value === 'rejected')
                                        <button
                                            type="button"
                                            @click="rejectOpen = true"
                                            class="flex h-12 w-full cursor-pointer items-center justify-center gap-2 rounded-[11px] border border-[#F5C8D0] bg-[#FFF0F2] px-4 text-[12px] font-bold text-[#D9475A] transition hover:bg-[#FFE1E7]"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <circle cx="12" cy="12" r="9"/>
                                                <path stroke-linecap="round" d="M8 8l8 8M16 8l-8 8"/>
                                            </svg>
                                            {{ $label }}
                                        </button>
                                    @else
                                        <button
                                            type="submit"
                                            name="status"
                                            value="{{ $value }}"
                                            class="flex h-12 w-full cursor-pointer items-center justify-center gap-2 rounded-[11px] bg-[#397CF6] px-4 text-[12px] font-bold text-white shadow-[0_3px_8px_rgba(57,124,246,0.2)] transition hover:bg-[#2869E3]"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12l4 4L19 6"/>
                                            </svg>
                                            {{ $label }}
                                        </button>
                                    @endif

                                @endforeach
                            </form>
                        @else
                            <div class="mt-5 rounded-[12px] border border-[#D9EDE4] bg-[#F0FBF5] p-4">
                                <div class="flex items-start gap-3">
                                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-[#159866]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="9"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12l3 3 5-6"/>
                                    </svg>

                                    <div>
                                        <p class="text-[12px] font-bold text-[#176A4C]">
                                            Final Status Reached
                                        </p>
                                        <p class="mt-1 text-[11px] leading-[1.7] text-[#578674]">
                                            No further status changes are available for this study.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>

                    {{-- Rejection Modal --}}
                    @if ($status === 'submitted')
                        <template x-teleport="body">
                            <div
                                x-show="rejectOpen"
                                x-cloak
                                @keydown.escape.window="rejectOpen = false"
                                role="dialog"
                                aria-modal="true"
                                aria-labelledby="reject-study-title"
                                class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0B1B39]/60 px-4 py-6 backdrop-blur-[3px]"
                            >
                                <div
                                    @click.outside="rejectOpen = false"
                                    class="w-full max-w-[480px] overflow-hidden rounded-[20px] bg-white shadow-[0_25px_70px_rgba(11,27,57,0.25)]"
                                >

                                    <div class="border-b border-[#F0E3E6] bg-gradient-to-r from-[#FFF0F2] to-white px-7 py-6">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-[14px] bg-[#FFE1E6] text-[#D9475A]">
                                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                    <circle cx="12" cy="12" r="9"/>
                                                    <path stroke-linecap="round" d="M8 8l8 8M16 8l-8 8"/>
                                                </svg>
                                            </div>

                                            <div>
                                                <h3 id="reject-study-title" class="text-[17px] font-bold text-[#102243]">
                                                    Reject Research Study
                                                </h3>
                                                <p class="mt-1 text-[11px] text-[#8A9AB3]">
                                                    Confirm this action before proceeding
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <form
                                        method="POST"
                                        action="{{ route('admin.research-requests.update-status', $researchRequest) }}"
                                        class="p-7"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <input type="hidden" name="status" value="rejected">

                                        <div class="rounded-[12px] border border-[#F8DBDF] bg-[#FFF5F6] p-4">
                                            <p class="text-[12px] leading-[1.8] text-[#A44D5B]">
                                                Are you sure you want to reject this study?
                                                Reserved respondents will be returned to the user.
                                            </p>
                                        </div>

                                        <label for="rejection_reason" class="mt-6 block text-[12px] font-bold text-[#102243]">
                                            Reason for rejection
                                            <span class="font-normal text-[#8A9AB3]">(Optional)</span>
                                        </label>

                                        <textarea
                                            id="rejection_reason"
                                            name="rejection_reason"
                                            rows="5"
                                            maxlength="2000"
                                            placeholder="Explain why this study cannot be approved..."
                                            class="mt-2 w-full resize-y rounded-[11px] border border-[#DCE5F3] bg-[#FAFCFF] px-4 py-3 text-[12px] leading-[1.7] text-[#102243] outline-none transition placeholder:text-[#A3B0C2] focus:border-[#3478FF] focus:bg-white focus:ring-2 focus:ring-[#3478FF]/10"
                                        >{{ old('rejection_reason') }}</textarea>

                                        <p class="mt-2 text-[10px] leading-[1.6] text-[#8A9AB3]">
                                            This reason can be displayed to the user when the study is rejected.
                                        </p>

                                        <div class="mt-6 flex flex-wrap justify-end gap-3">
                                            <button
                                                type="button"
                                                @click="rejectOpen = false"
                                                class="h-11 cursor-pointer rounded-[11px] border border-[#DCE5F3] bg-white px-5 text-[12px] font-semibold text-[#516581] transition hover:bg-[#F6F9FF]"
                                            >
                                                Cancel
                                            </button>

                                            <button
                                                type="submit"
                                                class="h-11 cursor-pointer rounded-[11px] bg-[#D9475A] px-5 text-[12px] font-bold text-white transition hover:bg-[#BE3548]"
                                            >
                                                Confirm Rejection
                                            </button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </template>
                    @endif

                </section>

                {{-- Account Information --}}
                <section class="overflow-hidden rounded-[19px] border border-[#DFE8F6] bg-white shadow-[0_2px_5px_rgba(30,64,120,0.06)]">

                    <div class="flex items-center gap-3 border-b border-[#EDF1F8] px-5 py-5">
                        <div class="flex h-11 w-11 items-center justify-center rounded-[13px] bg-[#EAF9F1] text-[#159866]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <circle cx="12" cy="8" r="4"/>
                                <path stroke-linecap="round" d="M4 21v-2a8 8 0 0116 0v2"/>
                            </svg>
                        </div>

                        <div>
                            <h2 class="text-[14px] font-bold text-[#102243]">
                                Account Information
                            </h2>
                            <p class="mt-1 text-[11px] text-[#8A9AB3]">
                                Request ownership and identifiers
                            </p>
                        </div>
                    </div>

                    <div class="space-y-3 p-5">

                        <div class="rounded-[12px] bg-[#F8FAFE] p-4">
                            <p class="text-[10px] font-bold uppercase tracking-[0.04em] text-[#8294B0]">
                                Submitted By
                            </p>
                            <p class="mt-2 break-words text-[12px] font-semibold text-[#102243]">
                                {{ $researchRequest->user?->name ?? 'Unknown user' }}
                            </p>
                        </div>

                        <div class="rounded-[12px] bg-[#F8FAFE] p-4">
                            <p class="text-[10px] font-bold uppercase tracking-[0.04em] text-[#8294B0]">
                                User ID
                            </p>
                            <p class="mt-2 font-mono text-[12px] font-semibold text-[#102243]">
                                {{ $researchRequest->user_id ?? '—' }}
                            </p>
                        </div>

                        <div class="rounded-[12px] bg-[#F8FAFE] p-4">
                            <p class="text-[10px] font-bold uppercase tracking-[0.04em] text-[#8294B0]">
                                Study Number
                            </p>
                            <p class="mt-2 font-mono text-[12px] font-semibold text-[#102243]">
                                {{ $studyNumber }}
                            </p>
                        </div>

                        <div class="rounded-[12px] bg-[#F8FAFE] p-4">
                            <p class="text-[10px] font-bold uppercase tracking-[0.04em] text-[#8294B0]">
                                Public Request ID
                            </p>
                            <p class="mt-2 break-all font-mono text-[12px] font-semibold text-[#102243]">
                                {{ $publicRequestCode }}
                            </p>
                        </div>

                    </div>

                </section>

            </aside>
        </div>

        {{-- Footer --}}
        <div class="mt-8 flex flex-wrap items-center justify-between gap-3 border-t border-[#DCE7F5] pt-5">
            <p class="text-[10px] text-[#91A0B6]">
                Impala Research · Administration Workspace
            </p>
            <p class="text-[10px] text-[#91A0B6]">
                Research Request Details
            </p>
        </div>

    </main>
</div>
@endsection
