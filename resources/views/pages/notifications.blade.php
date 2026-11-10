
@extends('layouts.app')

@section('content')
@php
    $isAdmin = auth()->user()->role === 'admin';

    $dashboardRoute = $isAdmin
        ? route('admin.dashboard')
        : route('dashboard');
@endphp

<div class="flex min-h-screen w-full bg-[#F5F8FF]">

    @if ($isAdmin)
        @include('partials.admin-sidebar')
    @else
        @include('partials.user-sidebar')
    @endif

    <main class="min-w-0 flex-1 bg-gradient-to-br from-[#DDEAFF] via-[#F4F8FF] to-white px-5 py-8 lg:px-9 lg:py-10">

        <div class="flex flex-wrap items-center justify-between gap-4">

            <div>
                <a
                    href="{{ $dashboardRoute }}"
                    class="text-[11px] font-semibold text-[#3478FF] hover:underline"
                >
                    ← Back to Dashboard
                </a>

                <h1 class="mt-4 text-[29px] font-bold tracking-[-1px] text-[#0B1B39]">
                    Notifications
                </h1>

                <p class="mt-2 text-[12px] text-[#7184A5]">
                    Stay informed about your research and account activity.
                </p>
            </div>

            @if (auth()->user()->unreadNotifications()->exists())
                <form
                    method="POST"
                    action="{{ route('notifications.read-all') }}"
                >
                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        class="rounded-[11px] bg-[#397CF6] px-5 py-3 text-[11px] font-bold text-white transition hover:bg-[#2869E3]"
                    >
                        Mark All as Read
                    </button>
                </form>
            @endif
        </div>

        @if (session('success'))
            <div class="mt-6 rounded-[12px] border border-[#BCEAD2] bg-[#EDFCF4] px-5 py-4 text-[12px] font-medium text-[#15845B]">
                {{ session('success') }}
            </div>
        @endif

        <section class="mt-8 overflow-hidden rounded-[19px] border border-[#DFE8F6] bg-white shadow-[0_2px_5px_rgba(30,64,120,0.06)]">

            <div class="border-b border-[#EDF1F8] px-6 py-5">
                <h2 class="text-[14px] font-bold text-[#102243]">
                    All Notifications
                </h2>

                <p class="mt-1 text-[11px] text-[#8A9AB3]">
                    Your notification history
                </p>
            </div>

            @forelse ($notifications as $notification)
                @php
                    $data = $notification->data;

                    $title = $data['title'] ?? 'Notification';
                    $message = $data['message'] ?? '';
                    $url = $data['url'] ?? null;
                    $isUnread = $notification->read_at === null;
                @endphp

                <div class="flex flex-wrap items-start justify-between gap-4 border-b border-[#EDF1F8] px-6 py-5 last:border-b-0 {{ $isUnread ? 'bg-[#F5F9FF]' : 'bg-white' }}">

                    <div class="flex min-w-0 flex-1 items-start gap-4">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-[12px] bg-[#EAF2FF] text-[#3478FF]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path d="M18 8a6 6 0 00-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/>
                            </svg>
                        </div>

                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="break-words text-[13px] font-bold text-[#102243]">
                                    {{ $title }}
                                </h3>

                                @if ($isUnread)
                                    <span class="rounded-full bg-[#E8F1FF] px-2.5 py-1 text-[9px] font-bold text-[#3478FF]">
                                        New
                                    </span>
                                @endif
                            </div>

                            <p class="mt-2 break-words text-[11px] leading-[1.8] text-[#7184A5]">
                                {{ $message }}
                            </p>

                            <p class="mt-3 text-[10px] text-[#9AA9BD]">
                                {{ $notification->created_at?->diffForHumans() }}
                            </p>

                            @if ($url)
                                <a
                                    href="{{ $url }}"
                                    class="mt-3 inline-block text-[11px] font-bold text-[#3478FF] hover:underline"
                                >
                                    View Details →
                                </a>
                            @endif
                        </div>
                    </div>

                    @if ($isUnread)
                        <form
                            method="POST"
                            action="{{ route('notifications.read', $notification->id) }}"
                        >
                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="rounded-[10px] border border-[#DCE5F3] bg-white px-4 py-2.5 text-[10px] font-bold text-[#516581] transition hover:border-[#3478FF] hover:text-[#3478FF]"
                            >
                                Mark as Read
                            </button>
                        </form>
                    @endif
                </div>

            @empty
                <div class="px-6 py-16 text-center">
                    <h3 class="text-[15px] font-bold text-[#102243]">
                        You're all caught up!
                    </h3>

                    <p class="mt-3 text-[11px] text-[#7184A5]">
                        You don't have any notifications yet.
                    </p>
                </div>
            @endforelse

        </section>

        @if ($notifications->hasPages())
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif

    </main>
</div>
@endsection
