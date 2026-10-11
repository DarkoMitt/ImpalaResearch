
@extends('layouts.app')

@section('content')
<div class="flex min-h-screen w-full bg-white">

    @include('partials.admin-sidebar')

    <main class="min-h-screen min-w-0 flex-1 bg-gradient-to-br from-[#DCEAFF] via-[#F7FAFF] to-white px-[30px] py-[46px] lg:px-[42px]">

        {{-- Header --}}
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-[25px] font-medium tracking-[-0.6px] text-[#171717]">
                    Research Requests
                </h1>

                <p class="mt-[6px] text-[12px] text-[#666666]">
                    Manage research studies submitted by your users.
                </p>
            </div>

            <span class="rounded-[7px] border border-[#C8D8F5] bg-white px-[18px] py-[12px] text-[11px] text-[#4D7FCF]">
                {{ $totalRequests }} Total Requests
            </span>
        </div>

        {{-- Stats --}}
        <div class="mt-[34px] grid gap-[14px] sm:grid-cols-2 xl:grid-cols-5">

            @foreach ([
                ['Total Requests', $totalRequests],
                ['Submitted', $submittedCount],
                ['In Progress', $inProgressCount],
                ['Review', $reviewCount],
                ['Completed', $completedCount],
            ] as $stat)
                <div class="rounded-[12px] border border-[#BFC3C9] bg-white px-[18px] py-[20px]">
                    <p class="text-[10px] uppercase text-[#666666]">
                        {{ $stat[0] }}
                    </p>

                    <h2 class="mt-[16px] text-[25px] font-medium text-[#171717]">
                        {{ $stat[1] }}
                    </h2>
                </div>
            @endforeach

        </div>

        {{-- Filters --}}
        <form
            method="GET"
            action="{{ route('admin.research-requests.index') }}"
            class="mt-[30px] flex flex-wrap items-end gap-[12px]"
        >
            <div class="min-w-[220px] flex-1">
                <label for="search" class="mb-[8px] block text-[11px] text-[#555555]">
                    Search Requests
                </label>

                <input
                    id="search"
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search by ID, goal, name or email..."
                    class="h-[42px] w-full rounded-[6px] border border-[#BFC3C9] bg-white px-[14px] text-[12px] outline-none focus:border-[#4D7FCF]"
                >
            </div>

            <div class="w-[180px]">
                <label for="status" class="mb-[8px] block text-[11px] text-[#555555]">
                    Status
                </label>

                <select
                    id="status"
                    name="status"
                    class="h-[42px] w-full rounded-[6px] border border-[#BFC3C9] bg-white px-[12px] text-[12px] outline-none focus:border-[#4D7FCF]"
                >
                    <option value="">All Statuses</option>
                    <option value="submitted" @selected($selectedStatus === 'submitted')>Submitted</option>
                    <option value="in_progress" @selected($selectedStatus === 'in_progress')>In Progress</option>
                    <option value="review" @selected($selectedStatus === 'review')>Review</option>
                    <option value="completed" @selected($selectedStatus === 'completed')>Completed</option>
                </select>
            </div>

            <button
                type="submit"
                class="h-[42px] rounded-[6px] bg-[#4D7FCF] px-[20px] text-[12px] font-medium text-white transition hover:bg-[#416FB4]"
            >
                Search
            </button>

            <a
                href="{{ route('admin.research-requests.index') }}"
                class="inline-flex h-[42px] items-center rounded-[6px] border border-[#BFC3C9] bg-white px-[18px] text-[12px] text-[#555555] hover:bg-[#F7F9FD]"
            >
                Reset
            </a>
        </form>

        {{-- Requests Table --}}
        <div class="mt-[24px] overflow-hidden rounded-[12px] border border-[#BFC3C9] bg-white">

            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-left">

                    <thead class="border-b border-[#E3E3E3] bg-[#F7F8FA]">
                        <tr class="text-[10px] uppercase text-[#666666]">
                            <th class="px-[20px] py-[17px] font-medium">Request</th>
                            <th class="px-[20px] py-[17px] font-medium">User</th>
                            <th class="px-[20px] py-[17px] font-medium">Research Goal</th>
                            <th class="px-[20px] py-[17px] font-medium">Respondents</th>
                            <th class="px-[20px] py-[17px] font-medium">Status</th>
                            <th class="px-[20px] py-[17px] font-medium">Submitted</th>
                            <th class="px-[20px] py-[17px] font-medium">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($requests as $researchRequest)
                            @php
                                $status = $researchRequest->status;

                                $statusLabel = match ($status) {
                                    'submitted' => 'Submitted',
                                    'in_progress' => 'In Progress',
                                    'review' => 'Review',
                                    'completed' => 'Completed',
                                    default => ucfirst(str_replace('_', ' ', $status)),
                                };

                                $statusClass = match ($status) {
                                    'submitted' => 'bg-[#E7EFFF] text-[#3775D6]',
                                    'in_progress' => 'bg-[#E0F4E5] text-[#248743]',
                                    'review' => 'bg-[#FFF3D8] text-[#C37B0A]',
                                    'completed' => 'bg-[#E8F4EA] text-[#248743]',
                                    default => 'bg-[#F1F1F1] text-[#777777]',
                                };

                                $requestCode = 'REQ-' . str_pad(
                                    (string) $researchRequest->id,
                                    4,
                                    '0',
                                    STR_PAD_LEFT
                                );
                            @endphp

                            <tr class="border-b border-[#EAEAEA] last:border-b-0 hover:bg-[#FAFBFF]">

                                <td class="px-[20px] py-[22px]">
                                    <span class="font-mono text-[11px] font-medium text-[#192231]">
                                        {{ $requestCode }}
                                    </span>
                                </td>

                                <td class="px-[20px] py-[22px]">
                                    <p class="text-[12px] font-medium text-[#192231]">
                                        {{ $researchRequest->user?->name ?? $researchRequest->full_name }}
                                    </p>

                                    <p class="mt-[5px] text-[10px] text-[#777777]">
                                        {{ $researchRequest->user?->email ?? $researchRequest->email }}
                                    </p>
                                </td>

                                <td class="px-[20px] py-[22px] text-[12px] text-[#333333]">
                                    {{ $researchRequest->goal }}
                                </td>

                                <td class="px-[20px] py-[22px] text-[12px] text-[#555555]">
                                    {{ $researchRequest->sample_size }}
                                </td>

                                <td class="px-[20px] py-[22px]">
                                    <span class="inline-block whitespace-nowrap rounded-full px-[12px] py-[6px] text-[10px] font-medium {{ $statusClass }}">
                                        ● &nbsp; {{ $statusLabel }}
                                    </span>
                                </td>

                                <td class="px-[20px] py-[22px] text-[11px] text-[#777777]">
                                    {{ $researchRequest->created_at?->format('M d, Y') }}
                                </td>

                                <td class="px-[20px] py-[22px]">
                                    <a
                                        href="{{ route('admin.research-requests.show', $researchRequest) }}"
                                        class="text-[11px] font-medium text-[#4D7FCF] hover:underline"
                                    >
                                        View Details
                                    </a>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-[20px] py-[65px] text-center">
                                    <p class="text-[13px] font-medium text-[#333333]">
                                        No research requests found.
                                    </p>

                                    <p class="mt-[8px] text-[11px] text-[#888888]">
                                        Try changing your search or filters.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- Pagination --}}
            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-[#E3E3E3] bg-[#F9FAFC] px-[20px] py-[16px]">

                <p class="text-[11px] text-[#777777]">
                    Showing {{ $requests->firstItem() ?? 0 }}
                    –
                    {{ $requests->lastItem() ?? 0 }}
                    of {{ $requests->total() }} results
                </p>

                <div class="flex items-center gap-[10px]">

                    @if ($requests->onFirstPage())
                        <span class="rounded-[6px] border border-[#D5D8DF] px-[14px] py-[9px] text-[11px] text-[#AAAAAA]">
                            Previous
                        </span>
                    @else
                        <a
                            href="{{ $requests->previousPageUrl() }}"
                            class="rounded-[6px] border border-[#D5D8DF] px-[14px] py-[9px] text-[11px] text-[#555555] hover:bg-white"
                        >
                            Previous
                        </a>
                    @endif

                    <span class="text-[11px] text-[#777777]">
                        {{ $requests->currentPage() }} / {{ $requests->lastPage() }}
                    </span>

                    @if ($requests->hasMorePages())
                        <a
                            href="{{ $requests->nextPageUrl() }}"
                            class="rounded-[6px] border border-[#D5D8DF] px-[14px] py-[9px] text-[11px] text-[#555555] hover:bg-white"
                        >
                            Next
                        </a>
                    @else
                        <span class="rounded-[6px] border border-[#D5D8DF] px-[14px] py-[9px] text-[11px] text-[#AAAAAA]">
                            Next
                        </span>
                    @endif

                </div>
            </div>

        </div>
    </main>
</div>
@endsection
