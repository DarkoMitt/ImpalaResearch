
@extends('layouts.app')

@section('content')

<div class="flex min-h-screen w-full bg-white">

    @include('partials.admin-sidebar')

    <main class="min-h-screen min-w-0 flex-1 bg-gradient-to-br from-[#DCEAFF] via-[#F7FAFF] to-white px-5 py-10 lg:px-10">

        {{-- Page Header --}}
        <div class="flex flex-wrap items-start justify-between gap-5">

            <div>
                <h1 class="text-[27px] font-semibold tracking-tight text-[#172235]">
                    Contact & Demo
                </h1>

                <p class="mt-2 text-[12px] text-[#68758A]">
                    Manage incoming contact inquiries and demo requests.
                </p>
            </div>

            <div class="inline-flex items-center gap-3 rounded-xl border border-[#D5E1F3] bg-white px-5 py-3 shadow-sm">
                <span class="h-2.5 w-2.5 rounded-full bg-[#397CF6]"></span>

                <span class="text-[12px] font-medium text-[#34415A]">
                    {{ number_format($totalRequests) }}
                    {{ $totalRequests === 1 ? 'Total Request' : 'Total Requests' }}
                </span>
            </div>

        </div>

        {{-- Statistics Cards --}}
        <div class="mt-9 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            {{-- Total Requests --}}
            <div class="relative overflow-hidden rounded-2xl border border-[#C8E0FF] bg-gradient-to-br from-white via-[#F5FAFF] to-[#E7F4FF] p-6 shadow-sm">

                <div class="absolute -bottom-10 -right-10 h-36 w-36 rounded-full bg-[#D6ECFF]/60"></div>

                <div class="relative flex items-start gap-4">

                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-[#E3F0FF] text-[#397CF6]">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                            <path stroke-linecap="round" stroke-linejoin="round" d="m3 7 9 6 9-6"></path>
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <p class="text-[12px] font-semibold text-[#34415A]">
                            Total Requests
                        </p>

                        <p class="mt-2 text-[32px] font-bold leading-none text-[#172235]">
                            {{ number_format($totalRequests) }}
                        </p>

                        <p class="mt-3 text-[10px] leading-5 text-[#7C879A]">
                            All contact and demo inquiries
                        </p>
                    </div>

                </div>
            </div>

            {{-- New Requests --}}
            <div class="relative overflow-hidden rounded-2xl border border-[#C9DEFF] bg-gradient-to-br from-white via-[#F4F8FF] to-[#E6F1FF] p-6 shadow-sm">

                <div class="absolute -bottom-10 -right-10 h-36 w-36 rounded-full bg-[#DFECFF]/70"></div>

                <div class="relative flex items-start gap-4">

                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-[#E4EEFF] text-[#397CF6]">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m22 2-7 20-4-9-9-4Z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M22 2 11 13"></path>
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <p class="text-[12px] font-semibold text-[#34415A]">
                            New Requests
                        </p>

                        <p class="mt-2 text-[32px] font-bold leading-none text-[#286CF0]">
                            {{ number_format($newRequests) }}
                        </p>

                        <p class="mt-3 text-[10px] leading-5 text-[#7C879A]">
                            Awaiting first response
                        </p>
                    </div>

                </div>
            </div>

            {{-- Contacted --}}
            <div class="relative overflow-hidden rounded-2xl border border-[#F7E3B9] bg-gradient-to-br from-white via-[#FFFCF4] to-[#FFF4D9] p-6 shadow-sm">

                <div class="absolute -bottom-10 -right-10 h-36 w-36 rounded-full bg-[#FFF0C9]/70"></div>

                <div class="relative flex items-start gap-4">

                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-[#FFF2D5] text-[#CB8509]">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9"></circle>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"></path>
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <p class="text-[12px] font-semibold text-[#34415A]">
                            Contacted
                        </p>

                        <p class="mt-2 text-[32px] font-bold leading-none text-[#C78208]">
                            {{ number_format($contactedRequests) }}
                        </p>

                        <p class="mt-3 text-[10px] leading-5 text-[#7C879A]">
                            Conversations in progress
                        </p>
                    </div>

                </div>
            </div>

            {{-- Closed --}}
            <div class="relative overflow-hidden rounded-2xl border border-[#C8EBD9] bg-gradient-to-br from-white via-[#F5FFF9] to-[#E2F8ED] p-6 shadow-sm">

                <div class="absolute -bottom-10 -right-10 h-36 w-36 rounded-full bg-[#D9F4E7]/70"></div>

                <div class="relative flex items-start gap-4">

                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-[#E0F7EA] text-[#15985B]">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9"></circle>
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8 12 3 3 5-6"></path>
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <p class="text-[12px] font-semibold text-[#34415A]">
                            Closed Requests
                        </p>

                        <p class="mt-2 text-[32px] font-bold leading-none text-[#138C54]">
                            {{ number_format($closedRequests) }}
                        </p>

                        <p class="mt-3 text-[10px] leading-5 text-[#7C879A]">
                            Completed inquiries
                        </p>
                    </div>

                </div>
            </div>

        </div>

        {{-- Search & Filters Panel --}}
        <section class="mt-8 rounded-2xl border border-[#DFE8F5] bg-white/90 p-5 shadow-sm lg:p-6">

            {{-- Search --}}
            <form
                method="GET"
                action="{{ route('admin.contact-requests.index') }}"
                class="flex flex-col gap-3 sm:flex-row sm:items-center"
            >

                <input type="hidden" name="type" value="{{ $type }}">
                <input type="hidden" name="status" value="{{ $status }}">

                <div class="relative min-w-0 flex-1">

                    <svg class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-[#8591A5]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3"></path>
                    </svg>

                    <input
                        type="search"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search by name, email, company or phone number..."
                        class="h-12 w-full rounded-xl border border-[#DCE5F1] bg-white pl-12 pr-4 text-[12px] text-[#26354B] outline-none transition placeholder:text-[#9BA6B7] focus:border-[#397CF6] focus:ring-2 focus:ring-[#397CF6]/10"
                    >

                </div>

                <button
                    type="submit"
                    class="inline-flex h-12 items-center justify-center gap-2 rounded-xl bg-[#397CF6] px-7 text-[12px] font-semibold text-white shadow-sm transition hover:bg-[#2869E5]"
                >
                    Search
                </button>

                <a
                    href="{{ route('admin.contact-requests.index') }}"
                    class="inline-flex h-12 items-center justify-center gap-2 rounded-xl px-4 text-[12px] font-medium text-[#397CF6] transition hover:bg-[#F1F6FF]"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12a9 9 0 1 0 3-6"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v6h6"></path>
                    </svg>

                    Clear all
                </a>

            </form>

            {{-- Filter Groups --}}
            <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-2 xl:gap-8">

                {{-- Request Type --}}
                <div>
                    <div class="mb-3 flex items-center gap-2">

                        <svg class="h-5 w-5 text-[#397CF6]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 12 12 20 3 11V4h7l10 8Z"></path>
                            <circle cx="7.5" cy="7.5" r="1"></circle>
                        </svg>

                        <h3 class="text-[12px] font-semibold text-[#26354B]">
                            Request Type
                        </h3>

                    </div>

                    <div class="flex flex-wrap gap-2">

                        @foreach([
                            'all' => 'All Types',
                            'demo' => 'Demo Requests',
                            'general' => 'General Inquiries'
                        ] as $value => $label)

                            <a
                                href="{{ route('admin.contact-requests.index', [
                                    'type' => $value,
                                    'status' => $status,
                                    'search' => $search,
                                ]) }}"
                                class="inline-flex h-10 items-center justify-center rounded-full border px-5 text-[11px] font-semibold transition
                                    {{ $type === $value
                                        ? 'border-[#397CF6] bg-[#397CF6] text-white shadow-sm'
                                        : 'border-[#D3DEEE] bg-white text-[#34415A] hover:border-[#397CF6] hover:bg-[#F3F7FF]' }}"
                            >
                                {{ $label }}
                            </a>

                        @endforeach

                    </div>
                </div>

                {{-- Status --}}
                <div class="xl:border-l xl:border-[#E1E8F2] xl:pl-8">

                    <div class="mb-3 flex items-center gap-2">

                        <svg class="h-5 w-5 text-[#397CF6]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m12 3 9 5-9 5-9-5 9-5Z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" d="m3 12 9 5 9-5"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" d="m3 16 9 5 9-5"></path>
                        </svg>

                        <h3 class="text-[12px] font-semibold text-[#26354B]">
                            Status
                        </h3>

                    </div>

                    <div class="flex flex-wrap gap-2">

                        @foreach([
                            'all' => 'All Statuses',
                            'new' => 'New',
                            'contacted' => 'Contacted',
                            'closed' => 'Closed'
                        ] as $value => $label)

                            @php
                                $statusColors = match($value) {
                                    'new' => 'border-[#CFE2FF] bg-[#EFF6FF] text-[#286CF0]',
                                    'contacted' => 'border-[#F6E1B2] bg-[#FFF8E9] text-[#BC7D0B]',
                                    'closed' => 'border-[#C9EBD9] bg-[#EDFCF3] text-[#128C54]',
                                    default => 'border-[#D3DEEE] bg-white text-[#34415A]',
                                };

                                $dotColors = match($value) {
                                    'new' => 'bg-[#397CF6]',
                                    'contacted' => 'bg-[#D28B0C]',
                                    'closed' => 'bg-[#15985B]',
                                    default => 'bg-[#7C8CA5]',
                                };
                            @endphp

                            <a
                                href="{{ route('admin.contact-requests.index', [
                                    'type' => $type,
                                    'status' => $value,
                                    'search' => $search,
                                ]) }}"
                                class="inline-flex h-10 items-center justify-center gap-2 rounded-full border px-4 text-[11px] font-semibold transition
                                    {{ $status === $value
                                        ? 'border-[#397CF6] bg-[#397CF6] text-white shadow-sm'
                                        : $statusColors . ' hover:brightness-95' }}"
                            >
                                @if($value !== 'all')
                                    <span class="h-2 w-2 rounded-full {{ $status === $value ? 'bg-white' : $dotColors }}"></span>
                                @endif

                                {{ $label }}
                            </a>

                        @endforeach

                    </div>

                </div>

            </div>

        </section>

        {{-- Requests Table --}}
        <section class="mt-7 overflow-hidden rounded-2xl border border-[#DFE8F5] bg-white shadow-sm">

            <div class="overflow-x-auto">

                <table class="w-full min-w-[1150px] border-collapse text-left">

                    <thead class="bg-[#F5F8FD]">

                        <tr class="border-b border-[#E1E8F2] text-[10px] font-semibold uppercase tracking-wide text-[#637188]">

                            <th scope="col" class="px-5 py-5 font-semibold">
                                Name
                            </th>

                            <th scope="col" class="px-5 py-5 font-semibold">
                                Company
                            </th>

                            <th scope="col" class="px-5 py-5 font-semibold">
                                Contact Details
                            </th>

                            <th scope="col" class="px-5 py-5 font-semibold">
                                Type
                            </th>

                            <th scope="col" class="px-5 py-5 font-semibold">
                                Status
                            </th>

                            <th scope="col" class="px-5 py-5 font-semibold">
                                Submitted
                            </th>

                            <th scope="col" class="px-5 py-5 font-semibold">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-[#E8EDF5]">

                        @forelse($contactRequests as $contactRequest)

                            @php
                                $nameParts = preg_split(
                                    '/\s+/',
                                    trim($contactRequest->name ?? '')
                                );

                                $initials = collect($nameParts)
                                    ->filter()
                                    ->take(2)
                                    ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
                                    ->implode('');

                                $isDemo = $contactRequest->request_type === 'demo';

                                $statusLabel = match($contactRequest->status) {
                                    'new' => 'New',
                                    'contacted' => 'Contacted',
                                    'closed' => 'Closed',
                                    default => ucfirst($contactRequest->status),
                                };

                                $statusClass = match($contactRequest->status) {
                                    'new' => 'bg-[#E8F2FF] text-[#286CF0]',
                                    'contacted' => 'bg-[#FFF3DC] text-[#BD7C08]',
                                    'closed' => 'bg-[#E4F8ED] text-[#128C54]',
                                    default => 'bg-[#F0F0F0] text-[#777777]',
                                };

                                $statusDot = match($contactRequest->status) {
                                    'new' => 'bg-[#397CF6]',
                                    'contacted' => 'bg-[#D28B0C]',
                                    'closed' => 'bg-[#15985B]',
                                    default => 'bg-[#999999]',
                                };
                            @endphp

                            <tr class="transition hover:bg-[#F9FBFF]">

                                {{-- Name --}}
                                <td class="px-5 py-5 align-middle">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#E3EAF5] text-[11px] font-semibold text-[#34415A]">
                                            {{ $initials ?: 'C' }}
                                        </div>

                                        <div class="min-w-0">

                                            <p class="max-w-[170px] truncate text-[12px] font-semibold text-[#172235]">
                                                {{ $contactRequest->name }}
                                            </p>

                                            <p class="mt-1 font-mono text-[10px] text-[#9AA5B6]">
                                                ID #{{ $contactRequest->id }}
                                            </p>

                                        </div>

                                    </div>

                                </td>

                                {{-- Company --}}
                                <td class="px-5 py-5 align-middle">

                                    <p class="max-w-[150px] truncate text-[11px] font-medium text-[#34415A]">
                                        {{ $contactRequest->company ?: 'Not provided' }}
                                    </p>

                                    <p class="mt-2 max-w-[150px] truncate text-[10px] text-[#8A96A8]">
                                        {{ $contactRequest->country }}
                                    </p>

                                </td>

                                {{-- Contact Details --}}
                                <td class="px-5 py-5 align-middle">

                                    <div class="space-y-2">

                                        <div class="flex items-center gap-2">

                                            <svg class="h-4 w-4 shrink-0 text-[#7C8CA5]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m3 7 9 6 9-6"></path>
                                            </svg>

                                            <span class="max-w-[200px] truncate text-[11px] text-[#34415A]" title="{{ $contactRequest->email }}">
                                                {{ $contactRequest->email }}
                                            </span>

                                        </div>

                                        <div class="flex items-center gap-2">

                                            <svg class="h-4 w-4 shrink-0 text-[#7C8CA5]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M22 16.92v3a2 2 0 0 1-2.18 2A19.79 19.79 0 0 1 3.09 5.18 2 2 0 0 1 5.08 3h3a2 2 0 0 1 2 1.72l.45 2.81a2 2 0 0 1-.57 1.74L8.09 11a16 16 0 0 0 4.91 4.91l1.73-1.87a2 2 0 0 1 1.74-.57l2.81.45A2 2 0 0 1 22 16.92Z"></path>
                                            </svg>

                                            <span class="text-[11px] text-[#8A96A8]">
                                                {{ $contactRequest->phone ?: 'Not provided' }}
                                            </span>

                                        </div>

                                    </div>

                                </td>

                                {{-- Type --}}
                                <td class="px-5 py-5 align-middle">

                                    <span class="inline-flex whitespace-nowrap rounded-full px-3 py-2 text-[10px] font-semibold {{ $isDemo ? 'bg-[#E8F2FF] text-[#286CF0]' : 'bg-[#F1F3F7] text-[#59677B]' }}">
                                        {{ $isDemo ? 'Request a Demo' : 'General Inquiry' }}
                                    </span>

                                </td>

                                {{-- Status --}}
                                <td class="px-5 py-5 align-middle">

                                    <span class="inline-flex items-center gap-2 whitespace-nowrap rounded-full px-3 py-2 text-[10px] font-semibold {{ $statusClass }}">

                                        <span class="h-2 w-2 rounded-full {{ $statusDot }}"></span>

                                        {{ $statusLabel }}

                                    </span>

                                </td>

                                {{-- Submitted --}}
                                <td class="px-5 py-5 align-middle">

                                    <p class="whitespace-nowrap text-[11px] font-semibold text-[#34415A]">
                                        {{ $contactRequest->created_at?->format('M d, Y') ?? '—' }}
                                    </p>

                                    <p class="mt-2 text-[10px] text-[#8A96A8]">
                                        {{ $contactRequest->created_at?->format('H:i') ?? '' }}
                                    </p>

                                </td>

                                {{-- Action --}}
                                <td class="px-5 py-5 align-middle">

                                    <a
                                        href="{{ route('admin.contact-requests.show', $contactRequest) }}"
                                        class="inline-flex h-10 items-center justify-center gap-2 whitespace-nowrap rounded-xl border border-[#AFCBFF] bg-[#F3F8FF] px-4 text-[11px] font-semibold text-[#286CF0] transition hover:border-[#397CF6] hover:bg-[#E7F1FF]"
                                    >
                                        View Details

                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"></path>
                                        </svg>
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">

                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-[#EAF2FF] text-[#397CF6]">
                                        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                                            <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m3 7 9 6 9-6"></path>
                                        </svg>
                                    </div>

                                    <h3 class="mt-4 text-[14px] font-semibold text-[#26354B]">
                                        No contact requests found
                                    </h3>

                                    <p class="mt-2 text-[11px] text-[#8A96A8]">
                                        New contact and demo requests will appear here.
                                    </p>

                                    <a
                                        href="{{ route('admin.contact-requests.index') }}"
                                        class="mt-4 inline-flex text-[11px] font-semibold text-[#397CF6] hover:underline"
                                    >
                                        Clear all filters
                                    </a>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            <div class="flex flex-wrap items-center justify-between gap-4 border-t border-[#E1E8F2] bg-[#FBFCFF] px-6 py-5">

                <p class="text-[11px] text-[#748198]">
                    @if($contactRequests->total() > 0)
                        Showing {{ $contactRequests->firstItem() }}
                        – {{ $contactRequests->lastItem() }}
                        of {{ $contactRequests->total() }} results
                    @else
                        Showing 0 results
                    @endif
                </p>

                <div class="flex items-center gap-2">

                    @if($contactRequests->onFirstPage())
                        <span class="inline-flex h-9 items-center rounded-lg border border-[#E1E8F2] px-4 text-[11px] text-[#B4BECC]">
                            Previous
                        </span>
                    @else
                        <a
                            href="{{ $contactRequests->previousPageUrl() }}"
                            class="inline-flex h-9 items-center rounded-lg border border-[#D3DEEE] bg-white px-4 text-[11px] text-[#34415A] hover:border-[#397CF6]"
                        >
                            Previous
                        </a>
                    @endif

                    <span class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg border border-[#397CF6] bg-[#EFF5FF] px-3 text-[11px] font-semibold text-[#286CF0]">
                        {{ $contactRequests->currentPage() }}
                    </span>

                    @if($contactRequests->hasMorePages())
                        <a
                            href="{{ $contactRequests->nextPageUrl() }}"
                            class="inline-flex h-9 items-center rounded-lg border border-[#D3DEEE] bg-white px-4 text-[11px] text-[#34415A] hover:border-[#397CF6]"
                        >
                            Next
                        </a>
                    @else
                        <span class="inline-flex h-9 items-center rounded-lg border border-[#E1E8F2] px-4 text-[11px] text-[#B4BECC]">
                            Next
                        </span>
                    @endif

                </div>

            </div>

        </section>

    </main>

</div>

@endsection
