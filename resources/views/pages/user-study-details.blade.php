
@extends('layouts.app')

@section('content')
@php
    $code = 'REQ-' . str_pad((string) $study->id, 4, '0', STR_PAD_LEFT);

    $status = $study->status ?? 'submitted';

    $statusLabel = match ($status) {
        'submitted' => 'Submitted',
        'in_progress', 'in_field' => 'In Field',
        'review', 'under_review' => 'In Review',
        'completed' => 'Completed',
        default => ucwords(str_replace('_', ' ', $status)),
    };

    $statusColor = match ($status) {
        'submitted' => 'bg-[#E7EFFF] text-[#3775D6]',
        'in_progress', 'in_field' => 'bg-[#E0F4E5] text-[#248743]',
        'review', 'under_review' => 'bg-[#FFF3D8] text-[#C37B0A]',
        'completed' => 'bg-[#E8F4EA] text-[#248743]',
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
@endphp

<div class="flex min-h-screen w-full bg-white">

    @include('partials.user-sidebar')

    <main class="min-h-screen min-w-0 flex-1 bg-gradient-to-br from-[#DCEAFF] via-[#F7FAFF] to-white px-[30px] py-[46px] lg:px-[42px]">

        <a href="{{ route('my-studies.index') }}" class="text-[12px] text-[#4D7FCF] hover:underline">
            ← Back to My Research Requests
        </a>

        <div class="mt-[25px] flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="font-mono text-[11px] text-[#777777]">
                    {{ $code }}
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
                                {{ $reached ? 'bg-[#4D7FCF] text-white' : 'bg-[#E7E7E7] text-[#888888]' }}">
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
        </section>

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

                <section class="rounded-[12px] border border-[#BFC3C9] bg-white p-[22px]">
                    <h2 class="text-[14px] font-medium text-[#192231]">
                        Request Summary
                    </h2>

                    <div class="mt-[20px] space-y-[18px]">
                        <div>
                            <p class="text-[11px] text-[#777777]">Request ID</p>
                            <p class="mt-[5px] font-mono text-[12px] text-[#192231]">
                                {{ $code }}
                            </p>
                        </div>

                        <div>
                            <p class="text-[11px] text-[#777777]">Submitted</p>
                            <p class="mt-[5px] text-[12px] text-[#192231]">
                                {{ $study->created_at?->format('M d, Y') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-[11px] text-[#777777]">Last Updated</p>
                            <p class="mt-[5px] text-[12px] text-[#192231]">
                                {{ $study->updated_at?->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                </section>

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
