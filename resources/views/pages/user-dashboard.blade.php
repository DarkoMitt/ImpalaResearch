
@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();

    $fullName = trim($user->name ?? '');
    $firstName = explode(' ', $fullName)[0] ?: 'User';

    $nameParts = preg_split('/\s+/', $fullName);

    $initials = collect($nameParts)
        ->filter()
        ->take(2)
        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
        ->implode('');

    $accountType = $user->account_type ?? 'individual';

    $accountTypeLabel = match ($accountType) {
        'company' => 'Company',
        'individual' => 'Individual',
        default => ucfirst($accountType),
    };

    /*
    |--------------------------------------------------------------------------
    | Research Statistics
    |--------------------------------------------------------------------------
    */

    $researchQuery = \App\Models\ResearchRequest::query()
        ->where('user_id', $user->id);

    $totalStudies = (clone $researchQuery)->count();

    $inFieldCount = (clone $researchQuery)
        ->whereIn('status', ['in_progress', 'in_field'])
        ->count();

    $studies = (clone $researchQuery)
        ->latest()
        ->limit(5)
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Subscription Statistics
    |--------------------------------------------------------------------------
    */

    $activeSubscription = $user->activeSubscription();
    $activePlan = $activeSubscription?->plan;

    $remainingRespondents = $activeSubscription?->remaining_respondents ?? 0;
    $grantedRespondents = $activeSubscription?->respondents_granted ?? 0;

    $respondentsPercent = $grantedRespondents > 0
        ? min(100, max(0, ($remainingRespondents / $grantedRespondents) * 100))
        : 0;

    $cancellationScheduled = $activeSubscription?->cancel_at !== null;

    /*
    |--------------------------------------------------------------------------
    | Research Status Labels
    |--------------------------------------------------------------------------
    */

    $statusLabels = [
        'submitted' => 'SUBMITTED',
        'pending' => 'PENDING',
        'in_progress' => 'IN FIELD',
        'in_field' => 'IN FIELD',
        'review' => 'IN REVIEW',
        'under_review' => 'IN REVIEW',
        'completed' => 'COMPLETED',
        'rejected' => 'REJECTED',
        'cancelled' => 'CANCELLED',
    ];

    $statusStyles = [
        'submitted' => 'bg-[#E8F1FF] text-[#3478FF]',
        'pending' => 'bg-[#FFF3DC] text-[#C68B16]',
        'in_progress' => 'bg-[#E2F8EE] text-[#159866]',
        'in_field' => 'bg-[#E2F8EE] text-[#159866]',
        'review' => 'bg-[#FFF3DC] text-[#C68B16]',
        'under_review' => 'bg-[#FFF3DC] text-[#C68B16]',
        'completed' => 'bg-[#DFF8EC] text-[#0B9B69]',
        'rejected' => 'bg-[#FFE8EB] text-[#D9475A]',
        'cancelled' => 'bg-[#F0F2F6] text-[#7B879B]',
    ];

    $statusDots = [
        'submitted' => 'bg-[#3478FF]',
        'pending' => 'bg-[#C68B16]',
        'in_progress' => 'bg-[#159866]',
        'in_field' => 'bg-[#159866]',
        'review' => 'bg-[#C68B16]',
        'under_review' => 'bg-[#C68B16]',
        'completed' => 'bg-[#0B9B69]',
        'rejected' => 'bg-[#D9475A]',
        'cancelled' => 'bg-[#7B879B]',
    ];
@endphp

