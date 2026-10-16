
@extends('layouts.app')

@section('content')

@php
    // Personal sequential number and shared public request ID.
    // The internal database ID remains unchanged.
    $studyNumber = $study->study_number;
    $publicRequestCode = $study->public_request_code;

    $status = $study->status ?? 'submitted';

    $statusLabel = match ($status) {
        'submitted' => 'Submitted',
        'in_progress', 'in_field' => 'In Field',
        'review', 'under_review' => 'In Review',
        'completed' => 'Completed',
        'rejected' => 'Rejected',
        default => ucwords(str_replace('_', ' ', $status)),
    };

    $statusColor = match ($status) {
        'submitted' => 'bg-[#E7EFFF] text-[#3775D6]',
        'in_progress', 'in_field' => 'bg-[#E0F4E5] text-[#248743]',
        'review', 'under_review' => 'bg-[#FFF3D8] text-[#C37B0A]',
        'completed' => 'bg-[#E8F4EA] text-[#248743]',
        'rejected' => 'bg-[#FDE8E8] text-[#C84747]',
        default => 'bg-[#F1F1F1] text-[#777777]',
    };

    $steps = [
        ['key' => 'submitted', 'label' => 'Submitted'],
        ['key' => 'in_progress', 'label' => 'In Progress'],
        ['key' => 'review', 'label' => 'Review'],
        ['key' => 'completed', 'label' => 'Completed'],
    ];

    $progressIndex = match ($status) {
        'submitted' => 0,
        'in_progress', 'in_field' => 1,
        'review', 'under_review' => 2,
        'completed' => 3,
        default => -1,
    };

    $rejectionReason = trim((string) ($study->rejection_reason ?? ''));
@endphp

