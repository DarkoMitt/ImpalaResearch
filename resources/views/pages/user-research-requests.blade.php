
@extends('layouts.app')

@section('content')
<div class="flex min-h-screen w-full bg-white">

    @include('partials.user-sidebar')

    <main class="min-h-screen min-w-0 flex-1 bg-gradient-to-br from-[#DCEAFF] via-[#F7FAFF] to-white px-[30px] py-[46px] lg:px-[42px]">

        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-[25px] font-medium tracking-[-0.6px] text-[#171717]">
                    My Research Requests
                </h1>
                <p class="mt-[6px] text-[12px] text-[#666666]">
                    View and track all your submitted research studies.
                </p>
            </div>

            <a
                href="{{ route('home') }}#start-study"
                class="inline-flex h-[42px] items-center rounded-[7px] bg-[#4D7FCF] px-[18px] text-[12px] font-medium text-white hover:bg-[#416FB4]"
            >
                + Create New Study
            </a>
        </div>

        <div class="mt-[32px] rounded-[12px] border border-[#BFC3C9] bg-white px-[22px] py-[20px]">
            <p class="text-[10px] uppercase text-[#666666]">
                Total Research Requests
            </p>
            <h2 class="mt-[12px] text-[27px] font-medium text-[#171717]">
                {{ $totalStudies }}
            </h2>
        </div>

        {{-- Search & Filter --}}
        <form
            method="GET"
            action="{{ route('my-studies.index') }}"
            class="mt-[25px] flex flex-wrap items-end gap-[12px]"
        >
            <div class="min-w-[220px] flex-1">
                <label for="search" class="mb-[8px] block text-[11px] text-[#555555]">
                    Search Studies
                </label>
                <input
                    id="search"
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search by request ID, goal or country..."
                    class="h-[42px] w-full rounded-[6px] border border-[#BFC3C9] bg-white px-[14px] text-[12px] outline-none focus:border-[#4D7FCF]"
                >
            </div>

            <div class="w-[175px]">
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

            <button type="submit" class="h-[42px] rounded-[6px] bg-[#4D7FCF] px-[20px] text-[12px] text-white hover:bg-[#416FB4]">
                Search
            </button>

            <a href="{{ route('my-studies.index') }}" class="inline-flex h-[42px] items-center rounded-[6px] border border-[#BFC3C9] bg-white px-[18px] text-[12px] text-[#555555]">
                Reset
            </a>
        </form>

        {{-- Studies Table --}}
        <div class="mt-[24px] overflow-hidden rounded-[12px] border border-[#BFC3C9] bg-white">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[750px] text-left">
                    <thead class="border-b border-[#E3E3E3] bg-[#F7F8FA]">
                        <tr class="text-[10px] uppercase text-[#666666]">
                            <th class="px-[20px] py-[17px] font-medium">Request ID</th>
                            <th class="px-[20px] py-[17px] font-medium">Research Goal</th>
                            <th class="px-[20px] py-[17px] font-medium">Country</th>
                            <th class="px-[20px] py-[17px] font-medium">Respondents</th>
                            <th class="px-[20px] py-[17px] font-medium">Status</th>
                            <th class="px-[20px] py-[17px] font-medium">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($studies as $study)
                            @php
                                $status = $study->status ?? 'submitted';

                                $label = match ($status) {
                                    'submitted' => 'Submitted',
                                    'in_progress', 'in_field' => 'In Field',
                                    'review', 'under_review' => 'In Review',
                                    'completed' => 'Completed',
                                    default => ucwords(str_replace('_', ' ', $status)),
                                };

                                $color = match ($status) {
                                    'submitted' => 'bg-[#E7EFFF] text-[#3775D6]',
                                    'in_progress', 'in_field' => 'bg-[#E0F4E5] text-[#248743]',
                                    'review', 'under_review' => 'bg-[#FFF3D8] text-[#C37B0A]',
                                    'completed' => 'bg-[#E8F4EA] text-[#248743]',
                                    default => 'bg-[#F1F1F1] text-[#777777]',
                                };

                                $code = 'REQ-' . str_pad((string) $study->id, 4, '0', STR_PAD_LEFT);
                            @endphp

                            <tr class="border-b border-[#EAEAEA] last:border-0 hover:bg-[#FAFBFF]">
                                <td class="px-[20px] py-[22px] font-mono text-[11px] text-[#192231]">
                                    {{ $code }}
                                </td>

                                <td class="px-[20px] py-[22px] text-[12px] font-medium text-[#192231]">
                                    {{ $study->goal }}
                                    <p class="mt-[5px] text-[10px] font-normal text-[#888888]">
                                        {{ $study->created_at?->format('M d, Y') }}
                                    </p>
                                </td>

                                <td class="px-[20px] py-[22px] text-[12px] text-[#555555]">
                                    {{ $study->country }}
                                </td>

                                <td class="px-[20px] py-[22px] text-[12px] text-[#555555]">
                                    {{ $study->sample_size }}
                                </td>

                                <td class="px-[20px] py-[22px]">
                                    <span class="whitespace-nowrap rounded-full px-[12px] py-[6px] text-[10px] font-medium {{ $color }}">
                                        ● {{ $label }}
                                    </span>
                                </td>

                                <td class="px-[20px] py-[22px]">
                                    <a
                                        href="{{ route('my-studies.show', $study) }}"
                                        class="text-[11px] font-medium text-[#4D7FCF] hover:underline"
                                    >
                                        View Details →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-[20px] py-[65px] text-center">
                                    <p class="text-[13px] font-medium text-[#333333]">
                                        No research requests found
                                    </p>
                                    <p class="mt-[8px] text-[11px] text-[#888888]">
                                        Create a study or adjust your filters.
                                    </p>
                                    <a
                                        href="{{ route('home') }}#start-study"
                                        class="mt-[18px] inline-block text-[12px] text-[#4D7FCF] hover:underline"
                                    >
                                        Create New Study
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-[#E3E3E3] bg-[#F9FAFC] px-[20px] py-[16px]">
                <p class="text-[11px] text-[#777777]">
                    Showing {{ $studies->firstItem() ?? 0 }}–{{ $studies->lastItem() ?? 0 }}
                    of {{ $studies->total() }} results
                </p>

                <div class="flex items-center gap-[10px]">
                    @if ($studies->onFirstPage())
                        <span class="rounded-[6px] border border-[#D5D8DF] px-[14px] py-[9px] text-[11px] text-[#AAAAAA]">
                            Previous
                        </span>
                    @else
                        <a href="{{ $studies->previousPageUrl() }}" class="rounded-[6px] border border-[#D5D8DF] px-[14px] py-[9px] text-[11px]">
                            Previous
                        </a>
                    @endif

                    <span class="text-[11px] text-[#777777]">
                        {{ $studies->currentPage() }} / {{ $studies->lastPage() }}
                    </span>

                    @if ($studies->hasMorePages())
                        <a href="{{ $studies->nextPageUrl() }}" class="rounded-[6px] border border-[#D5D8DF] px-[14px] py-[9px] text-[11px]">
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
