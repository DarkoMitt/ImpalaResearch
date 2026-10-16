
@extends('layouts.app')

@section('content')

<div class="flex min-h-screen w-full bg-white">

    @include('partials.admin-sidebar')

    <main class="min-h-screen min-w-0 flex-1 bg-gradient-to-br from-[#DCEAFF] via-[#F7FAFF] to-white px-[26px] py-[42px] lg:px-[34px]">

        @php
            $nameParts = preg_split('/\s+/', trim($user->name ?? ''));

            $initials = collect($nameParts)
                ->filter()
                ->take(2)
                ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
                ->implode('');

            $isAdmin = $user->role === 'admin';
            $isCompany = $user->account_type === 'company';

            $statusLabels = [
                'active' => 'Active',
                'expired' => 'Expired',
                'cancelled' => 'Cancelled',
                'canceled' => 'Cancelled',
                'past_due' => 'Past Due',
                'incomplete' => 'Incomplete',
                'trialing' => 'Trialing',
                'unpaid' => 'Unpaid',
                'paused' => 'Paused',
                'none' => 'No Subscription',
            ];

            $statusColors = [
                'active' => 'bg-[#E0F4E5] text-[#248743]',
                'expired' => 'bg-[#F0F0F0] text-[#666666]',
                'cancelled' => 'bg-[#FCE8E8] text-[#C34444]',
                'canceled' => 'bg-[#FCE8E8] text-[#C34444]',
                'past_due' => 'bg-[#FFF3D8] text-[#C37B0A]',
                'unpaid' => 'bg-[#FFF3D8] text-[#C37B0A]',
                'none' => 'bg-[#F0F0F0] text-[#777777]',
            ];

            $subscriptionStatusLabel = $statusLabels[$subscriptionStatus]
                ?? ucwords(str_replace('_', ' ', $subscriptionStatus));

            $subscriptionStatusClass = $statusColors[$subscriptionStatus]
                ?? 'bg-[#E7EFFF] text-[#3775D6]';
        @endphp

        {{-- Breadcrumb and Header --}}

        <div class="flex flex-wrap items-start justify-between gap-4">

            <div>

                <div class="flex flex-wrap items-center gap-[8px] text-[11px] text-[#777777]">

                    <a href="{{ route('admin.dashboard') }}" class="hover:text-[#4D7FCF]">
                        Admin
                    </a>

                    <span>/</span>

                    <a href="{{ route('admin.users') }}" class="hover:text-[#4D7FCF]">
                        Users
                    </a>

                    <span>/</span>

                    <span class="font-medium text-[#26354B]">
                        User Details
                    </span>

                </div>

                <h1 class="mt-[14px] text-[25px] font-medium tracking-[-0.6px] text-[#171717]">
                    User Details
                </h1>

                <p class="mt-[6px] text-[12px] text-[#666666]">
                    Account information, subscriptions and research activity.
                </p>

            </div>

            <a
                href="{{ route('admin.users') }}"
                class="inline-flex h-[38px] items-center justify-center rounded-[7px] border border-[#C5C5C5] bg-white px-[16px] text-[11px] text-[#555555] transition hover:border-[#4D7FCF] hover:text-[#4D7FCF]"
            >
                ← Back to Users
            </a>

        </div>

        {{-- User Profile --}}

        <section class="mt-[30px] rounded-[14px] border border-[#BFC3C9] bg-white p-[22px]">

            <div class="flex flex-wrap items-start justify-between gap-4">

                <div class="flex min-w-0 items-center gap-[16px]">

                    <div class="flex h-[56px] w-[56px] shrink-0 items-center justify-center rounded-[12px] bg-[#E9EDFF] text-[17px] font-medium text-[#4D7FCF]">
                        {{ $initials ?: 'U' }}
                    </div>

                    <div class="min-w-0">

                        <h2 class="break-words text-[18px] font-medium text-[#202939]">
                            {{ $user->name }}
                        </h2>

                        <p class="mt-[5px] break-all text-[12px] text-[#666666]">
                            {{ $user->email }}
                        </p>

                        <p class="mt-[7px] font-mono text-[10px] text-[#999999]">
                            User ID #{{ $user->id }}
                        </p>

                    </div>

                </div>

                <div class="flex flex-wrap items-center gap-[8px]">

                    <span class="rounded-full bg-[#F0F0F0] px-[12px] py-[6px] text-[10px] text-[#555555]">
                        {{ $isCompany ? 'Company' : 'Individual' }}
                    </span>

                    <span class="rounded-full px-[12px] py-[6px] text-[10px] font-medium {{ $isAdmin ? 'bg-[#E7EFFF] text-[#3775D6]' : 'bg-[#EEF5EE] text-[#35824B]' }}">
                        ● {{ $isAdmin ? 'Admin' : 'User' }}
                    </span>

                </div>

            </div>

            <div class="mt-[22px] grid gap-[14px] border-t border-[#EAEAEA] pt-[20px] sm:grid-cols-2 xl:grid-cols-4">

                <div>
                    <p class="text-[10px] uppercase text-[#888888]">Full Name</p>
                    <p class="mt-[7px] break-words text-[12px] font-medium text-[#26354B]">
                        {{ $user->name }}
                    </p>
                </div>

                <div>
                    <p class="text-[10px] uppercase text-[#888888]">Company</p>
                    <p class="mt-[7px] break-words text-[12px] font-medium text-[#26354B]">
                        {{ $user->company_name ?: '—' }}
                    </p>
                </div>

                <div>
                    <p class="text-[10px] uppercase text-[#888888]">Email Verification</p>
                    <p class="mt-[7px] text-[12px] font-medium {{ $user->email_verified_at ? 'text-[#248743]' : 'text-[#C37B0A]' }}">
                        {{ $user->email_verified_at ? 'Verified' : 'Not Verified' }}
                    </p>
                </div>

                <div>
                    <p class="text-[10px] uppercase text-[#888888]">Joined</p>
                    <p class="mt-[7px] text-[12px] font-medium text-[#26354B]">
                        {{ $user->created_at?->format('M d, Y') ?? '—' }}
                    </p>
                </div>

            </div>

        </section>

        {{-- Summary Cards --}}

        <div class="mt-[18px] grid gap-[12px] sm:grid-cols-2 xl:grid-cols-4">

            <div class="rounded-[14px] border border-[#BFC3C9] bg-white px-[20px] py-[20px]">

                <p class="text-[10px] uppercase text-[#666666]">
                    Subscription Plan
                </p>

                <h3 class="mt-[17px] break-words text-[19px] font-medium text-[#202939]">
                    {{ $displaySubscription?->plan?->name ?? 'No Plan' }}
                </h3>

                <span class="mt-[12px] inline-flex rounded-full px-[10px] py-[5px] text-[10px] font-medium {{ $subscriptionStatusClass }}">
                    ● {{ $subscriptionStatusLabel }}
                </span>

            </div>

            <div class="rounded-[14px] border border-[#BFC3C9] bg-white px-[20px] py-[20px]">

                <p class="text-[10px] uppercase text-[#666666]">
                    Available Respondents
                </p>

                <h3 class="mt-[17px] text-[22px] font-medium text-[#248743]">
                    {{ number_format($respondentsRemaining) }}
                </h3>

                <p class="mt-[10px] text-[10px] text-[#777777]">
                    {{ $activeSubscription ? 'Ready for new studies' : 'No active subscription' }}
                </p>

            </div>

            <div class="rounded-[14px] border border-[#BFC3C9] bg-white px-[20px] py-[20px]">

                <p class="text-[10px] uppercase text-[#666666]">
                    Respondents Used
                </p>

                <h3 class="mt-[17px] text-[22px] font-medium text-[#202939]">
                    {{ number_format($respondentsUsed) }}
                </h3>

                <p class="mt-[10px] text-[10px] text-[#777777]">
                    {{ number_format($respondentsGranted) }} granted in current plan
                </p>

            </div>

            <div class="rounded-[14px] border border-[#BFC3C9] bg-white px-[20px] py-[20px]">

                <p class="text-[10px] uppercase text-[#666666]">
                    Total Studies
                </p>

                <h3 class="mt-[17px] text-[22px] font-medium text-[#202939]">
                    {{ number_format((int) ($studyStats?->total ?? 0)) }}
                </h3>

                <p class="mt-[10px] text-[10px] text-[#777777]">
                    All submitted research studies
                </p>

            </div>

        </div>

        {{-- Subscription and Respondent Usage --}}

        <div class="mt-[18px] grid gap-[14px] xl:grid-cols-2">

            {{-- Subscription Details --}}

            <section class="rounded-[14px] border border-[#BFC3C9] bg-white p-[22px]">

                <h2 class="text-[14px] font-medium text-[#202939]">
                    Subscription Details
                </h2>

                <p class="mt-[5px] text-[11px] text-[#777777]">
                    Current subscription information.
                </p>

                <div class="mt-[22px] space-y-[16px]">

                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <span class="text-[11px] text-[#777777]">Plan</span>
                        <span class="text-[12px] font-medium text-[#26354B]">
                            {{ $displaySubscription?->plan?->name ?? 'No Plan' }}
                        </span>
                    </div>

                    <div class="border-t border-[#EAEAEA] pt-[16px]">

                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <span class="text-[11px] text-[#777777]">Status</span>
                            <span class="rounded-full px-[11px] py-[5px] text-[10px] font-medium {{ $subscriptionStatusClass }}">
                                ● {{ $subscriptionStatusLabel }}
                            </span>
                        </div>

                    </div>

                    <div class="border-t border-[#EAEAEA] pt-[16px]">

                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <span class="text-[11px] text-[#777777]">Start Date</span>
                            <span class="text-[12px] text-[#26354B]">
                                {{ $displaySubscription?->starts_at?->format('M d, Y') ?? '—' }}
                            </span>
                        </div>

                    </div>

                    <div class="border-t border-[#EAEAEA] pt-[16px]">

                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <span class="text-[11px] text-[#777777]">Expiration Date</span>
                            <span class="text-[12px] text-[#26354B]">
                                {{ $displaySubscription?->ends_at?->format('M d, Y') ?? '—' }}
                            </span>
                        </div>

                    </div>

                    <div class="border-t border-[#EAEAEA] pt-[16px]">

                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <span class="text-[11px] text-[#777777]">Subscription Type</span>
                            <span class="text-[12px] text-[#26354B]">
                                @if (!$displaySubscription)
                                    —
                                @elseif ($displaySubscription->is_demo)
                                    Demo
                                @else
                                    Paid
                                @endif
                            </span>
                        </div>

                    </div>

                    @if ($displaySubscription?->cancel_at)

                        <div class="border-t border-[#EAEAEA] pt-[16px]">

                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <span class="text-[11px] text-[#777777]">
                                    Scheduled Cancellation
                                </span>

                                <span class="text-[12px] text-[#C37B0A]">
                                    {{ $displaySubscription->cancel_at->format('M d, Y') }}
                                </span>
                            </div>

                        </div>

                    @endif

                </div>

            </section>

            {{-- Respondent Allocation --}}

            <section class="rounded-[14px] border border-[#BFC3C9] bg-white p-[22px]">

                <h2 class="text-[14px] font-medium text-[#202939]">
                    Respondent Allocation
                </h2>

                <p class="mt-[5px] text-[11px] text-[#777777]">
                    Available respondents from the active subscription.
                </p>

                <div class="mt-[28px] flex flex-wrap items-end justify-between gap-3">

                    <div>

                        <p class="text-[10px] text-[#777777]">
                            Respondents Used
                        </p>

                        <p class="mt-[8px] text-[23px] font-medium text-[#202939]">
                            {{ number_format($respondentsUsed) }}
                            <span class="text-[13px] font-normal text-[#888888]">
                                / {{ number_format($respondentsGranted) }}
                            </span>
                        </p>

                    </div>

                    <span class="text-[12px] font-medium text-[#4D7FCF]">
                        {{ $respondentUsagePercentage }}% used
                    </span>

                </div>

                <div class="mt-[16px] h-[9px] overflow-hidden rounded-full bg-[#E6ECF5]">

                    <div
                        class="h-full rounded-full bg-[#4D7FCF] transition-all"
                        style="width: {{ $respondentUsagePercentage }}%"
                    ></div>

                </div>

                <div class="mt-[28px] grid grid-cols-2 gap-[12px]">

                    <div class="rounded-[10px] bg-[#F4F7FC] p-[16px]">

                        <p class="text-[10px] text-[#777777]">
                            Granted
                        </p>

                        <p class="mt-[9px] text-[19px] font-medium text-[#26354B]">
                            {{ number_format($respondentsGranted) }}
                        </p>

                    </div>

                    <div class="rounded-[10px] bg-[#EFF8F1] p-[16px]">

                        <p class="text-[10px] text-[#777777]">
                            Remaining
                        </p>

                        <p class="mt-[9px] text-[19px] font-medium text-[#248743]">
                            {{ number_format($respondentsRemaining) }}
                        </p>

                    </div>

                </div>

                @if (!$activeSubscription)

                    <p class="mt-[18px] rounded-[8px] bg-[#FFF3D8] px-[14px] py-[12px] text-[11px] leading-[1.6] text-[#94620C]">
                        This user has no active subscription. Available respondents are 0 until a valid subscription is activated.
                    </p>

                @endif

            </section>

        </div>

        {{-- Research Statistics --}}

        <section class="mt-[18px] rounded-[14px] border border-[#BFC3C9] bg-white p-[22px]">

            <h2 class="text-[14px] font-medium text-[#202939]">
                Research Activity
            </h2>

            <p class="mt-[5px] text-[11px] text-[#777777]">
                Overview of this user's research studies.
            </p>

            @php
                $researchStatistics = [
                    [
                        'label' => 'Submitted',
                        'value' => (int) ($studyStats?->submitted ?? 0),
                        'color' => 'text-[#3775D6]',
                    ],
                    [
                        'label' => 'In Progress',
                        'value' => (int) ($studyStats?->in_progress ?? 0),
                        'color' => 'text-[#248743]',
                    ],
                    [
                        'label' => 'Review',
                        'value' => (int) ($studyStats?->review_count ?? 0),
                        'color' => 'text-[#C37B0A]',
                    ],
                    [
                        'label' => 'Completed',
                        'value' => (int) ($studyStats?->completed ?? 0),
                        'color' => 'text-[#248743]',
                    ],
                    [
                        'label' => 'Rejected',
                        'value' => (int) ($studyStats?->rejected ?? 0),
                        'color' => 'text-[#C34444]',
                    ],
                ];
            @endphp

            <div class="mt-[20px] grid gap-[12px] sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">

                @foreach ($researchStatistics as $stat)

                    <div class="rounded-[10px] bg-[#F7F9FC] px-[16px] py-[17px]">

                        <p class="text-[10px] uppercase text-[#777777]">
                            {{ $stat['label'] }}
                        </p>

                        <p class="mt-[12px] text-[22px] font-medium {{ $stat['color'] }}">
                            {{ number_format($stat['value']) }}
                        </p>

                    </div>

                @endforeach

            </div>

        </section>

        {{-- Subscription History --}}

        <section class="mt-[18px] overflow-hidden rounded-[14px] border border-[#BFC3C9] bg-white">

            <div class="border-b border-[#EAEAEA] px-[22px] py-[18px]">

                <h2 class="text-[14px] font-medium text-[#202939]">
                    Subscription History
                </h2>

                <p class="mt-[5px] text-[11px] text-[#777777]">
                    All current and previous subscriptions for this account.
                </p>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full min-w-[900px] text-left">

                    <thead class="border-b border-[#E3E3E3] bg-[#F8F9FC]">

                        <tr class="text-[10px] uppercase text-[#666666]">

                            <th class="px-[20px] py-[15px] font-medium">Plan</th>
                            <th class="px-[20px] py-[15px] font-medium">Status</th>
                            <th class="px-[20px] py-[15px] font-medium">Started</th>
                            <th class="px-[20px] py-[15px] font-medium">Ends</th>
                            <th class="px-[20px] py-[15px] font-medium">Granted</th>
                            <th class="px-[20px] py-[15px] font-medium">Used</th>
                            <th class="px-[20px] py-[15px] font-medium">Type</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($subscriptionHistory as $history)

                            @php
                                $historyStatus = $history->status ?? 'unknown';

                                if (
                                    $history->ends_at &&
                                    $history->ends_at->lte(now())
                                ) {
                                    $historyStatus = 'expired';
                                }

                                $historyStatusLabel = $statusLabels[$historyStatus]
                                    ?? ucwords(str_replace('_', ' ', $historyStatus));

                                $historyStatusClass = $statusColors[$historyStatus]
                                    ?? 'bg-[#E7EFFF] text-[#3775D6]';
                            @endphp

                            <tr class="border-b border-[#EAEAEA] last:border-b-0">

                                <td class="px-[20px] py-[18px] text-[12px] font-medium text-[#26354B]">
                                    {{ $history->plan?->name ?? 'Unknown Plan' }}
                                </td>

                                <td class="px-[20px] py-[18px]">

                                    <span class="inline-flex whitespace-nowrap rounded-full px-[10px] py-[6px] text-[10px] font-medium {{ $historyStatusClass }}">
                                        ● {{ $historyStatusLabel }}
                                    </span>

                                </td>

                                <td class="px-[20px] py-[18px] text-[11px] text-[#555555]">
                                    {{ $history->starts_at?->format('M d, Y') ?? '—' }}
                                </td>

                                <td class="px-[20px] py-[18px] text-[11px] text-[#555555]">
                                    {{ $history->ends_at?->format('M d, Y') ?? '—' }}
                                </td>

                                <td class="px-[20px] py-[18px] text-[11px] text-[#555555]">
                                    {{ number_format((int) $history->respondents_granted) }}
                                </td>

                                <td class="px-[20px] py-[18px] text-[11px] text-[#555555]">
                                    {{ number_format((int) $history->respondents_used) }}
                                </td>

                                <td class="px-[20px] py-[18px] text-[11px] text-[#555555]">
                                    {{ $history->is_demo ? 'Demo' : 'Paid' }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="px-[20px] py-[45px] text-center text-[12px] text-[#777777]">
                                    No subscription history available.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </section>

        {{-- Research Studies --}}

        <section class="mt-[18px] overflow-hidden rounded-[14px] border border-[#BFC3C9] bg-white">

            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#EAEAEA] px-[22px] py-[18px]">

                <div>

                    <h2 class="text-[14px] font-medium text-[#202939]">
                        Research Studies
                    </h2>

                    <p class="mt-[5px] text-[11px] text-[#777777]">
                        All studies submitted by this user.
                    </p>

                </div>

                <span class="rounded-full bg-[#F0F2F7] px-[12px] py-[6px] text-[10px] text-[#666666]">
                    {{ number_format($researchRequests->total()) }} studies
                </span>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full min-w-[950px] text-left">

                    <thead class="border-b border-[#E3E3E3] bg-[#F8F9FC]">

                        <tr class="text-[10px] uppercase text-[#666666]">

                            <th class="px-[20px] py-[15px] font-medium">Study</th>
                            <th class="px-[20px] py-[15px] font-medium">Research Goal</th>
                            <th class="px-[20px] py-[15px] font-medium">Respondents</th>
                            <th class="px-[20px] py-[15px] font-medium">Status</th>
                            <th class="px-[20px] py-[15px] font-medium">Submitted</th>
                            <th class="px-[20px] py-[15px] font-medium">Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($researchRequests as $study)

                            @php
                                $studyStatus = $study->status ?? 'submitted';

                                $studyStatusLabel = match ($studyStatus) {
                                    'submitted' => 'Submitted',
                                    'in_progress' => 'In Progress',
                                    'review' => 'Review',
                                    'completed' => 'Completed',
                                    'rejected' => 'Rejected',
                                    default => ucwords(str_replace('_', ' ', $studyStatus)),
                                };

                                $studyStatusClass = match ($studyStatus) {
                                    'submitted' => 'bg-[#E7EFFF] text-[#3775D6]',
                                    'in_progress' => 'bg-[#E0F4E5] text-[#248743]',
                                    'review' => 'bg-[#FFF3D8] text-[#C37B0A]',
                                    'completed' => 'bg-[#E8F4EA] text-[#248743]',
                                    'rejected' => 'bg-[#FCE8E8] text-[#C34444]',
                                    default => 'bg-[#F0F0F0] text-[#777777]',
                                };
                            @endphp

                            <tr class="border-b border-[#EAEAEA] last:border-b-0 hover:bg-[#FAFBFF]">

                                <td class="px-[20px] py-[18px]">

                                    <p class="whitespace-nowrap font-mono text-[11px] font-medium text-[#202939]">
                                        Study {{ $study->study_number }}
                                    </p>

                                    <p class="mt-[5px] whitespace-nowrap font-mono text-[10px] text-[#777777]">
                                        {{ $study->public_request_code }}
                                    </p>

                                </td>

                                <td class="px-[20px] py-[18px] text-[12px] text-[#26354B]">
                                    {{ $study->goal }}
                                </td>

                                <td class="px-[20px] py-[18px]">

                                    <p class="text-[12px] font-medium text-[#26354B]">
                                        {{ number_format((int) $study->sample_size) }}
                                    </p>

                                    <p class="mt-[5px] text-[9px] text-[#888888]">
                                        {{ number_format((int) $study->respondents_reserved) }} reserved
                                    </p>

                                </td>

                                <td class="px-[20px] py-[18px]">

                                    <span class="inline-flex whitespace-nowrap rounded-full px-[11px] py-[6px] text-[10px] font-medium {{ $studyStatusClass }}">
                                        ● {{ $studyStatusLabel }}
                                    </span>

                                </td>

                                <td class="px-[20px] py-[18px] text-[11px] text-[#777777]">
                                    {{ $study->created_at?->format('M d, Y') ?? '—' }}
                                </td>

                                <td class="px-[20px] py-[18px]">

                                    <a
                                        href="{{ route('admin.research-requests.show', $study) }}"
                                        class="whitespace-nowrap text-[11px] font-medium text-[#4D7FCF] hover:underline"
                                    >
                                        View Details
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="px-[20px] py-[45px] text-center text-[12px] text-[#777777]">
                                    This user has not submitted any research studies yet.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Studies Pagination --}}

            @if ($researchRequests->hasPages())

                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-[#EAEAEA] bg-[#F8F9FC] px-[22px] py-[15px]">

                    <p class="text-[10px] text-[#777777]">
                        Showing {{ $researchRequests->firstItem() }}
                        – {{ $researchRequests->lastItem() }}
                        of {{ $researchRequests->total() }} studies
                    </p>

                    <div class="flex items-center gap-[8px]">

                        @if ($researchRequests->onFirstPage())

                            <span class="rounded-[7px] border border-[#D8DCE2] px-[14px] py-[8px] text-[11px] text-[#AAAAAA]">
                                Previous
                            </span>

                        @else

                            <a
                                href="{{ $researchRequests->previousPageUrl() }}"
                                class="rounded-[7px] border border-[#C3C7CE] bg-white px-[14px] py-[8px] text-[11px] hover:border-[#4D7FCF]"
                            >
                                Previous
                            </a>

                        @endif

                        <span class="px-[8px] text-[11px] text-[#777777]">
                            {{ $researchRequests->currentPage() }}
                            /
                            {{ $researchRequests->lastPage() }}
                        </span>

                        @if ($researchRequests->hasMorePages())

                            <a
                                href="{{ $researchRequests->nextPageUrl() }}"
                                class="rounded-[7px] border border-[#C3C7CE] bg-white px-[14px] py-[8px] text-[11px] hover:border-[#4D7FCF]"
                            >
                                Next
                            </a>

                        @else

                            <span class="rounded-[7px] border border-[#D8DCE2] px-[14px] py-[8px] text-[11px] text-[#AAAAAA]">
                                Next
                            </span>

                        @endif

                    </div>

                </div>

            @endif

        </section>

        {{-- Read-only Notice --}}

        <p class="mt-[18px] text-center text-[10px] text-[#888888]">
            This page is read-only. Subscription and respondent information is managed by the platform.
        </p>

    </main>

</div>

@endsection
