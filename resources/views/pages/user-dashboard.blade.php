
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

    $remainingRespondents =
        $activeSubscription?->remaining_respondents ?? 0;

    $grantedRespondents =
        $activeSubscription?->respondents_granted ?? 0;

    $respondentsPercent = $grantedRespondents > 0
        ? min(
            100,
            max(
                0,
                ($remainingRespondents / $grantedRespondents) * 100
            )
        )
        : 0;

    $cancellationScheduled =
        $activeSubscription?->cancel_at !== null;

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
        'submitted' => 'bg-[#E7EFFF] text-[#3775D6]',
        'pending' => 'bg-[#FFF3D8] text-[#C37B0A]',
        'in_progress' => 'bg-[#E0F4E5] text-[#248743]',
        'in_field' => 'bg-[#E0F4E5] text-[#248743]',
        'review' => 'bg-[#FFF3D8] text-[#C37B0A]',
        'under_review' => 'bg-[#FFF3D8] text-[#C37B0A]',
        'completed' => 'bg-[#E7EFFF] text-[#3775D6]',
        'rejected' => 'bg-[#FCE8E8] text-[#C34444]',
        'cancelled' => 'bg-[#F1F1F1] text-[#777777]',
    ];
@endphp

<div class="flex min-h-screen w-full bg-white">

    @include('partials.user-sidebar')

    <main class="min-h-screen min-w-0 flex-1 bg-gradient-to-br from-[#DCEAFF] via-[#F7FAFF] to-white px-[30px] py-[46px] lg:px-[42px]">

        {{-- Success Message --}}

        @if (session('success'))
            <div class="mb-[25px] rounded-[8px] border border-[#B8DFC4] bg-[#E8F7EC] px-[18px] py-[14px] text-[12px] text-[#248743]">
                {{ session('success') }}
            </div>
        @endif

        {{-- Error Message --}}

        @if (session('error'))
            <div class="mb-[25px] rounded-[8px] border border-[#E9B9B9] bg-[#FCE8E8] px-[18px] py-[14px] text-[12px] text-[#A83232]">
                {{ session('error') }}
            </div>
        @endif

        {{-- Header --}}

        <div class="flex flex-wrap items-start justify-between gap-4">

            <div>
                <h1 class="text-[25px] font-medium tracking-[-0.6px] text-[#171717]">
                    Welcome back, {{ $firstName }}
                </h1>

                <p class="mt-[6px] text-[12px] text-[#666666]">
                    Here's an overview of your research activity.
                </p>
            </div>

            <a
                href="{{ route('home') }}#start-study"
                class="inline-flex h-[42px] items-center gap-[8px] rounded-[7px] bg-[#4D7FCF] px-[18px] text-[12px] font-medium text-white transition hover:bg-[#416FB4]"
            >
                <span class="text-[18px] font-light">+</span>
                Create New Study
            </a>

        </div>

        {{-- Statistics --}}

        <div class="mt-[34px] grid gap-[14px] sm:grid-cols-2 xl:grid-cols-4">

            {{-- Active Plan --}}

            <div class="rounded-[15px] border border-[#BFBFBF] bg-white px-[20px] py-[21px]">

                <p class="text-[10px] uppercase text-[#666666]">
                    Active Plan
                </p>

                <h2 class="mt-[20px] text-[23px] font-medium text-[#171717]">
                    {{ $activePlan?->name ?? 'No Active Plan' }}
                </h2>

                @if ($activeSubscription)

                    <p class="mt-[8px] text-[10px] text-[#777777]">
                        {{ $activeSubscription->is_demo
                            ? 'Demo subscription · No payment'
                            : 'Active subscription' }}
                    </p>

                @else

                    <a
                        href="{{ route('pricing') }}"
                        class="mt-[8px] inline-block text-[10px] text-[#4D7FCF] hover:underline"
                    >
                        Explore subscription plans
                    </a>

                @endif

            </div>

            {{-- Remaining Respondents --}}

            <div class="rounded-[15px] border border-[#BFBFBF] bg-white px-[20px] py-[21px]">

                <p class="text-[10px] uppercase text-[#666666]">
                    Remaining Respondents
                </p>

                <div class="mt-[20px] flex items-end gap-1">

                    <span class="text-[23px] font-medium text-[#171717]">
                        {{ $activeSubscription
                            ? number_format($remainingRespondents)
                            : '—' }}
                    </span>

                </div>

                <p class="mt-[8px] text-[10px] text-[#777777]">
                    {{ $activeSubscription
                        ? number_format($grantedRespondents) . ' granted this period'
                        : 'Available with an active plan' }}
                </p>

                <div class="mt-[18px] h-[6px] overflow-hidden rounded-full bg-[#E4EAF2]">

                    <div
                        class="h-full rounded-full bg-[#4D7FCF]"
                        style="width: {{ $respondentsPercent }}%"
                    ></div>

                </div>

            </div>

            {{-- Subscription Expiration --}}

            <div class="rounded-[15px] border border-[#BFBFBF] bg-white px-[20px] py-[21px]">

                <p class="text-[10px] uppercase text-[#666666]">
                    Subscription Expiration
                </p>

                <h2 class="mt-[20px] text-[23px] font-medium text-[#171717]">
                    {{ $activeSubscription?->ends_at?->format('M d, Y') ?? '—' }}
                </h2>

                <p class="mt-[8px] text-[10px] text-[#777777]">

                    @if (!$activeSubscription)

                        No subscription information yet

                    @elseif ($cancellationScheduled)

                        Cancellation scheduled · No renewal

                    @else

                        Current subscription period ends on this date

                    @endif

                </p>

            </div>

            {{-- Studies in Field --}}

            <div class="rounded-[15px] border border-[#BFBFBF] bg-white px-[20px] py-[21px]">

                <p class="text-[10px] uppercase text-[#666666]">
                    Studies in Field
                </p>

                <h2 class="mt-[20px] text-[23px] font-medium text-[#171717]">
                    {{ $inFieldCount }}
                </h2>

                <p class="mt-[8px] text-[10px] text-[#777777]">
                    {{ $totalStudies }}
                    total research
                    {{ $totalStudies === 1 ? 'request' : 'requests' }}
                </p>

            </div>

        </div>

        {{-- Subscription Management --}}

        @if ($activeSubscription)

            <section
                class="mt-[16px] rounded-[12px] border border-[#BFC3C9] bg-white px-[22px] py-[20px]"
                x-data="{ confirmCancel: false }"
            >

                <div class="flex flex-wrap items-center justify-between gap-4">

                    <div>

                        <h2 class="text-[13px] font-medium text-[#192231]">
                            Manage Subscription
                        </h2>

                        <p class="mt-[7px] text-[11px] leading-[1.7] text-[#777777]">

                            @if ($cancellationScheduled)

                                Your plan remains available until
                                {{ $activeSubscription->ends_at->format('M d, Y') }}.
                                Automatic renewal is off.

                            @else

                                You can turn off automatic renewal at any time.
                                Your current period stays active until
                                {{ $activeSubscription->ends_at->format('M d, Y') }}.

                            @endif

                        </p>

                    </div>

                    {{-- Resume Subscription --}}

                    @if ($cancellationScheduled)

                        <form
                            method="POST"
                            action="{{ route('subscriptions.resume') }}"
                        >
                            @csrf

                            <input
                                type="hidden"
                                name="subscription_id"
                                value="{{ $activeSubscription->id }}"
                            >

                            <button
                                type="submit"
                                class="inline-flex h-[38px] items-center justify-center rounded-[7px] bg-[#4D7FCF] px-[17px] text-[11px] font-medium text-white transition hover:bg-[#416FB4]"
                            >
                                Resume Subscription
                            </button>

                        </form>

                    @else

                        {{-- Cancel Subscription --}}

                        <button
                            type="button"
                            @click="confirmCancel = true"
                            class="inline-flex h-[38px] items-center justify-center rounded-[7px] border border-[#D4A5A5] px-[17px] text-[11px] font-medium text-[#B34747] transition hover:bg-[#FFF3F3]"
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
                        class="mt-[18px] rounded-[9px] border border-[#E5CACA] bg-[#FFF8F8] p-[16px]"
                    >

                        <p class="text-[12px] font-medium text-[#192231]">
                            Turn off automatic renewal?
                        </p>

                        <p class="mt-[6px] text-[11px] leading-[1.7] text-[#666666]">
                            You will keep access until
                            {{ $activeSubscription->ends_at->format('M d, Y') }}.
                            This does not request a refund.
                        </p>

                        <div class="mt-[14px] flex flex-wrap gap-[9px]">

                            <form
                                method="POST"
                                action="{{ route('subscriptions.cancellation-request') }}"
                            >
                                @csrf

                                <input
                                    type="hidden"
                                    name="subscription_id"
                                    value="{{ $activeSubscription->id }}"
                                >

                                <input
                                    type="hidden"
                                    name="type"
                                    value="period_end"
                                >

                                <button
                                    type="submit"
                                    class="rounded-[7px] bg-[#B34747] px-[16px] py-[10px] text-[11px] font-medium text-white hover:bg-[#963B3B]"
                                >
                                    Yes, cancel renewal
                                </button>

                            </form>

                            <button
                                type="button"
                                @click="confirmCancel = false"
                                class="rounded-[7px] border border-[#C5C5C5] px-[16px] py-[10px] text-[11px] hover:bg-white"
                            >
                                Keep subscription
                            </button>

                        </div>

                    </div>

                @endif

                {{-- Early Refund Request --}}

                @if (
                    (int) ($activePlan?->duration_months ?? 0) > 1
                    && !$activeSubscription->is_demo
                )

                    <details class="mt-[18px] border-t border-[#E9E9E9] pt-[16px]">

                        <summary class="cursor-pointer text-[11px] font-medium text-[#4D7FCF]">
                            Request an early refund
                        </summary>

                        <p class="mt-[8px] text-[11px] leading-[1.7] text-[#777777]">
                            Early refunds require a separate review.
                            Submitting this request does not cancel your
                            subscription or issue a refund automatically.
                        </p>

                        <form
                            method="POST"
                            action="{{ route('subscriptions.cancellation-request') }}"
                            class="mt-[12px] space-y-[10px]"
                        >
                            @csrf

                            <input
                                type="hidden"
                                name="subscription_id"
                                value="{{ $activeSubscription->id }}"
                            >

                            <input
                                type="hidden"
                                name="type"
                                value="early_refund"
                            >

                            <label
                                for="refund-reason"
                                class="block text-[11px] text-[#555555]"
                            >
                                Reason (optional)
                            </label>

                            <textarea
                                id="refund-reason"
                                name="reason"
                                maxlength="2000"
                                rows="3"
                                class="w-full rounded-[7px] border border-[#C5C5C5] px-[12px] py-[10px] text-[12px] focus:border-[#4D7FCF] focus:outline-none"
                            >{{ old('reason') }}</textarea>

                            @error('reason')
                                <p class="text-[11px] text-[#B34747]">
                                    {{ $message }}
                                </p>
                            @enderror

                            <button
                                type="submit"
                                class="rounded-[7px] border border-[#C5C5C5] px-[16px] py-[10px] text-[11px] font-medium hover:border-[#4D7FCF] hover:text-[#4D7FCF]"
                            >
                                Submit refund request
                            </button>

                        </form>

                    </details>

                @endif

            </section>

        @endif

        {{-- Lower Section --}}

        <div class="mt-[16px] grid gap-[16px] xl:grid-cols-[1fr_260px]">

            {{-- Recent Studies --}}

            <section
                id="recent-studies"
                class="min-w-0 overflow-hidden rounded-[12px] border border-[#BFC3C9] bg-white"
            >

                <div class="flex min-h-[50px] items-center justify-between gap-3 border-b border-[#E3E3E3] px-[22px]">

                    <h2 class="text-[13px] font-medium">
                        Recent Studies
                    </h2>

                    <a
                        href="{{ route('my-studies.index') }}"
                        class="text-[11px] text-[#4D7FCF] hover:underline"
                    >
                        View all
                    </a>

                </div>

                @forelse ($studies as $study)

                    @php
                        $status = strtolower($study->status ?? 'submitted');

                        $statusLabel = $statusLabels[$status]
                            ?? strtoupper(str_replace('_', ' ', $status));

                        $statusClass = $statusStyles[$status]
                            ?? 'bg-[#F1F1F1] text-[#777777]';

                        $studyCode = 'REQ-' . str_pad(
                            (string) $study->id,
                            4,
                            '0',
                            STR_PAD_LEFT
                        );
                    @endphp

                    <a
                        href="{{ route('my-studies.show', $study) }}"
                        class="flex min-h-[85px] flex-wrap items-center justify-between gap-3 border-b border-[#E9E9E9] px-[22px] py-[15px] transition hover:bg-[#F7F9FD] last:border-b-0"
                    >

                        <div class="min-w-0 flex-1">

                            <h3 class="break-words text-[13px] font-medium text-[#192231]">
                                {{ $study->goal }}
                            </h3>

                            <p class="mt-[6px] text-[11px] tracking-[0.02em] text-[#555555]">

                                <span class="font-mono">
                                    ID {{ $studyCode }}
                                </span>

                                <span class="mx-[6px]">•</span>

                                {{ $study->sample_size }} RESPONDENTS

                            </p>

                            <p class="mt-[5px] text-[10px] text-[#888888]">

                                {{ $study->country }}

                                <span class="mx-[5px]">•</span>

                                {{ $study->created_at?->format('M d, Y') }}

                            </p>

                        </div>

                        <span class="shrink-0 rounded-full px-[14px] py-[6px] text-[9px] font-medium {{ $statusClass }}">
                            ● &nbsp; {{ $statusLabel }}
                        </span>

                    </a>

                @empty

                    {{-- Empty State --}}

                    <div class="flex min-h-[290px] flex-col items-center justify-center px-[24px] py-[40px] text-center">

                        <div class="flex h-[56px] w-[56px] items-center justify-center rounded-full bg-[#EDF3FF]">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="25"
                                height="25"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="#4D7FCF"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <path d="M8 16V12"/>
                                <path d="M12 16V8"/>
                                <path d="M16 16V10"/>
                            </svg>

                        </div>

                        <h3 class="mt-[18px] text-[14px] font-medium text-[#192231]">
                            Your research journey starts here
                        </h3>

                        <p class="mt-[9px] max-w-[330px] text-[11px] leading-[1.7] text-[#777777]">
                            You haven't created any research studies yet.
                            Start your first study to explore customer behavior
                            and gain valuable insights.
                        </p>

                        <a
                            href="{{ route('home') }}#start-study"
                            class="mt-[20px] inline-flex h-[37px] items-center justify-center rounded-[6px] bg-[#4D7FCF] px-[17px] text-[11px] font-medium text-white transition hover:bg-[#416FB4]"
                        >
                            + Create Your First Study
                        </a>

                    </div>

                @endforelse

            </section>

            {{-- Quick Profile --}}

            <aside class="rounded-[12px] border border-[#BFC3C9] bg-white">

                <div class="flex h-[50px] items-center border-b border-[#E3E3E3] px-[20px]">

                    <h2 class="text-[13px] font-medium">
                        Quick Profile
                    </h2>

                </div>

                <div class="p-[20px]">

                    <div class="flex items-center gap-[14px]">

                        <div class="flex h-[44px] w-[44px] shrink-0 items-center justify-center rounded-full bg-[#262B34] text-[15px] text-white">
                            {{ $initials ?: 'U' }}
                        </div>

                        <div class="min-w-0">

                            <p class="truncate text-[13px] font-semibold text-[#171717]">
                                {{ $user->name }}
                            </p>

                            <p class="break-all text-[10px] text-[#666666]">
                                {{ $user->email }}
                            </p>

                        </div>

                    </div>

                    <div class="mt-[28px] space-y-[24px] text-[12px]">

                        <div class="flex items-center justify-between gap-3">

                            <span class="text-[#555555]">
                                Company
                            </span>

                            <strong class="text-right font-medium text-[#171717]">
                                {{ $user->company_name
                                    ?: ($accountType === 'company'
                                        ? 'Not specified'
                                        : '—') }}
                            </strong>

                        </div>

                        <div class="flex items-center justify-between gap-3">

                            <span class="text-[#555555]">
                                Account type
                            </span>

                            <strong class="font-medium text-[#171717]">
                                {{ $accountTypeLabel }}
                            </strong>

                        </div>

                        <div class="flex items-center justify-between gap-3">

                            <span class="text-[#555555]">
                                Member since
                            </span>

                            <strong class="font-medium text-[#171717]">
                                {{ $user->created_at?->format('M Y') ?? '—' }}
                            </strong>

                        </div>

                    </div>

                    <a
                        href="{{ route('profile-settings') }}"
                        class="mt-[30px] flex h-[38px] w-full items-center justify-center rounded-[7px] border border-[#C5C5C5] text-[11px] transition hover:border-[#4D7FCF] hover:text-[#4D7FCF]"
                    >
                        Edit profile
                    </a>

                </div>

            </aside>

        </div>

    </main>

</div>

@endsection
