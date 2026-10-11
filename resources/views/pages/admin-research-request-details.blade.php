
@extends('layouts.app')

@section('content')
@php
    $requestCode = 'REQ-' . str_pad(
        (string) $researchRequest->id,
        4,
        '0',
        STR_PAD_LEFT
    );

    $statusLabels = [
        'submitted' => 'Submitted',
        'in_progress' => 'In Progress',
        'review' => 'Review',
        'completed' => 'Completed',
    ];

    $statusLabel = $statusLabels[$researchRequest->status]
        ?? ucfirst(str_replace('_', ' ', $researchRequest->status));
@endphp

<div class="flex min-h-screen w-full bg-white">

    @include('partials.admin-sidebar')

    <main class="min-h-screen min-w-0 flex-1 bg-gradient-to-br from-[#DCEAFF] via-[#F7FAFF] to-white px-[30px] py-[46px] lg:px-[42px]">

        {{-- Back Link --}}
        <a
            href="{{ route('admin.research-requests.index') }}"
            class="inline-flex items-center gap-[7px] text-[12px] text-[#4D7FCF] hover:underline"
        >
            ← Back to Research Requests
        </a>

        {{-- Header --}}
        <div class="mt-[25px] flex flex-wrap items-start justify-between gap-4">

            <div>
                <p class="font-mono text-[11px] text-[#777777]">
                    {{ $requestCode }}
                </p>

                <h1 class="mt-[8px] text-[25px] font-medium tracking-[-0.6px] text-[#171717]">
                    {{ $researchRequest->goal }}
                </h1>

                <p class="mt-[7px] text-[12px] text-[#666666]">
                    Submitted on {{ $researchRequest->created_at?->format('F d, Y \a\t H:i') }}
                </p>
            </div>

            <span class="rounded-full bg-[#E7EFFF] px-[16px] py-[8px] text-[11px] font-medium text-[#3775D6]">
                {{ $statusLabel }}
            </span>

        </div>

        @if (session('success'))
            <div class="mt-[24px] rounded-[8px] border border-[#B8DFC4] bg-[#E8F7EC] px-[18px] py-[14px] text-[12px] text-[#248743]">
                {{ session('success') }}
            </div>
        @endif

        <div class="mt-[30px] grid gap-[18px] xl:grid-cols-[1fr_300px]">

            {{-- Left --}}
            <div class="space-y-[18px]">

                {{-- Study Information --}}
                <section class="rounded-[12px] border border-[#BFC3C9] bg-white p-[25px]">

                    <h2 class="text-[15px] font-medium text-[#192231]">
                        Study Information
                    </h2>

                    <div class="mt-[25px] grid gap-[24px] sm:grid-cols-2">

                        <div>
                            <p class="text-[11px] text-[#777777]">Research Goal</p>
                            <p class="mt-[6px] text-[13px] font-medium text-[#192231]">
                                {{ $researchRequest->goal }}
                            </p>
                        </div>

                        <div>
                            <p class="text-[11px] text-[#777777]">Target Market</p>
                            <p class="mt-[6px] text-[13px] font-medium text-[#192231]">
                                {{ $researchRequest->country }}
                            </p>
                        </div>

                        <div>
                            <p class="text-[11px] text-[#777777]">Target Audience</p>
                            <p class="mt-[6px] text-[13px] font-medium text-[#192231]">
                                {{ $researchRequest->audience }}
                            </p>
                        </div>

                        <div>
                            <p class="text-[11px] text-[#777777]">Sample Size</p>
                            <p class="mt-[6px] text-[13px] font-medium text-[#192231]">
                                {{ $researchRequest->sample_size }} respondents
                            </p>
                        </div>

                        <div>
                            <p class="text-[11px] text-[#777777]">Timeline</p>
                            <p class="mt-[6px] text-[13px] font-medium text-[#192231]">
                                {{ $researchRequest->timeline ?: 'Not specified' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-[11px] text-[#777777]">Current Status</p>
                            <p class="mt-[6px] text-[13px] font-medium text-[#192231]">
                                {{ $statusLabel }}
                            </p>
                        </div>

                    </div>

                    <div class="mt-[28px] border-t border-[#EAEAEA] pt-[22px]">
                        <p class="text-[11px] text-[#777777]">
                            Selected Research Methods
                        </p>

                        <div class="mt-[12px] flex flex-wrap gap-[8px]">
                            @forelse (($researchRequest->methods ?? []) as $method)
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

                        <p class="mt-[9px] whitespace-pre-wrap break-words text-[12px] leading-[1.7] text-[#333333]">
                            {{ $researchRequest->notes ?: 'No additional notes provided.' }}
                        </p>
                    </div>

                </section>

                {{-- Contact Information --}}
                <section class="rounded-[12px] border border-[#BFC3C9] bg-white p-[25px]">

                    <h2 class="text-[15px] font-medium text-[#192231]">
                        Contact Information
                    </h2>

                    <div class="mt-[25px] grid gap-[24px] sm:grid-cols-2">

                        <div>
                            <p class="text-[11px] text-[#777777]">Full Name</p>
                            <p class="mt-[6px] text-[13px] font-medium text-[#192231]">
                                {{ $researchRequest->full_name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-[11px] text-[#777777]">Account Email</p>
                            <p class="mt-[6px] break-all text-[13px] font-medium text-[#192231]">
                                {{ $researchRequest->user?->email ?? $researchRequest->email }}
                            </p>
                        </div>

                        <div>
                            <p class="text-[11px] text-[#777777]">Phone Number</p>
                            <p class="mt-[6px] text-[13px] font-medium text-[#192231]">
                                {{ $researchRequest->phone }}
                            </p>
                        </div>

                        <div>
                            <p class="text-[11px] text-[#777777]">Company</p>
                            <p class="mt-[6px] text-[13px] font-medium text-[#192231]">
                                {{ $researchRequest->company ?: 'Not specified' }}
                            </p>
                        </div>

                    </div>

                </section>
            </div>

            {{-- Right --}}
            <aside class="space-y-[18px]">

                {{-- Change Status --}}
                <section class="rounded-[12px] border border-[#BFC3C9] bg-white p-[22px]">

                    <h2 class="text-[14px] font-medium text-[#192231]">
                        Manage Request
                    </h2>

                    <p class="mt-[8px] text-[11px] leading-[1.6] text-[#777777]">
                        Update the research request status.
                        Changes will be visible on the user's dashboard.
                    </p>

                    <form
                        method="POST"
                        action="{{ route('admin.research-requests.update-status', $researchRequest) }}"
                        class="mt-[22px]"
                    >
                        @csrf
                        @method('PATCH')

                        <label for="status" class="block text-[11px] text-[#555555]">
                            Request Status
                        </label>

                        <select
                            id="status"
                            name="status"
                            class="mt-[9px] h-[42px] w-full rounded-[6px] border border-[#BFC3C9] bg-white px-[12px] text-[12px] text-[#333333] outline-none focus:border-[#4D7FCF]"
                        >
                            <option value="submitted" @selected($researchRequest->status === 'submitted')>
                                Submitted
                            </option>

                            <option value="in_progress" @selected($researchRequest->status === 'in_progress')>
                                In Progress
                            </option>

                            <option value="review" @selected($researchRequest->status === 'review')>
                                Review
                            </option>

                            <option value="completed" @selected($researchRequest->status === 'completed')>
                                Completed
                            </option>
                        </select>

                        @error('status')
                            <p class="mt-[8px] text-[11px] text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                        <button
                            type="submit"
                            class="mt-[18px] h-[42px] w-full rounded-[6px] bg-[#4D7FCF] text-[12px] font-medium text-white transition hover:bg-[#416FB4]"
                        >
                            Update Status
                        </button>

                    </form>
                </section>

                {{-- Account Information --}}
                <section class="rounded-[12px] border border-[#BFC3C9] bg-white p-[22px]">

                    <h2 class="text-[14px] font-medium text-[#192231]">
                        Account Information
                    </h2>

                    <div class="mt-[20px] space-y-[16px] text-[12px]">

                        <div>
                            <p class="text-[11px] text-[#777777]">
                                Submitted By
                            </p>

                            <p class="mt-[5px] font-medium text-[#192231]">
                                {{ $researchRequest->user?->name ?? 'Unknown user' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-[11px] text-[#777777]">
                                User ID
                            </p>

                            <p class="mt-[5px] font-mono text-[#192231]">
                                {{ $researchRequest->user_id ?? '—' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-[11px] text-[#777777]">
                                Request ID
                            </p>

                            <p class="mt-[5px] font-mono text-[#192231]">
                                {{ $requestCode }}
                            </p>
                        </div>

                    </div>
                </section>

            </aside>
        </div>
    </main>
</div>
@endsection