<div class="flex min-h-screen w-full bg-[#F5F8FF]">

    @include('partials.user-sidebar')

    <main class="min-w-0 flex-1 bg-gradient-to-br from-[#DDEAFF] via-[#F4F8FF] to-white px-5 py-8 lg:px-9 lg:py-10">

        {{-- Success Message --}}
        @if (session('success'))
            <div class="mb-6 flex items-start gap-3 rounded-[13px] border border-[#BCEAD2] bg-[#EDFCF4] px-5 py-4 text-[12px] font-medium text-[#15845B]">
                <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12l3 3 5-6"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Error Message --}}
        @if (session('error'))
            <div class="mb-6 flex items-start gap-3 rounded-[13px] border border-[#F5C7CD] bg-[#FFF0F2] px-5 py-4 text-[12px] font-medium text-[#C33F52]">
                <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9"/>
                    <path stroke-linecap="round" d="M12 7v6m0 4h.01"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Welcome Header --}}
        <div class="flex flex-wrap items-start justify-between gap-5">

            <div>
                <div class="mb-3 flex items-center gap-2">
                    <span class="inline-flex items-center gap-2 rounded-full border border-[#CFE0FF] bg-white/80 px-3 py-1.5 text-[10px] font-bold text-[#3478FF]">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#3478FF]"></span>
                        Research Workspace
                    </span>
                </div>

                <h1 class="text-[28px] font-bold tracking-[-1px] text-[#0B1B39] lg:text-[32px]">
                    Welcome back, {{ $firstName }}!
                </h1>

                <p class="mt-2 text-[12px] text-[#7184A5]">
                    Here's what's happening with your research activity today.
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

        {{-- Statistics Cards --}}
        <div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">

            {{-- Active Plan --}}
            <div class="relative min-w-0 overflow-hidden rounded-[18px] border border-[#C9DEFF] bg-gradient-to-br from-white to-[#EDF5FF] p-5 shadow-[0_2px_5px_rgba(30,64,120,0.06)]">
                <div class="pointer-events-none absolute -bottom-10 -right-10 h-32 w-32 rounded-full bg-[#DCEBFF]/70"></div>

                <div class="relative">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-[10px] font-bold uppercase tracking-[0.04em] text-[#7184A5]">
                            Active Plan
                        </p>

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-[12px] bg-[#E0EDFF] text-[#3478FF]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <rect x="3" y="5" width="18" height="14" rx="3"/>
                                <path stroke-linecap="round" d="M3 10h18M7 15h4"/>
                            </svg>
                        </div>
                    </div>

                    <h2 class="mt-5 break-words text-[23px] font-bold tracking-[-0.5px] text-[#102243]">
                        {{ $activePlan?->name ?? 'No Active Plan' }}
                    </h2>

                    @if ($activeSubscription)
                        <p class="mt-2 text-[10px] leading-[1.6] text-[#7184A5]">
                            {{ $activeSubscription->is_demo
                                ? 'Demo subscription · No payment'
                                : 'Your subscription is active' }}
                        </p>
                    @else
                        <a href="{{ route('pricing') }}" class="mt-2 inline-flex items-center gap-1 text-[10px] font-bold text-[#3478FF] hover:underline">
                            Explore subscription plans
                            <span>→</span>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Remaining Respondents --}}
            <div class="relative min-w-0 overflow-hidden rounded-[18px] border border-[#D9D3FA] bg-gradient-to-br from-white to-[#F2EFFF] p-5 shadow-[0_2px_5px_rgba(30,64,120,0.06)]">
                <div class="pointer-events-none absolute -bottom-10 -right-10 h-32 w-32 rounded-full bg-[#E8E1FF]/70"></div>

                <div class="relative">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-[10px] font-bold uppercase tracking-[0.04em] text-[#7184A5]">
                            Remaining Respondents
                        </p>

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-[12px] bg-[#EAE4FF] text-[#7958E8]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 20v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9 10a4 4 0 100-8 4 4 0 000 8zM22 20v-2a4 4 0 00-3-3.87M16 2.13a4 4 0 010 7.75"/>
                            </svg>
                        </div>
                    </div>

                    <h2 class="mt-5 text-[23px] font-bold tracking-[-0.5px] text-[#102243]">
                        {{ $activeSubscription ? number_format($remainingRespondents) : '—' }}
                    </h2>

                    <p class="mt-2 text-[10px] leading-[1.6] text-[#7184A5]">
                        {{ $activeSubscription
                            ? number_format($grantedRespondents) . ' granted this period'
                            : 'Available with an active plan' }}
                    </p>

                    <div class="mt-4 h-[7px] overflow-hidden rounded-full bg-[#E4E5F2]">
                        <div
                            class="h-full rounded-full bg-[#7958E8] transition-all duration-500"
                            style="width: {{ $respondentsPercent }}%"
                        ></div>
                    </div>
                </div>
            </div>

            {{-- Subscription Expiration --}}
            <div class="relative min-w-0 overflow-hidden rounded-[18px] border border-[#BCEBD8] bg-gradient-to-br from-white to-[#EBFCF5] p-5 shadow-[0_2px_5px_rgba(30,64,120,0.06)]">
                <div class="pointer-events-none absolute -bottom-10 -right-10 h-32 w-32 rounded-full bg-[#D6F8E7]/70"></div>

                <div class="relative">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-[10px] font-bold uppercase tracking-[0.04em] text-[#7184A5]">
                            Subscription Expiration
                        </p>

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-[12px] bg-[#D8F8E9] text-[#0D9D6D]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <rect x="3" y="5" width="18" height="16" rx="2"/>
                                <path stroke-linecap="round" d="M7 3v4M17 3v4M3 10h18"/>
                            </svg>
                        </div>
                    </div>

                    <h2 class="mt-5 text-[21px] font-bold tracking-[-0.5px] text-[#102243]">
                        {{ $activeSubscription?->ends_at?->format('M d, Y') ?? '—' }}
                    </h2>

                    <p class="mt-2 text-[10px] leading-[1.6] text-[#7184A5]">
                        @if (!$activeSubscription)
                            No subscription information yet
                        @elseif ($cancellationScheduled)
                            Cancellation scheduled · No renewal
                        @else
                            Current subscription period end date
                        @endif
                    </p>
                </div>
            </div>

            {{-- Studies in Field --}}
            <div class="relative min-w-0 overflow-hidden rounded-[18px] border border-[#F3DFC1] bg-gradient-to-br from-white to-[#FFF8ED] p-5 shadow-[0_2px_5px_rgba(30,64,120,0.06)]">
                <div class="pointer-events-none absolute -bottom-10 -right-10 h-32 w-32 rounded-full bg-[#FFF0D7]/70"></div>

                <div class="relative">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-[10px] font-bold uppercase tracking-[0.04em] text-[#7184A5]">
                            Studies in Field
                        </p>

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-[12px] bg-[#FFF0D7] text-[#E49A26]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="18" height="18" rx="3"/>
                                <path stroke-linecap="round" d="M8 16v-4M12 16V8M16 16v-6"/>
                            </svg>
                        </div>
                    </div>

                    <h2 class="mt-5 text-[23px] font-bold tracking-[-0.5px] text-[#102243]">
                        {{ $inFieldCount }}
                    </h2>

                    <p class="mt-2 text-[10px] leading-[1.6] text-[#7184A5]">
                        {{ $totalStudies }} total research {{ $totalStudies === 1 ? 'request' : 'requests' }}
                    </p>
                </div>
            </div>

        </div>

        {{-- Subscription Management --}}
        @if ($activeSubscription)
            <section
                x-data="{ confirmCancel: false }"
                class="mt-5 overflow-hidden rounded-[19px] border border-[#DFE8F6] bg-white shadow-[0_2px_5px_rgba(30,64,120,0.06)]"
            >
                <div class="flex flex-wrap items-center justify-between gap-5 p-6">

                    <div class="flex min-w-0 flex-1 items-start gap-4">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-[13px] bg-[#EAF2FF] text-[#3478FF]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <rect x="3" y="5" width="18" height="14" rx="3"/>
                                <path stroke-linecap="round" d="M3 10h18M7 15h4"/>
                            </svg>
                        </div>

                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="text-[14px] font-bold text-[#102243]">
                                    Manage Subscription
                                </h2>

                                <span class="rounded-full px-2.5 py-1 text-[9px] font-bold {{ $cancellationScheduled ? 'bg-[#FFF3DC] text-[#C68B16]' : 'bg-[#E2F8EE] text-[#159866]' }}">
                                    {{ $cancellationScheduled ? 'Renewal Off' : 'Active' }}
                                </span>
                            </div>

                            <p class="mt-2 text-[11px] leading-[1.8] text-[#7184A5]">
                                @if ($cancellationScheduled)
                                    Your plan remains available until
                                    {{ $activeSubscription->ends_at?->format('M d, Y') ?? 'the end of your current period' }}.
                                    Automatic renewal is off.
                                @else
                                    You can turn off automatic renewal at any time.
                                    Your current period stays active until
                                    {{ $activeSubscription->ends_at?->format('M d, Y') ?? 'its expiration date' }}.
                                @endif
                            </p>
                        </div>
                    </div>

                    @if ($cancellationScheduled)
                        <form method="POST" action="{{ route('subscriptions.resume') }}">
                            @csrf

                            <input type="hidden" name="subscription_id" value="{{ $activeSubscription->id }}">

                            <button
                                type="submit"
                                class="inline-flex h-11 cursor-pointer items-center justify-center gap-2 rounded-[11px] bg-[#397CF6] px-5 text-[11px] font-bold text-white transition hover:bg-[#2869E3]"
                            >
                                Resume Subscription
                            </button>
                        </form>
                    @else
                        <button
                            type="button"
                            @click="confirmCancel = !confirmCancel"
                            class="inline-flex h-11 cursor-pointer items-center justify-center rounded-[11px] border border-[#F1C8CE] bg-[#FFF5F6] px-5 text-[11px] font-bold text-[#D9475A] transition hover:bg-[#FFE9ED]"
                        >
                            Cancel Subscription
                        </button>
                    @endif

                </div>

                {{-- Cancellation Confirmation --}}
                @if (!$cancellationScheduled)
                    <div
                        x-cloak
                        x-show="confirmCancel"
                        x-transition
                        class="border-t border-[#F4E2E5] bg-[#FFF9FA] p-6"
                    >
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-[12px] bg-[#FFE5E9] text-[#D9475A]">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="9"/>
                                    <path stroke-linecap="round" d="M12 7v6m0 4h.01"/>
                                </svg>
                            </div>

                            <div>
                                <h3 class="text-[13px] font-bold text-[#102243]">
                                    Turn off automatic renewal?
                                </h3>

                                <p class="mt-2 text-[11px] leading-[1.8] text-[#7184A5]">
                                    You will keep access until
                                    {{ $activeSubscription->ends_at?->format('M d, Y') ?? 'the end of your current period' }}.
                                    This does not request a refund.
                                </p>

                                <div class="mt-4 flex flex-wrap gap-3">
                                    <form method="POST" action="{{ route('subscriptions.cancellation-request') }}">
                                        @csrf

                                        <input type="hidden" name="subscription_id" value="{{ $activeSubscription->id }}">
                                        <input type="hidden" name="type" value="period_end">

                                        <button
                                            type="submit"
                                            class="h-10 cursor-pointer rounded-[10px] bg-[#D9475A] px-5 text-[11px] font-bold text-white transition hover:bg-[#BE3548]"
                                        >
                                            Yes, cancel renewal
                                        </button>
                                    </form>

                                    <button
                                        type="button"
                                        @click="confirmCancel = false"
                                        class="h-10 cursor-pointer rounded-[10px] border border-[#DCE5F3] bg-white px-5 text-[11px] font-semibold text-[#516581] transition hover:bg-[#F6F9FF]"
                                    >
                                        Keep subscription
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Early Refund Request --}}
                @if (
                    (int) ($activePlan?->duration_months ?? 0) > 1
                    && !$activeSubscription->is_demo
                )
                    <details class="group border-t border-[#EDF1F8] px-6 py-5">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-3 text-[11px] font-bold text-[#3478FF]">
                            <span>Request an early refund</span>

                            <svg class="h-4 w-4 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                            </svg>
                        </summary>

                        <p class="mt-4 text-[11px] leading-[1.8] text-[#7184A5]">
                            Early refunds require a separate review.
                            Submitting this request does not cancel your subscription
                            or issue a refund automatically.
                        </p>

                        <form
                            method="POST"
                            action="{{ route('subscriptions.cancellation-request') }}"
                            class="mt-4 space-y-3"
                        >
                            @csrf

                            <input type="hidden" name="subscription_id" value="{{ $activeSubscription->id }}">
                            <input type="hidden" name="type" value="early_refund">

                            <label for="refund-reason" class="block text-[11px] font-bold text-[#102243]">
                                Reason
                                <span class="font-normal text-[#8A9AB3]">(Optional)</span>
                            </label>

                            <textarea
                                id="refund-reason"
                                name="reason"
                                maxlength="2000"
                                rows="3"
                                placeholder="Tell us why you're requesting an early refund..."
                                class="w-full resize-y rounded-[11px] border border-[#DCE5F3] bg-[#FAFCFF] px-4 py-3 text-[12px] leading-[1.7] text-[#102243] outline-none transition placeholder:text-[#A3B0C2] focus:border-[#3478FF] focus:bg-white focus:ring-2 focus:ring-[#3478FF]/10"
                            >{{ old('reason') }}</textarea>

                            @error('reason')
                                <p class="text-[11px] text-[#D9475A]">
                                    {{ $message }}
                                </p>
                            @enderror

                            <button
                                type="submit"
                                class="inline-flex h-10 cursor-pointer items-center justify-center rounded-[10px] border border-[#DCE5F3] bg-white px-5 text-[11px] font-bold text-[#516581] transition hover:border-[#3478FF] hover:text-[#3478FF]"
                            >
                                Submit Refund Request
                            </button>
                        </form>
                    </details>
                @endif

            </section>
        @endif

        {{-- Lower Dashboard --}}
        <div class="mt-5 grid items-start gap-5 xl:grid-cols-[minmax(0,1fr)_310px]">

            {{-- Recent Studies --}}
            <section
                id="recent-studies"
                class="min-w-0 overflow-hidden rounded-[19px] border border-[#DFE8F6] bg-white shadow-[0_2px_5px_rgba(30,64,120,0.06)]"
            >

                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#EDF1F8] px-6 py-5">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-[13px] bg-[#EAF2FF] text-[#3478FF]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="18" height="18" rx="3"/>
                                <path stroke-linecap="round" d="M8 16v-4M12 16V8M16 16v-6"/>
                            </svg>
                        </div>

                        <div>
                            <h2 class="text-[14px] font-bold text-[#102243]">
                                Recent Studies
                            </h2>
                            <p class="mt-1 text-[11px] text-[#8A9AB3]">
                                Your latest research activity
                            </p>
                        </div>
                    </div>

                    <a
                        href="{{ route('my-studies.index') }}"
                        class="inline-flex items-center gap-1 text-[11px] font-bold text-[#3478FF] transition hover:text-[#2869E3]"
                    >
                        View all
                        <span>→</span>
                    </a>
                </div>

                @forelse ($studies as $study)
                    @php
                        $status = strtolower($study->status ?? 'submitted');

                        $statusLabel = $statusLabels[$status]
                            ?? strtoupper(str_replace('_', ' ', $status));

                        $statusClass = $statusStyles[$status]
                            ?? 'bg-[#F0F2F6] text-[#7B879B]';

                        $statusDot = $statusDots[$status]
                            ?? 'bg-[#7B879B]';

                        $studyNumber = $study->study_number;
                        $publicRequestCode = $study->public_request_code;
                    @endphp

                    <a
                        href="{{ route('my-studies.show', $study) }}"
                        class="group flex flex-wrap items-center justify-between gap-4 border-b border-[#EDF1F8] px-6 py-5 transition hover:bg-[#F8FBFF] last:border-b-0"
                    >
                        <div class="flex min-w-0 flex-1 items-start gap-4">

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-[12px] bg-[#EEF4FF] text-[#3478FF] transition group-hover:bg-[#E1EDFF]">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <rect x="4" y="4" width="16" height="16" rx="3"/>
                                    <path stroke-linecap="round" d="M8 9h8M8 13h8M8 17h5"/>
                                </svg>
                            </div>

                            <div class="min-w-0 flex-1">
                                <h3 class="break-words text-[13px] font-bold text-[#102243] transition group-hover:text-[#3478FF]">
                                    {{ $study->goal }}
                                </h3>

                                <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1.5 text-[10px] text-[#7184A5]">
                                    <span class="font-mono font-semibold text-[#516581]">
                                        Study {{ $studyNumber }}
                                    </span>

                                    <span class="hidden h-1 w-1 rounded-full bg-[#B5C2D5] sm:block"></span>

                                    <span>
                                        {{ number_format((int) $study->sample_size) }} respondents
                                    </span>
                                </div>

                                <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1.5 text-[10px] text-[#91A0B6]">
                                    <span class="break-all font-mono">
                                        {{ $publicRequestCode }}
                                    </span>

                                    <span class="hidden h-1 w-1 rounded-full bg-[#C4CEDD] sm:block"></span>

                                    <span>{{ $study->country }}</span>

                                    <span class="hidden h-1 w-1 rounded-full bg-[#C4CEDD] sm:block"></span>

                                    <span>{{ $study->created_at?->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex shrink-0 items-center gap-3">
                            <span class="inline-flex items-center gap-2 rounded-full px-3 py-2 text-[9px] font-bold {{ $statusClass }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>
                                {{ $statusLabel }}
                            </span>

                            <svg class="h-4 w-4 text-[#9BAAC1] transition group-hover:translate-x-1 group-hover:text-[#3478FF]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6l6 6-6 6"/>
                            </svg>
                        </div>
                    </a>

                @empty

                    {{-- Empty State --}}
                    <div class="flex min-h-[310px] flex-col items-center justify-center px-6 py-12 text-center">

                        <div class="flex h-16 w-16 items-center justify-center rounded-[18px] bg-[#EAF2FF] text-[#3478FF]">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="18" height="18" rx="3"/>
                                <path stroke-linecap="round" d="M8 16v-4M12 16V8M16 16v-6"/>
                            </svg>
                        </div>

                        <h3 class="mt-5 text-[15px] font-bold text-[#102243]">
                            Your research journey starts here
                        </h3>

                        <p class="mt-3 max-w-[340px] text-[11px] leading-[1.8] text-[#7184A5]">
                            You haven't created any research studies yet.
                            Start your first study to explore customer behavior
                            and gain valuable insights.
                        </p>

                        <a
                            href="{{ route('home') }}#start-study"
                            class="mt-5 inline-flex h-11 items-center justify-center gap-2 rounded-[11px] bg-[#397CF6] px-5 text-[11px] font-bold text-white transition hover:bg-[#2869E3]"
                        >
                            <span class="text-[16px]">+</span>
                            Create Your First Study
                        </a>

                    </div>

                @endforelse

            </section>

            {{-- Quick Profile --}}
            <aside class="min-w-0 overflow-hidden rounded-[19px] border border-[#DFE8F6] bg-white shadow-[0_2px_5px_rgba(30,64,120,0.06)]">

                <div class="flex items-center gap-3 border-b border-[#EDF1F8] px-5 py-5">
                    <div class="flex h-11 w-11 items-center justify-center rounded-[13px] bg-[#EDEAFF] text-[#7557E9]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <circle cx="12" cy="8" r="4"/>
                            <path stroke-linecap="round" d="M4 21v-2a8 8 0 0116 0v2"/>
                        </svg>
                    </div>

                    <div>
                        <h2 class="text-[14px] font-bold text-[#102243]">
                            Quick Profile
                        </h2>
                        <p class="mt-1 text-[11px] text-[#8A9AB3]">
                            Your account at a glance
                        </p>
                    </div>
                </div>

                <div class="p-5">

                    <div class="rounded-[14px] bg-gradient-to-r from-[#F0F5FF] to-[#F9FBFF] p-4">
                        <div class="flex items-center gap-3">

                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-[#DDEAFF] text-[14px] font-bold text-[#3478FF]">
                                {{ $initials ?: 'U' }}
                            </div>

                            <div class="min-w-0">
                                <p class="break-words text-[12px] font-bold text-[#102243]">
                                    {{ $user->name }}
                                </p>

                                <p class="mt-1 break-all text-[10px] text-[#7184A5]">
                                    {{ $user->email }}
                                </p>
                            </div>

                        </div>
                    </div>

                    <div class="mt-5 space-y-4">

                        <div class="flex items-start justify-between gap-3 border-b border-[#EDF1F8] pb-4">
                            <span class="text-[11px] text-[#7184A5]">
                                Company
                            </span>

                            <strong class="max-w-[60%] break-words text-right text-[11px] font-bold text-[#102243]">
                                {{ $user->company_name
                                    ?: ($accountType === 'company' ? 'Not specified' : '—') }}
                            </strong>
                        </div>

                        <div class="flex items-center justify-between gap-3 border-b border-[#EDF1F8] pb-4">
                            <span class="text-[11px] text-[#7184A5]">
                                Account Type
                            </span>

                            <span class="rounded-full bg-[#EAF2FF] px-3 py-1.5 text-[10px] font-bold text-[#3478FF]">
                                {{ $accountTypeLabel }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-3">
                            <span class="text-[11px] text-[#7184A5]">
                                Member Since
                            </span>

                            <strong class="text-[11px] font-bold text-[#102243]">
                                {{ $user->created_at?->format('M Y') ?? '—' }}
                            </strong>
                        </div>

                    </div>

                    <a
                        href="{{ route('profile-settings') }}"
                        class="mt-6 flex h-11 w-full items-center justify-center gap-2 rounded-[11px] border border-[#DCE5F3] bg-[#F8FAFE] text-[11px] font-bold text-[#516581] transition hover:border-[#3478FF] hover:bg-[#EFF5FF] hover:text-[#3478FF]"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <circle cx="12" cy="8" r="4"/>
                            <path stroke-linecap="round" d="M4 21v-2a8 8 0 0116 0v2"/>
                        </svg>
                        Edit Profile
                    </a>

                </div>

            </aside>

        </div>

        {{-- Footer --}}
        <div class="mt-8 flex flex-wrap items-center justify-between gap-3 border-t border-[#DCE7F5] pt-5">
            <p class="text-[10px] text-[#91A0B6]">
                Impala Research · Your Research Workspace
            </p>

            <p class="text-[10px] text-[#91A0B6]">
                Dashboard Overview
            </p>
        </div>

    </main>
</div>
@endsection