<div class="flex min-h-screen w-full bg-white">

    @include('partials.user-sidebar')

    <main class="min-h-screen min-w-0 flex-1 bg-gradient-to-br from-[#DCEAFF] via-[#F7FAFF] to-white px-[30px] py-[46px] lg:px-[42px]">

        {{-- Back Link --}}

        <a
            href="{{ route('my-studies.index') }}"
            class="text-[12px] text-[#4D7FCF] hover:underline"
        >
            ← Back to My Research Requests
        </a>

        {{-- Study Header --}}

        <div class="mt-[25px] flex flex-wrap items-start justify-between gap-4">

            <div>

                <p class="font-mono text-[11px] text-[#777777]">
                    Study {{ $studyNumber }}
                    <span class="mx-[6px]">•</span>
                    Request ID: {{ $publicRequestCode }}
                </p>

                <h1 class="mt-[8px] text-[25px] font-medium text-[#171717]">
                    {{ $study->goal }}
                </h1>

                <p class="mt-[7px] text-[12px] text-[#666666]">
                    Submitted on {{ $study->created_at?->format('F d, Y') }}
                </p>

            </div>

            <span class="rounded-full px-[16px] py-[8px] text-[11px] font-medium {{ $statusColor }}">
                ● {{ $statusLabel }}
            </span>

        </div>

        {{-- Research Progress --}}

        <section class="mt-[30px] rounded-[12px] border border-[#BFC3C9] bg-white p-[25px]">

            <h2 class="text-[15px] font-medium text-[#192231]">
                Research Progress
            </h2>

            @if ($status === 'rejected')

                {{-- Rejected state: replaces the four progress stages --}}

                <div class="mt-[22px] rounded-[10px] border border-[#F2B8B8] bg-[#FFF6F6] p-[22px]">

                    <div class="flex items-start gap-[14px]">

                        <div class="flex h-[38px] w-[38px] shrink-0 items-center justify-center rounded-full bg-[#FDE2E2] text-[#C84747]">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="21"
                                height="21"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                            >
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="m15 9-6 6"></path>
                                <path d="m9 9 6 6"></path>
                            </svg>

                        </div>

                        <div class="min-w-0 flex-1">

                            <div class="flex flex-wrap items-center gap-[10px]">

                                <h3 class="text-[15px] font-medium text-[#A52F2F]">
                                    Your research study was
                                </h3>

                                <span class="rounded-full bg-[#FDE2E2] px-[10px] py-[5px] text-[10px] font-medium text-[#C84747]">
                                    Rejected
                                </span>

                            </div>

                            <p class="mt-[9px] text-[12px] leading-[1.7] text-[#765858]">
                                Unfortunately, your research request was not approved by the Impala Research team.
                            </p>

                        </div>

                    </div>

                    {{-- Rejection Reason --}}

                    <div class="mt-[22px] border-t border-[#F1CECE] pt-[19px]">

                        <p class="text-[11px] font-medium text-[#A52F2F]">
                            Reason for rejection
                        </p>

                        <p class="mt-[9px] whitespace-pre-wrap break-words text-[12px] leading-[1.8] text-[#513737]">{{ $rejectionReason !== '' ? $rejectionReason : 'No specific reason was provided by the Impala Research team.' }}</p>

                    </div>

                    {{-- Respondent Allocation Refund --}}

                    @if ((int) ($study->respondents_reserved ?? 0) > 0)

                        <div class="mt-[22px] flex items-start gap-[10px] rounded-[7px] border border-[#C7E7CF] bg-[#F2FAF4] px-[14px] py-[13px]">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="17"
                                height="17"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="#248743"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="mt-[1px] shrink-0"
                                aria-hidden="true"
                            >
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="m8 12 2.5 2.5L16 9"></path>
                            </svg>

                            <p class="text-[11px] leading-[1.7] text-[#248743]">
                                Your {{ number_format((int) $study->respondents_reserved) }} reserved respondents have been returned to the original subscription.
                            </p>

                        </div>

                    @endif

                </div>

                <p class="mt-[17px] text-[10px] text-[#888888]">
                    If you have questions about this decision, please contact the Impala Research team.
                </p>

            @else

                {{-- Standard Research Progress --}}

                <p class="mt-[7px] text-[11px] text-[#777777]">
                    Track the current stage of your research request.
                </p>

                <div class="mt-[28px] grid grid-cols-2 gap-[12px] md:grid-cols-4">

                    @foreach ($steps as $index => $step)

                        @php
                            $reached = $progressIndex >= $index;
                            $current = $progressIndex === $index;
                        @endphp

                        <div class="rounded-[8px] border px-[14px] py-[18px]
                            {{ $current
                                ? 'border-[#4D7FCF] bg-[#F1F5FF]'
                                : ($reached
                                    ? 'border-[#B8DFC4] bg-[#F2FAF4]'
                                    : 'border-[#E4E4E4] bg-[#FAFAFA]') }}"
                        >

                            <div class="flex items-center gap-[9px]">

                                <span class="flex h-[25px] w-[25px] items-center justify-center rounded-full text-[11px] font-medium
                                    {{ $reached ? 'bg-[#4D7FCF] text-white' : 'bg-[#E7E7E7] text-[#888888]' }}"
                                >
                                    {{ $reached ? '✓' : $index + 1 }}
                                </span>

                                <span class="text-[11px] font-medium text-[#192231]">
                                    {{ $step['label'] }}
                                </span>

                            </div>

                            <p class="mt-[12px] text-[10px] text-[#777777]">
                                {{ $current ? 'Current stage' : ($reached ? 'Stage reached' : 'Upcoming') }}
                            </p>

                        </div>

                    @endforeach

                </div>

                <p class="mt-[18px] text-[10px] text-[#888888]">
                    Progress is based on the status assigned by the Impala Research team.
                </p>

            @endif

        </section>

        {{-- Main Information Layout --}}

        <div class="mt-[18px] grid gap-[18px] xl:grid-cols-[1fr_280px]">

            <div class="space-y-[18px]">

                {{-- Study Information --}}

                <section class="rounded-[12px] border border-[#BFC3C9] bg-white p-[25px]">

                    <h2 class="text-[15px] font-medium text-[#192231]">
                        Study Information
                    </h2>

                    <div class="mt-[25px] grid gap-[24px] sm:grid-cols-2">

                        @foreach ([
                            ['Research Goal', $study->goal],
                            ['Target Market', $study->country],
                            ['Target Audience', $study->audience],
                            ['Sample Size', $study->sample_size . ' respondents'],
                            ['Timeline', $study->timeline ?: 'Not specified'],
                            ['Current Status', $statusLabel],
                        ] as $detail)

                            <div>

                                <p class="text-[11px] text-[#777777]">
                                    {{ $detail[0] }}
                                </p>

                                <p class="mt-[6px] break-words text-[13px] font-medium text-[#192231]">
                                    {{ $detail[1] }}
                                </p>

                            </div>

                        @endforeach

                    </div>

                    {{-- Research Methods --}}

                    <div class="mt-[28px] border-t border-[#EAEAEA] pt-[22px]">

                        <p class="text-[11px] text-[#777777]">
                            Selected Research Methods
                        </p>

                        <div class="mt-[12px] flex flex-wrap gap-[8px]">

                            @forelse ($study->methods ?? [] as $method)

                                <span class="rounded-full bg-[#E7EFFF] px-[12px] py-[7px] text-[11px] text-[#3775D6]">
                                    {{ $method }}
                                </span>

                            @empty

                                <span class="text-[12px] text-[#777777]">
                                    No methods selected
                                </span>

                            @endforelse

                        </div>

                    </div>

                    {{-- Additional Notes --}}

                    <div class="mt-[28px] border-t border-[#EAEAEA] pt-[22px]">

                        <p class="text-[11px] text-[#777777]">
                            Additional Notes
                        </p>

                        <p class="mt-[9px] whitespace-pre-wrap break-words text-[12px] leading-[1.7] text-[#333333]">{{ $study->notes ?: 'No additional notes provided.' }}</p>

                    </div>

                </section>

                {{-- Contact Information --}}

                <section class="rounded-[12px] border border-[#BFC3C9] bg-white p-[25px]">

                    <h2 class="text-[15px] font-medium text-[#192231]">
                        Contact Information
                    </h2>

                    <div class="mt-[25px] grid gap-[24px] sm:grid-cols-2">

                        @foreach ([
                            ['Full Name', $study->full_name],
                            ['Account Email', auth()->user()->email],
                            ['Phone Number', $study->phone],
                            ['Company', $study->company ?: 'Not specified'],
                        ] as $detail)

                            <div>

                                <p class="text-[11px] text-[#777777]">
                                    {{ $detail[0] }}
                                </p>

                                <p class="mt-[6px] break-words text-[13px] font-medium text-[#192231]">
                                    {{ $detail[1] }}
                                </p>

                            </div>

                        @endforeach

                    </div>

                </section>

            </div>

            {{-- Right Side --}}

            <aside class="space-y-[18px]">

                {{-- Request Summary --}}

                <section class="rounded-[12px] border border-[#BFC3C9] bg-white p-[22px]">

                    <h2 class="text-[14px] font-medium text-[#192231]">
                        Request Summary
                    </h2>

                    <div class="mt-[20px] space-y-[18px]">

                        {{-- Personal Study Number --}}

                        <div>

                            <p class="text-[11px] text-[#777777]">
                                Study Number
                            </p>

                            <p class="mt-[5px] font-mono text-[12px] text-[#192231]">
                                {{ $studyNumber }}
                            </p>

                        </div>

                        {{-- Shared Public Request ID --}}

                        <div>

                            <p class="text-[11px] text-[#777777]">
                                Request ID
                            </p>

                            <p class="mt-[5px] break-all font-mono text-[12px] text-[#192231]">
                                {{ $publicRequestCode }}
                            </p>

                        </div>

                        <div>

                            <p class="text-[11px] text-[#777777]">
                                Submitted
                            </p>

                            <p class="mt-[5px] text-[12px] text-[#192231]">
                                {{ $study->created_at?->format('M d, Y') }}
                            </p>

                        </div>

                        <div>

                            <p class="text-[11px] text-[#777777]">
                                Last Updated
                            </p>

                            <p class="mt-[5px] text-[12px] text-[#192231]">
                                {{ $study->updated_at?->format('M d, Y') }}
                            </p>

                        </div>

                    </div>

                </section>

                {{-- Need Assistance? --}}

                <section class="rounded-[12px] border border-[#BFC3C9] bg-white p-[22px]">

                    <h2 class="text-[14px] font-medium text-[#192231]">
                        Need Assistance?
                    </h2>

                    <p class="mt-[9px] text-[11px] leading-[1.7] text-[#777777]">
                        Have questions about your research request?
                        Contact the Impala Research team.
                    </p>

                    <a
                        href="{{ route('contact') }}"
                        class="mt-[20px] flex h-[40px] items-center justify-center rounded-[6px] border border-[#4D7FCF] text-[11px] font-medium text-[#4D7FCF] hover:bg-[#F1F5FF]"
                    >
                        Contact Support
                    </a>

                </section>

            </aside>

        </div>

    </main>

</div>

@endsection
