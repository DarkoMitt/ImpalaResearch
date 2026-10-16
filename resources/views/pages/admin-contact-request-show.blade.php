
@extends('layouts.app')

@section('content')

@php
    $isDemo = $contactRequest->request_type === 'demo';

    $statusLabel = match ($contactRequest->status) {
        'new' => 'New',
        'contacted' => 'Contacted',
        'closed' => 'Closed',
        default => ucfirst($contactRequest->status),
    };

    $statusColors = match ($contactRequest->status) {
        'new' => 'bg-blue-50 text-blue-700 ring-blue-200',
        'contacted' => 'bg-amber-50 text-amber-700 ring-amber-200',
        'closed' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        default => 'bg-slate-50 text-slate-600 ring-slate-200',
    };

    $statusDot = match ($contactRequest->status) {
        'new' => 'bg-blue-500',
        'contacted' => 'bg-amber-500',
        'closed' => 'bg-emerald-500',
        default => 'bg-slate-400',
    };

    $nameParts = preg_split('/\s+/', trim($contactRequest->name ?? ''));

    $initials = collect($nameParts)
        ->filter()
        ->take(2)
        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
        ->implode('');

    $currentStatus = old('status', $contactRequest->status);
@endphp

<div class="flex min-h-screen w-full bg-white">

    @include('partials.admin-sidebar')

    <main class="min-h-screen min-w-0 flex-1 bg-gradient-to-br from-[#DCEAFF] via-[#F7FAFF] to-white px-5 py-9 lg:px-10">

        {{-- Breadcrumb --}}
        <nav class="mb-7 flex flex-wrap items-center gap-2 text-xs text-slate-400">

            <a href="{{ route('admin.dashboard') }}"
               class="transition hover:text-blue-600">
                Admin Dashboard
            </a>

            <span class="text-slate-300">/</span>

            <a href="{{ route('admin.contact-requests.index') }}"
               class="transition hover:text-blue-600">
                Contact & Demo
            </a>

            <span class="text-slate-300">/</span>

            <span class="font-semibold text-slate-700">
                Request #{{ $contactRequest->id }}
            </span>

        </nav>

        {{-- Page Header --}}
        <div class="flex flex-wrap items-start justify-between gap-5">

            <div>
                <div class="flex flex-wrap items-center gap-3">

                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 md:text-[28px]">
                        Request Details
                    </h1>

                    <span class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-semibold ring-1 {{ $statusColors }}">
                        <span class="h-2 w-2 rounded-full {{ $statusDot }}"></span>
                        {{ $statusLabel }}
                    </span>

                </div>

                <p class="mt-2 text-xs text-slate-500">
                    Review inquiry information and manage this request.
                </p>
            </div>

            <a href="{{ route('admin.contact-requests.index') }}"
               class="inline-flex h-11 items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 text-xs font-semibold text-slate-700 shadow-sm transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-600">

                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                     stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M19 12H5m6 6-6-6 6-6"></path>
                </svg>

                Back to Requests
            </a>

        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div role="status"
                 class="mt-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-xs font-medium text-emerald-700">

                <svg class="h-5 w-5 shrink-0" fill="none"
                     stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9"></circle>
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="m8 12 3 3 5-6"></path>
                </svg>

                {{ session('success') }}
            </div>
        @endif

        {{-- Validation Errors --}}
        @if($errors->any())
            <div role="alert"
                 class="mt-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-xs text-red-700">

                <p class="font-semibold">Please review the following errors:</p>

                <ul class="mt-2 list-inside list-disc space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Summary Cards --}}
        <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2 2xl:grid-cols-3">

            {{-- Request ID --}}
            <div class="relative overflow-hidden rounded-2xl border border-blue-100 bg-gradient-to-br from-white to-blue-50 p-5 shadow-sm">

                <div class="absolute -bottom-12 -right-12 h-36 w-36 rounded-full bg-blue-100/60"></div>

                <div class="relative flex items-center gap-4">

                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                        <svg class="h-6 w-6" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             viewBox="0 0 24 24">
                            <rect x="4" y="4" width="16" height="16" rx="3"></rect>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 9h6M9 13h6M9 17h3"></path>
                        </svg>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-slate-500">
                            Request ID
                        </p>

                        <p class="mt-1 text-2xl font-bold text-slate-900">
                            #{{ $contactRequest->id }}
                        </p>
                    </div>

                </div>
            </div>

            {{-- Request Type --}}
            <div class="relative overflow-hidden rounded-2xl border border-violet-100 bg-gradient-to-br from-white to-violet-50 p-5 shadow-sm">

                <div class="absolute -bottom-12 -right-12 h-36 w-36 rounded-full bg-violet-100/60"></div>

                <div class="relative flex items-center gap-4">

                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-violet-600">
                        <svg class="h-6 w-6" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             viewBox="0 0 24 24">
                            <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="m3 7 9 6 9-6"></path>
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <p class="text-xs font-medium text-slate-500">
                            Request Type
                        </p>

                        <p class="mt-1 text-lg font-bold text-slate-900">
                            {{ $isDemo ? 'Request a Demo' : 'General Inquiry' }}
                        </p>
                    </div>

                </div>
            </div>

            {{-- Submitted Date --}}
            <div class="relative overflow-hidden rounded-2xl border border-emerald-100 bg-gradient-to-br from-white to-emerald-50 p-5 shadow-sm sm:col-span-2 2xl:col-span-1">

                <div class="absolute -bottom-12 -right-12 h-36 w-36 rounded-full bg-emerald-100/60"></div>

                <div class="relative flex items-center gap-4">

                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                        <svg class="h-6 w-6" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             viewBox="0 0 24 24">
                            <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M7 3v4m10-4v4M3 10h18"></path>
                        </svg>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-slate-500">
                            Submitted On
                        </p>

                        <p class="mt-1 text-lg font-bold text-slate-900">
                            {{ $contactRequest->created_at?->format('M d, Y') ?? '—' }}
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            {{ $contactRequest->created_at?->format('H:i') ?? '' }}
                        </p>
                    </div>

                </div>
            </div>

        </div>

        {{-- Content Layout --}}
        <div class="mt-7 grid grid-cols-1 items-start gap-6 2xl:grid-cols-[minmax(0,1.65fr)_minmax(340px,1fr)]">

            {{-- LEFT SIDE --}}
            <div class="min-w-0 space-y-6">

                {{-- Contact Information --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">

                    <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 bg-slate-50/60 px-6 py-5">

                        <div class="flex items-center gap-3">

                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                                <svg class="h-5 w-5" fill="none"
                                     stroke="currentColor" stroke-width="1.8"
                                     viewBox="0 0 24 24">
                                    <circle cx="12" cy="8" r="4"></circle>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M4 21a8 8 0 0 1 16 0"></path>
                                </svg>
                            </div>

                            <div>
                                <h2 class="text-sm font-bold text-slate-900">
                                    Contact Information
                                </h2>

                                <p class="mt-1 text-xs text-slate-400">
                                    Details provided by the requester
                                </p>
                            </div>

                        </div>

                        <span class="rounded-full bg-blue-50 px-3 py-1.5 text-[10px] font-semibold text-blue-600">
                            Customer Details
                        </span>

                    </div>

                    <div class="p-6">

                        {{-- Profile --}}
                        <div class="flex items-center gap-4 rounded-xl bg-gradient-to-r from-blue-50 to-slate-50 p-5">

                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-blue-100 text-lg font-bold text-blue-600">
                                {{ $initials ?: 'C' }}
                            </div>

                            <div class="min-w-0">

                                <p class="break-words text-base font-bold text-slate-900">
                                    {{ $contactRequest->name }}
                                </p>

                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $isDemo ? 'Demo Requester' : 'General Inquiry Contact' }}
                                </p>

                            </div>

                        </div>

                        {{-- Contact Details --}}
                        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">

                            {{-- Email --}}
                            <div class="min-w-0 rounded-xl bg-[#F8FAFD] p-4 ring-1 ring-slate-100">

                                <div class="flex items-center gap-2 text-blue-600">

                                    <svg class="h-4 w-4 shrink-0" fill="none"
                                         stroke="currentColor" stroke-width="1.8"
                                         viewBox="0 0 24 24">
                                        <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="m3 7 9 6 9-6"></path>
                                    </svg>

                                    <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-500">
                                        Email Address
                                    </span>
                                </div>

                                <a href="mailto:{{ $contactRequest->email }}"
                                   class="mt-3 block break-all text-xs font-semibold text-blue-600 hover:underline">
                                    {{ $contactRequest->email }}
                                </a>

                            </div>

                            {{-- Phone --}}
                            <div class="min-w-0 rounded-xl bg-[#F8FAFD] p-4 ring-1 ring-slate-100">

                                <div class="flex items-center gap-2 text-blue-600">

                                    <svg class="h-4 w-4 shrink-0" fill="none"
                                         stroke="currentColor" stroke-width="1.8"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M22 16.92v3a2 2 0 0 1-2.18 2A19.79 19.79 0 0 1 3.09 5.18 2 2 0 0 1 5.08 3h3a2 2 0 0 1 2 1.72l.45 2.81a2 2 0 0 1-.57 1.74L8.09 11a16 16 0 0 0 4.91 4.91l1.73-1.87a2 2 0 0 1 1.74-.57l2.81.45A2 2 0 0 1 22 16.92Z"></path>
                                    </svg>

                                    <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-500">
                                        Phone Number
                                    </span>
                                </div>

                                @if($contactRequest->phone)
                                    <a href="tel:{{ $contactRequest->phone }}"
                                       class="mt-3 block text-xs font-semibold text-slate-800 hover:text-blue-600">
                                        {{ $contactRequest->phone }}
                                    </a>
                                @else
                                    <p class="mt-3 text-xs text-slate-400">
                                        Not provided
                                    </p>
                                @endif

                            </div>

                            {{-- Country --}}
                            <div class="min-w-0 rounded-xl bg-[#F8FAFD] p-4 ring-1 ring-slate-100">

                                <div class="flex items-center gap-2 text-blue-600">

                                    <svg class="h-4 w-4 shrink-0" fill="none"
                                         stroke="currentColor" stroke-width="1.8"
                                         viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="9"></circle>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M3 12h18M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18"></path>
                                    </svg>

                                    <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-500">
                                        Country
                                    </span>
                                </div>

                                <p class="mt-3 text-xs font-semibold text-slate-800">
                                    {{ $contactRequest->country ?: 'Not provided' }}
                                </p>

                            </div>

                            {{-- Company --}}
                            <div class="min-w-0 rounded-xl bg-[#F8FAFD] p-4 ring-1 ring-slate-100">

                                <div class="flex items-center gap-2 text-blue-600">

                                    <svg class="h-4 w-4 shrink-0" fill="none"
                                         stroke="currentColor" stroke-width="1.8"
                                         viewBox="0 0 24 24">
                                        <rect x="4" y="7" width="16" height="14" rx="2"></rect>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M9 7V4h6v3M4 12h16"></path>
                                    </svg>

                                    <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-500">
                                        Company / Organization
                                    </span>
                                </div>

                                <p class="mt-3 break-words text-xs font-semibold text-slate-800">
                                    {{ $contactRequest->company ?: 'Not provided' }}
                                </p>

                            </div>

                        </div>

                    </div>

                </section>

                {{-- Customer Message --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">

                    <div class="flex items-center gap-3 border-b border-slate-100 bg-slate-50/60 px-6 py-5">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-violet-600">
                            <svg class="h-5 w-5" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M8 10h8m-8 4h5m-9 6 4-4h11a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v11a2 2 0 002 2h1z"></path>
                            </svg>
                        </div>

                        <div>
                            <h2 class="text-sm font-bold text-slate-900">
                                Customer Message
                            </h2>

                            <p class="mt-1 text-xs text-slate-400">
                                Original message submitted with this request
                            </p>
                        </div>

                    </div>

                    <div class="p-6">

                        <div class="relative rounded-xl bg-gradient-to-br from-[#F8FAFF] to-[#F0F5FF] p-6 ring-1 ring-blue-100">

                            <div class="mb-4 flex h-9 w-9 items-center justify-center rounded-lg bg-blue-100 text-blue-500">
                                <svg class="h-5 w-5" fill="none"
                                     stroke="currentColor" stroke-width="1.8"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M8 10h8m-8 4h5m-9 6 4-4h11a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v11a2 2 0 002 2h1z"></path>
                                </svg>
                            </div>

                            <p class="whitespace-pre-wrap break-words text-sm leading-7 text-slate-700">{{ $contactRequest->message ?: 'No message provided.' }}</p>

                        </div>

                    </div>

                </section>

                {{-- Request Timeline --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">

                    <div class="flex items-center gap-3 border-b border-slate-100 bg-slate-50/60 px-6 py-5">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                            <svg class="h-5 w-5" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="9"></circle>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 7v5l3 2"></path>
                            </svg>
                        </div>

                        <div>
                            <h2 class="text-sm font-bold text-slate-900">
                                Request Timeline
                            </h2>

                            <p class="mt-1 text-xs text-slate-400">
                                Important milestones for this inquiry
                            </p>
                        </div>

                    </div>

                    <div class="px-6 py-7">

                        <div class="space-y-6">

                            {{-- Submitted --}}
                            <div class="flex items-start gap-4">

                                <div class="flex w-8 shrink-0 flex-col items-center">

                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                                        <svg class="h-4 w-4" fill="none"
                                             stroke="currentColor" stroke-width="2"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="m5 12 4 4L19 6"></path>
                                        </svg>
                                    </div>

                                </div>

                                <div class="min-w-0 pt-1">

                                    <p class="text-xs font-semibold text-slate-900">
                                        Request Submitted
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $contactRequest->created_at?->format('M d, Y \a\t H:i') ?? 'Date unavailable' }}
                                    </p>

                                </div>

                            </div>

                            {{-- Contacted --}}
                            @if($contactRequest->contacted_at)
                                <div class="flex items-start gap-4">

                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                                        <svg class="h-4 w-4" fill="none"
                                             stroke="currentColor" stroke-width="2"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M12 7v5l3 2"></path>
                                            <circle cx="12" cy="12" r="9"></circle>
                                        </svg>
                                    </div>

                                    <div class="min-w-0 pt-1">

                                        <p class="text-xs font-semibold text-slate-900">
                                            Customer Contacted
                                        </p>

                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ \Illuminate\Support\Carbon::parse($contactRequest->contacted_at)->format('M d, Y \a\t H:i') }}
                                        </p>

                                    </div>

                                </div>
                            @endif

                            {{-- Closed --}}
                            @if($contactRequest->closed_at)
                                <div class="flex items-start gap-4">

                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                                        <svg class="h-4 w-4" fill="none"
                                             stroke="currentColor" stroke-width="2"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="m5 12 4 4L19 6"></path>
                                        </svg>
                                    </div>

                                    <div class="min-w-0 pt-1">

                                        <p class="text-xs font-semibold text-slate-900">
                                            Request Closed
                                        </p>

                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ \Illuminate\Support\Carbon::parse($contactRequest->closed_at)->format('M d, Y \a\t H:i') }}
                                        </p>

                                    </div>

                                </div>
                            @endif

                            {{-- Waiting --}}
                            @if(!$contactRequest->contacted_at && !$contactRequest->closed_at)
                                <div class="flex items-start gap-4">

                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                        <svg class="h-4 w-4" fill="none"
                                             stroke="currentColor" stroke-width="1.8"
                                             viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="9"></circle>
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M12 7v5"></path>
                                        </svg>
                                    </div>

                                    <div class="min-w-0 pt-1">

                                        <p class="text-xs font-semibold text-slate-600">
                                            Awaiting first response
                                        </p>

                                        <p class="mt-1 text-xs text-slate-400">
                                            No further activity recorded yet.
                                        </p>

                                    </div>

                                </div>
                            @endif

                        </div>

                    </div>

                </section>

            </div>

            {{-- RIGHT SIDE --}}
            <div class="min-w-0 space-y-6">

                {{-- Manage Request --}}
                <section class="overflow-hidden rounded-2xl border border-blue-100 bg-white shadow-sm">

                    <div class="flex items-center gap-3 border-b border-blue-100 bg-gradient-to-r from-blue-50 to-white px-6 py-5">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                            <svg class="h-5 w-5" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 20h9"></path>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M16.5 3.5a2.12 2.12 0 0 1 3 3L10 16l-4 1 1-4 9.5-9.5Z"></path>
                            </svg>
                        </div>

                        <div>
                            <h2 class="text-sm font-bold text-slate-900">
                                Manage Request
                            </h2>

                            <p class="mt-1 text-xs text-slate-400">
                                Update status and internal notes
                            </p>
                        </div>

                    </div>

                    <form method="POST"
                          action="{{ route('admin.contact-requests.update', $contactRequest) }}"
                          class="p-6"
                          x-data="{ selectedStatus: @js($currentStatus) }">

                        @csrf
                        @method('PATCH')

                        <label class="mb-4 block text-xs font-semibold text-slate-800">
                            Request Status
                        </label>

                        <div class="space-y-3">

                            {{-- New --}}
                            <label class="flex cursor-pointer items-center gap-3 rounded-xl p-4 ring-1 transition"
                                   :class="selectedStatus === 'new'
                                       ? 'bg-blue-50 ring-blue-300'
                                       : 'bg-slate-50 ring-slate-100 hover:ring-blue-200'">

                                <input type="radio"
                                       name="status"
                                       value="new"
                                       x-model="selectedStatus"
                                       class="h-4 w-4 shrink-0 accent-blue-600"
                                       required>

                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                                    <svg class="h-4 w-4" fill="none"
                                         stroke="currentColor" stroke-width="2"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M12 5v14m-7-7h14"></path>
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-900">
                                        New
                                    </p>

                                    <p class="mt-1 text-[11px] text-slate-500">
                                        Awaiting response
                                    </p>
                                </div>

                            </label>

                            {{-- Contacted --}}
                            <label class="flex cursor-pointer items-center gap-3 rounded-xl p-4 ring-1 transition"
                                   :class="selectedStatus === 'contacted'
                                       ? 'bg-amber-50 ring-amber-300'
                                       : 'bg-slate-50 ring-slate-100 hover:ring-amber-200'">

                                <input type="radio"
                                       name="status"
                                       value="contacted"
                                       x-model="selectedStatus"
                                       class="h-4 w-4 shrink-0 accent-amber-600">

                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-600">
                                    <svg class="h-4 w-4" fill="none"
                                         stroke="currentColor" stroke-width="2"
                                         viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="9"></circle>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M12 7v5l3 2"></path>
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-900">
                                        Contacted
                                    </p>

                                    <p class="mt-1 text-[11px] text-slate-500">
                                        Conversation started
                                    </p>
                                </div>

                            </label>

                            {{-- Closed --}}
                            <label class="flex cursor-pointer items-center gap-3 rounded-xl p-4 ring-1 transition"
                                   :class="selectedStatus === 'closed'
                                       ? 'bg-emerald-50 ring-emerald-300'
                                       : 'bg-slate-50 ring-slate-100 hover:ring-emerald-200'">

                                <input type="radio"
                                       name="status"
                                       value="closed"
                                       x-model="selectedStatus"
                                       class="h-4 w-4 shrink-0 accent-emerald-600">

                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                                    <svg class="h-4 w-4" fill="none"
                                         stroke="currentColor" stroke-width="2"
                                         viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="9"></circle>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="m8 12 3 3 5-6"></path>
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-900">
                                        Closed
                                    </p>

                                    <p class="mt-1 text-[11px] text-slate-500">
                                        Inquiry completed
                                    </p>
                                </div>

                            </label>

                        </div>

                        {{-- Admin Notes --}}
                        <div class="mt-7">

                            <div class="mb-3 flex items-center justify-between gap-3">

                                <label for="admin-notes"
                                       class="text-xs font-semibold text-slate-800">
                                    Admin Notes
                                </label>

                                <span class="rounded-full bg-slate-100 px-3 py-1 text-[10px] font-semibold text-slate-500">
                                    Private
                                </span>

                            </div>

                            <textarea id="admin-notes"
                                      name="admin_notes"
                                      rows="6"
                                      maxlength="10000"
                                      placeholder="Add internal notes about this request..."
                                      class="block w-full resize-y rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-xs leading-6 text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-400 focus:ring-2 focus:ring-blue-100">{{ old('admin_notes', $contactRequest->admin_notes) }}</textarea>

                            <p class="mt-3 flex items-start gap-2 text-[11px] leading-5 text-slate-400">

                                <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none"
                                     stroke="currentColor" stroke-width="1.8"
                                     viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="9"></circle>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 11v5m0-8h.01"></path>
                                </svg>

                                These notes are visible only to administrators.
                            </p>

                        </div>

                        <button type="submit"
                                class="mt-7 flex min-h-12 w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-xs font-bold text-white shadow-md shadow-blue-200/60 transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100">

                            <svg class="h-4 w-4" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M17 21v-8H7v8M7 3v5h8"></path>
                            </svg>

                            Save Changes
                        </button>

                    </form>

                </section>

                {{-- Quick Actions --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">

                    <div class="border-b border-slate-100 bg-slate-50/60 px-6 py-5">

                        <h2 class="text-sm font-bold text-slate-900">
                            Quick Actions
                        </h2>

                        <p class="mt-1 text-xs text-slate-400">
                            Reach out to this requester
                        </p>

                    </div>

                    <div class="space-y-3 p-5">

                        {{-- Send Email - FIXED --}}
                        <a href="mailto:{{ $contactRequest->email }}"
                           class="flex min-h-12 w-full items-center justify-between gap-3 rounded-xl bg-blue-600 px-5 py-3 text-xs font-bold text-white shadow-md shadow-blue-200/60 transition hover:bg-blue-700">

                            <span class="flex items-center gap-3">

                                <svg class="h-5 w-5 shrink-0" fill="none"
                                     stroke="currentColor" stroke-width="1.8"
                                     viewBox="0 0 24 24">
                                    <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m3 7 9 6 9-6"></path>
                                </svg>

                                Send Email
                            </span>

                            <svg class="h-4 w-4 shrink-0" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M7 17 17 7M7 7h10v10"></path>
                            </svg>

                        </a>

                        @if($contactRequest->phone)

                            {{-- Call Customer --}}
                            <a href="tel:{{ $contactRequest->phone }}"
                               class="flex min-h-12 w-full items-center justify-between gap-3 rounded-xl bg-emerald-600 px-5 py-3 text-xs font-bold text-white shadow-md shadow-emerald-200/60 transition hover:bg-emerald-700">

                                <span class="flex items-center gap-3">

                                    <svg class="h-5 w-5 shrink-0" fill="none"
                                         stroke="currentColor" stroke-width="1.8"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M22 16.92v3a2 2 0 0 1-2.18 2A19.79 19.79 0 0 1 3.09 5.18 2 2 0 0 1 5.08 3h3a2 2 0 0 1 2 1.72l.45 2.81a2 2 0 0 1-.57 1.74L8.09 11a16 16 0 0 0 4.91 4.91l1.73-1.87a2 2 0 0 1 1.74-.57l2.81.45A2 2 0 0 1 22 16.92Z"></path>
                                    </svg>

                                    Call Customer
                                </span>

                                <svg class="h-4 w-4 shrink-0" fill="none"
                                     stroke="currentColor" stroke-width="1.8"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M7 17 17 7M7 7h10v10"></path>
                                </svg>

                            </a>

                        @endif

                    </div>

                </section>

                {{-- Good to Know --}}
                <div class="rounded-2xl bg-gradient-to-br from-blue-50 to-[#E8F2FF] p-5 ring-1 ring-blue-100">

                    <div class="flex items-start gap-3">

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                            <svg class="h-5 w-5" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="9"></circle>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 11v5m0-8h.01"></path>
                            </svg>
                        </div>

                        <div>
                            <p class="text-xs font-bold text-slate-800">
                                Good to know
                            </p>

                            <p class="mt-2 text-xs leading-6 text-slate-600">
                                Updating the request status or saving notes will not automatically send an email to the customer. Communication can be handled separately.
                            </p>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </main>

</div>

@endsection
