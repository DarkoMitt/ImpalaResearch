
@php
    $navigationItems = [
        [
            'label' => 'Overview',
            'route' => 'admin.dashboard',
            'active' => request()->routeIs('admin.dashboard'),
            'icon' => 'overview',
        ],
        [
            'label' => 'Users',
            'route' => 'admin.users',
            'active' => request()->routeIs('admin.users', 'admin.users.*'),
            'icon' => 'users',
        ],
        [
            'label' => 'Research Requests',
            'route' => 'admin.research-requests.index',
            'active' => request()->routeIs('admin.research-requests.*'),
            'icon' => 'research',
        ],
        [
            'label' => 'Contact & Demo',
            'route' => 'admin.contact-requests.index',
            'active' => request()->routeIs('admin.contact-requests.*'),
            'icon' => 'contact',
        ],
    ];
@endphp

<aside
    class="sticky top-0 z-30 flex h-screen w-[235px] shrink-0 flex-col overflow-hidden border-r border-[#E7EDF7] bg-white shadow-[4px_0_24px_rgba(31,65,120,0.025)]"
>

    {{-- Brand --}}
    <div class="shrink-0 px-6 pb-7 pt-9">

        <a
            href="{{ route('admin.dashboard') }}"
            class="inline-flex items-center transition hover:opacity-80"
            aria-label="Impala Admin Dashboard"
        >
            <img
                src="{{ asset('images/logo.svg') }}"
                alt="Impala"
                class="h-[36px] w-auto"
            >
        </a>

        <div class="mt-7 flex items-center gap-3 rounded-xl border border-[#DFEAF9] bg-gradient-to-r from-[#F2F7FF] to-white px-3 py-3">

            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#E0ECFF] text-[#3478F6]">

                <svg
                    class="h-[19px] w-[19px]"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    viewBox="0 0 24 24"
                >
                    <rect x="3" y="3" width="18" height="18" rx="4"></rect>
                    <path d="M8 8h8M8 12h8M8 16h5"></path>
                </svg>

            </div>

            <div class="min-w-0">
                <p class="text-[11px] font-bold text-[#172235]">
                    Admin Workspace
                </p>

                <p class="mt-1 text-[10px] text-[#8A96A8]">
                    Impala Research
                </p>
            </div>

        </div>

    </div>

    {{-- Navigation --}}
    <div class="min-h-0 flex-1 overflow-y-auto px-4 pb-5">

        <div class="mb-3 flex items-center gap-3 px-3">

            <p class="shrink-0 text-[10px] font-bold uppercase tracking-[0.12em] text-[#9AA6B7]">
                Workspace
            </p>

            <span class="h-px flex-1 bg-[#E9EEF6]"></span>

        </div>

        <nav class="space-y-1.5" aria-label="Admin navigation">

            @foreach($navigationItems as $item)

                <a
                    href="{{ route($item['route']) }}"
                    @if($item['active'])
                        aria-current="page"
                    @endif
                    class="group relative flex min-h-11 items-center gap-3 rounded-xl px-3 py-3 text-[11px] font-semibold transition-all duration-200
                        {{ $item['active']
                            ? 'bg-[#EDF4FF] text-[#3478F6] shadow-[inset_0_0_0_1px_rgba(80,139,240,0.10)]'
                            : 'text-[#657389] hover:bg-[#F5F8FD] hover:text-[#3478F6]' }}"
                >

                    {{-- Active Indicator --}}
                    @if($item['active'])
                        <span class="absolute bottom-2 left-0 top-2 w-[3px] rounded-r-full bg-[#397CF6]"></span>
                    @endif

                    {{-- Icon --}}
                    <span
                        class="flex h-5 w-5 shrink-0 items-center justify-center
                            {{ $item['active']
                                ? 'text-[#3478F6]'
                                : 'text-[#91A0B5] transition-colors group-hover:text-[#3478F6]' }}"
                    >

                        @if($item['icon'] === 'overview')

                            <svg
                                class="h-[19px] w-[19px]"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                viewBox="0 0 24 24"
                            >
                                <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                                <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                                <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                                <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                            </svg>

                        @elseif($item['icon'] === 'users')

                            <svg
                                class="h-[19px] w-[19px]"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                viewBox="0 0 24 24"
                            >
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>

                        @elseif($item['icon'] === 'research')

                            <svg
                                class="h-[19px] w-[19px]"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                viewBox="0 0 24 24"
                            >
                                <rect x="4" y="3" width="16" height="18" rx="2"></rect>
                                <path d="M8 8h8M8 12h8M8 16h5"></path>
                                <path d="M9 3v-1h6v1"></path>
                            </svg>

                        @elseif($item['icon'] === 'contact')

                            <svg
                                class="h-[19px] w-[19px]"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                viewBox="0 0 24 24"
                            >
                                <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                <path d="m3 7 9 6 9-6"></path>
                            </svg>

                        @endif

                    </span>

                    <span class="min-w-0 flex-1 leading-5">
                        {{ $item['label'] }}
                    </span>

                    @if($item['active'])
                        <svg
                            class="h-3.5 w-3.5 shrink-0 text-[#3478F6]"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            viewBox="0 0 24 24"
                        >
                            <path d="m9 18 6-6-6-6"></path>
                        </svg>
                    @endif

                </a>

            @endforeach

            {{-- Website CMS: Not implemented yet --}}
            <div
                class="flex min-h-11 items-center gap-3 rounded-xl px-3 py-3 text-[11px] font-semibold text-[#A5AFBF]"
                title="Coming soon"
            >

                <span class="flex h-5 w-5 shrink-0 items-center justify-center">

                    <svg
                        class="h-[19px] w-[19px]"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        viewBox="0 0 24 24"
                    >
                        <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                        <path d="M3 9h18"></path>
                        <path d="M8 6.5h.01M11 6.5h.01"></path>
                        <path d="m9 15 2 2 4-4"></path>
                    </svg>

                </span>

                <span class="min-w-0 flex-1">
                    Website CMS
                </span>

                <span class="rounded-full bg-[#F1F4F8] px-2 py-1 text-[9px] font-semibold text-[#9AA6B7]">
                    Soon
                </span>

            </div>

        </nav>

    </div>

    {{-- Bottom Section --}}
    <div class="shrink-0 border-t border-[#E9EEF6] bg-white px-4 pb-6 pt-4">

        {{-- Admin Profile --}}
        <div class="mb-3 flex items-center gap-3 rounded-xl bg-[#F7F9FD] px-3 py-3">

            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-[#DDEAFF] to-[#C8DDFF] text-[12px] font-bold text-[#3478F6]">
                {{ mb_strtoupper(mb_substr(auth()->user()?->name ?? 'A', 0, 1)) }}
            </div>

            <div class="min-w-0 flex-1">

                <p
                    class="truncate text-[11px] font-bold text-[#26354B]"
                    title="{{ auth()->user()?->name }}"
                >
                    {{ auth()->user()?->name ?? 'Administrator' }}
                </p>

                <p
                    class="mt-1 truncate text-[10px] text-[#91A0B5]"
                    title="{{ auth()->user()?->email }}"
                >
                    {{ auth()->user()?->email ?? '' }}
                </p>

            </div>

        </div>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button
                type="submit"
                class="group flex min-h-11 w-full cursor-pointer items-center gap-3 rounded-xl px-3 py-3 text-left text-[11px] font-semibold text-[#E16A6A] transition hover:bg-[#FFF1F1] hover:text-[#D74747]"
            >

                <svg
                    class="h-[19px] w-[19px] shrink-0"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    viewBox="0 0 24 24"
                >
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <path d="M16 17l5-5-5-5"></path>
                    <path d="M21 12H9"></path>
                </svg>

                <span class="flex-1">
                    Log out
                </span>

                <svg
                    class="h-3.5 w-3.5 opacity-0 transition-opacity group-hover:opacity-100"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    viewBox="0 0 24 24"
                >
                    <path d="m9 18 6-6-6-6"></path>
                </svg>

            </button>
        </form>

    </div>

</aside>
