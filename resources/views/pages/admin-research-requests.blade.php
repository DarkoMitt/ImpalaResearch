
@extends('layouts.app')

@section('content')
<div class="flex min-h-screen w-full bg-[#F5F8FF]">

    @include('partials.admin-sidebar')

    <main class="min-w-0 flex-1 bg-gradient-to-br from-[#DDEAFF] via-[#F4F8FF] to-white px-5 py-8 lg:px-9 lg:py-10">

        {{-- Header --}}
        <div class="flex flex-wrap items-center justify-between gap-5">
            <div>
                <div class="mb-3 flex items-center gap-2 text-[11px] font-medium text-[#8A9BB8]">
                    <a href="{{ route('admin.dashboard') }}" class="transition hover:text-[#3478F6]">
                        Admin Workspace
                    </a>
                    <span>/</span>
                    <span class="text-[#3478F6]">Research Requests</span>
                </div>

                <h1 class="text-[29px] font-bold tracking-[-1px] text-[#0B1B39]">
                    Research Requests
                </h1>

                <p class="mt-2 text-[12px] text-[#7184A5]">
                    Monitor, review and manage research studies submitted by your users.
                </p>
            </div>

            <div class="inline-flex items-center gap-3 rounded-[13px] border border-[#D6E5FF] bg-white px-4 py-3 shadow-sm">
                <div class="flex h-9 w-9 items-center justify-center rounded-[10px] bg-[#EAF2FF] text-[#3478FF]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <rect x="4" y="4" width="16" height="16" rx="3"/>
                        <path stroke-linecap="round" d="M8 9h8M8 13h8M8 17h5"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-medium text-[#8A9BB8]">All Studies</p>
                    <p class="text-[13px] font-bold text-[#102243]">
                        {{ number_format($totalRequests) }} Total Requests
                    </p>
                </div>
            </div>
        </div>

        {{-- Statistics --}}
        @php
            $stats = [
                [
                    'label' => 'Total Requests',
                    'value' => $totalRequests,
                    'description' => 'All submitted studies',
                    'border' => 'border-[#C8DEFF]',
                    'background' => 'bg-gradient-to-br from-white to-[#EDF6FF]',
                    'iconBackground' => 'bg-[#E2EFFF]',
                    'color' => 'text-[#3478FF]',
                    'circle' => 'bg-[#DCEEFF]/70',
                    'icon' => 'total',
                ],
                [
                    'label' => 'Submitted',
                    'value' => $submittedCount,
                    'description' => 'Awaiting approval',
                    'border' => 'border-[#D7DCFF]',
                    'background' => 'bg-gradient-to-br from-white to-[#F0F1FF]',
                    'iconBackground' => 'bg-[#E8E9FF]',
                    'color' => 'text-[#6958EA]',
                    'circle' => 'bg-[#E5E8FF]/70',
                    'icon' => 'submitted',
                ],
                [
                    'label' => 'In Progress',
                    'value' => $inProgressCount,
                    'description' => 'Active research studies',
                    'border' => 'border-[#C9ECDC]',
                    'background' => 'bg-gradient-to-br from-white to-[#EBFBF3]',
                    'iconBackground' => 'bg-[#DDF8E9]',
                    'color' => 'text-[#13A36B]',
                    'circle' => 'bg-[#D8F7E8]/70',
                    'icon' => 'progress',
                ],
                [
                    'label' => 'Review',
                    'value' => $reviewCount,
                    'description' => 'Pending final review',
                    'border' => 'border-[#F8E0B9]',
                    'background' => 'bg-gradient-to-br from-white to-[#FFF8E9]',
                    'iconBackground' => 'bg-[#FFF0CF]',
                    'color' => 'text-[#D38B0C]',
                    'circle' => 'bg-[#FFF0D0]/70',
                    'icon' => 'review',
                ],
                [
                    'label' => 'Completed',
                    'value' => $completedCount,
                    'description' => 'Finished research studies',
                    'border' => 'border-[#BDEBD7]',
                    'background' => 'bg-gradient-to-br from-white to-[#EDFCF7]',
                    'iconBackground' => 'bg-[#D7F8E9]',
                    'color' => 'text-[#0B9B69]',
                    'circle' => 'bg-[#D7F8E9]/70',
                    'icon' => 'completed',
                ],
            ];
        @endphp

        <div class="mt-9 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-5">
            @foreach ($stats as $stat)
                <div class="relative overflow-hidden rounded-[19px] border {{ $stat['border'] }} {{ $stat['background'] }} p-5 shadow-[0_2px_5px_rgba(30,64,120,0.06)]">

                    <div class="pointer-events-none absolute -bottom-12 -right-12 h-36 w-36 rounded-full {{ $stat['circle'] }}"></div>

                    <div class="relative flex h-12 w-12 items-center justify-center rounded-[14px] {{ $stat['iconBackground'] }} {{ $stat['color'] }}">

                        @switch($stat['icon'])
                            @case('total')
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <rect x="4" y="4" width="16" height="16" rx="3"/>
                                    <path stroke-linecap="round" d="M8 9h8M8 13h8M8 17h5"/>
                                </svg>
                                @break

                            @case('submitted')
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/>
                                </svg>
                                @break

                            @case('progress')
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="9"/>
                                    <path stroke-linecap="round" d="M12 7v5l3 2"/>
                                </svg>
                                @break

                            @case('review')
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 3h8l4 4v14H4V3h4zM8 12h8M8 16h5"/>
                                </svg>
                                @break

                            @case('completed')
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="9"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12l3 3 5-6"/>
                                </svg>
                                @break
                        @endswitch

                    </div>

                    <div class="relative mt-5">
                        <p class="text-[12px] font-semibold text-[#516581]">
                            {{ $stat['label'] }}
                        </p>

                        <h2 class="mt-2 text-[32px] font-bold tracking-[-1px] {{ $stat['color'] }}">
                            {{ number_format($stat['value']) }}
                        </h2>

                        <p class="mt-2 text-[10px] text-[#8799B3]">
                            {{ $stat['description'] }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Search and Filters --}}
        <section class="mt-7 overflow-hidden rounded-[19px] border border-[#DFE8F6] bg-white shadow-[0_2px_5px_rgba(30,64,120,0.06)]">

            <div class="flex items-center gap-3 border-b border-[#EDF1F8] px-6 py-5">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#EAF2FF] text-[#3478FF]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <circle cx="10.5" cy="10.5" r="6.5"/>
                        <path stroke-linecap="round" d="M16 16l5 5"/>
                    </svg>
                </div>

                <div>
                    <h2 class="text-[14px] font-bold text-[#102243]">Find Research Requests</h2>
                    <p class="mt-1 text-[11px] text-[#8A9AB3]">
                        Search studies or narrow down results by their current status.
                    </p>
                </div>
            </div>

            <form method="GET"
                  action="{{ route('admin.research-requests.index') }}"
                  class="flex flex-wrap items-end gap-3 px-6 py-5">

                <div class="min-w-[230px] flex-1">
                    <label for="search" class="mb-2 block text-[11px] font-semibold text-[#516581]">
                        Search Requests
                    </label>

                    <div class="relative">
                        <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[#91A4BF]"
                             fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <circle cx="10.5" cy="10.5" r="6.5"/>
                            <path stroke-linecap="round" d="M16 16l5 5"/>
                        </svg>

                        <input
                            id="search"
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search by Study #, Request ID, goal, name or email..."
                            class="h-12 w-full rounded-[11px] border border-[#DCE5F3] bg-[#FAFCFF] pl-11 pr-4 text-[12px] text-[#102243] outline-none transition placeholder:text-[#9BAAC0] focus:border-[#3478FF] focus:bg-white focus:ring-2 focus:ring-[#3478FF]/10"
                        >
                    </div>
                </div>

                <div class="w-full sm:w-[200px]">
                    <label for="status" class="mb-2 block text-[11px] font-semibold text-[#516581]">
                        Request Status
                    </label>

                    <select
                        id="status"
                        name="status"
                        class="h-12 w-full cursor-pointer rounded-[11px] border border-[#DCE5F3] bg-[#FAFCFF] px-3.5 text-[12px] text-[#102243] outline-none transition focus:border-[#3478FF] focus:bg-white focus:ring-2 focus:ring-[#3478FF]/10"
                    >
                        <option value="">All Statuses</option>
                        <option value="submitted" @selected($selectedStatus === 'submitted')>Submitted</option>
                        <option value="in_progress" @selected($selectedStatus === 'in_progress')>In Progress</option>
                        <option value="review" @selected($selectedStatus === 'review')>Review</option>
                        <option value="completed" @selected($selectedStatus === 'completed')>Completed</option>
                        <option value="rejected" @selected($selectedStatus === 'rejected')>Rejected</option>
                    </select>
                </div>

                <button
                    type="submit"
                    class="inline-flex h-12 cursor-pointer items-center justify-center gap-2 rounded-[11px] bg-[#397CF6] px-6 text-[12px] font-bold text-white shadow-[0_3px_8px_rgba(57,124,246,0.2)] transition hover:bg-[#2869E3]"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <circle cx="10.5" cy="10.5" r="6.5"/>
                        <path stroke-linecap="round" d="M16 16l5 5"/>
                    </svg>
                    Search
                </button>

                <a
                    href="{{ route('admin.research-requests.index') }}"
                    class="inline-flex h-12 items-center justify-center gap-2 rounded-[11px] border border-[#DCE5F3] bg-white px-5 text-[12px] font-semibold text-[#7184A5] transition hover:border-[#B9CFF2] hover:bg-[#F6F9FF]"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12a9 9 0 111.8 5.4M3 18v-5h5"/>
                    </svg>
                    Reset
                </a>

            </form>

        </section>

        {{-- Requests Table --}}
        <section class="mt-6 overflow-hidden rounded-[19px] border border-[#DFE8F6] bg-white shadow-[0_2px_5px_rgba(30,64,120,0.06)]">

            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#EDF1F8] px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#EEEAFE] text-[#7557E9]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <rect x="4" y="4" width="16" height="16" rx="3"/>
                            <path stroke-linecap="round" d="M8 9h8M8 13h8M8 17h5"/>
                        </svg>
                    </div>

                    <div>
                        <h2 class="text-[14px] font-bold text-[#102243]">All Research Requests</h2>
                        <p class="mt-1 text-[11px] text-[#8A9AB3]">
                            Review individual studies and open their details.
                        </p>
                    </div>
                </div>

                <span class="rounded-full bg-[#EDF4FF] px-3.5 py-2 text-[10px] font-bold text-[#3478FF]">
                    {{ number_format($requests->total()) }} Results
                </span>
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

                        @forelse ($requests as $researchRequest)

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

                                {{-- Request --}}
                                <td class="px-5 py-5">
                                    <p class="whitespace-nowrap font-mono text-[12px] font-bold text-[#102243]">
                                        Study {{ $researchRequest->study_number }}
                                    </p>

                                    <p class="mt-1.5 whitespace-nowrap font-mono text-[10px] text-[#8A9AB3]">
                                        {{ $researchRequest->public_request_code }}
                                    </p>
                                </td>

                                {{-- User --}}
                                <td class="px-5 py-5">
                                    <p class="text-[12px] font-semibold text-[#102243]">
                                        {{ $researchRequest->user?->name ?? $researchRequest->full_name ?? 'Unknown user' }}
                                    </p>

                                    <p class="mt-1.5 break-all text-[10px] text-[#8A9AB3]">
                                        {{ $researchRequest->user?->email ?? $researchRequest->email }}
                                    </p>
                                </td>

                                {{-- Research Goal --}}
                                <td class="px-5 py-5">
                                    <span class="text-[12px] font-medium text-[#516581]">
                                        {{ $researchRequest->goal }}
                                    </span>
                                </td>

                                {{-- Respondents --}}
                                <td class="px-5 py-5">
                                    <span class="inline-flex rounded-[9px] bg-[#F0F4FB] px-3 py-2 text-[11px] font-bold text-[#516581]">
                                        {{ number_format((int) $researchRequest->sample_size) }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="px-5 py-5">
                                    <span class="inline-flex items-center gap-2 whitespace-nowrap rounded-full px-3 py-2 text-[10px] font-semibold {{ $statusClass }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $dotClass }}"></span>
                                        {{ $statusLabel }}
                                    </span>
                                </td>

                                {{-- Submitted --}}
                                <td class="whitespace-nowrap px-5 py-5 text-[11px] font-medium text-[#7184A5]">
                                    {{ $researchRequest->created_at?->format('M d, Y') ?? '—' }}
                                </td>

                                {{-- Action --}}
                                <td class="px-5 py-5">
                                    <a
                                        href="{{ route('admin.research-requests.show', $researchRequest) }}"
                                        class="inline-flex items-center gap-2 whitespace-nowrap rounded-[10px] border border-[#BED4FF] bg-[#F0F5FF] px-3.5 py-2.5 text-[11px] font-bold text-[#3478FF] transition hover:bg-[#E1EDFF]"
                                    >
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

                                    <p class="mt-4 text-[13px] font-bold text-[#102243]">
                                        No research requests found
                                    </p>

                                    <p class="mt-2 text-[11px] text-[#8A9AB3]">
                                        Try changing your search or status filters.
                                    </p>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>

            {{-- Pagination --}}
            <div class="flex flex-wrap items-center justify-between gap-4 border-t border-[#EDF1F8] bg-[#F8FAFE] px-6 py-5">

                <p class="text-[11px] font-medium text-[#8A9AB3]">
                    Showing
                    <span class="font-bold text-[#102243]">{{ $requests->firstItem() ?? 0 }}</span>
                    –
                    <span class="font-bold text-[#102243]">{{ $requests->lastItem() ?? 0 }}</span>
                    of
                    <span class="font-bold text-[#102243]">{{ $requests->total() }}</span>
                    results
                </p>

                <div class="flex items-center gap-2">

                    @if ($requests->onFirstPage())
                        <span class="inline-flex h-10 items-center rounded-[10px] border border-[#E2E8F2] bg-[#F3F5F9] px-4 text-[11px] font-semibold text-[#B0BDCF]">
                            Previous
                        </span>
                    @else
                        <a href="{{ $requests->previousPageUrl() }}"
                           class="inline-flex h-10 items-center rounded-[10px] border border-[#DCE5F3] bg-white px-4 text-[11px] font-semibold text-[#516581] transition hover:border-[#3478FF] hover:text-[#3478FF]">
                            Previous
                        </a>
                    @endif

                    <span class="rounded-[10px] bg-[#EDF4FF] px-3.5 py-3 text-[11px] font-bold text-[#3478FF]">
                        {{ $requests->currentPage() }} / {{ $requests->lastPage() }}
                    </span>

                    @if ($requests->hasMorePages())
                        <a href="{{ $requests->nextPageUrl() }}"
                           class="inline-flex h-10 items-center rounded-[10px] border border-[#DCE5F3] bg-white px-4 text-[11px] font-semibold text-[#516581] transition hover:border-[#3478FF] hover:text-[#3478FF]">
                            Next
                        </a>
                    @else
                        <span class="inline-flex h-10 items-center rounded-[10px] border border-[#E2E8F2] bg-[#F3F5F9] px-4 text-[11px] font-semibold text-[#B0BDCF]">
                            Next
                        </span>
                    @endif

                </div>
            </div>

        </section>

        {{-- Footer --}}
        <div class="mt-8 flex flex-wrap items-center justify-between gap-3 border-t border-[#DCE7F5] pt-5">
            <p class="text-[10px] text-[#91A0B6]">
                Impala Research · Administration Workspace
            </p>
            <p class="text-[10px] text-[#91A0B6]">
                Research Requests Management
            </p>
        </div>

    </main>
</div>
@endsection
r