
@php
    $sidebarUser = auth()->user();
    $sidebarName = trim($sidebarUser?->name ?? 'User');

    $sidebarInitials = collect(preg_split('/\s+/', $sidebarName))
        ->filter()
        ->take(2)
        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
        ->implode('');

    $dashboardActive = request()->routeIs('dashboard');
    $studiesActive = request()->routeIs('my-studies.*');
    $profileActive = request()->routeIs('profile-settings');
    $subscriptionActive = request()->routeIs('pricing');

    $linkBase = 'group relative flex min-h-[42px] items-center gap-3 rounded-[11px] px-3 py-2.5 text-[11px] font-semibold transition-all duration-200';
    $linkActive = 'bg-gradient-to-r from-[#E7F0FF] to-[#F1F6FF] text-[#3478FF] ring-1 ring-inset ring-[#D7E6FF]';
    $linkInactive = 'text-[#7184A5] hover:bg-[#F3F7FE] hover:text-[#3478FF]';
@endphp

<aside class="sticky top-0 z-30 flex h-screen w-[260px] shrink-0 flex-col overflow-hidden border-r border-[#E5ECF7] bg-white shadow-[3px_0_20px_rgba(30,64,120,0.025)]">

    {{-- Brand --}}
    <div class="shrink-0 px-6 pb-4 pt-6">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center transition-opacity hover:opacity-80">
            <img
                src="{{ asset('images/logo.svg') }}"
                alt="Impala Research"
                class="h-[34px] w-auto max-w-full"
            >
        </a>

        <div class="mt-4 flex items-center gap-2">
            <span class="h-1.5 w-1.5 rounded-full bg-[#20B985]"></span>
            <span class="text-[10px] font-semibold text-[#91A0B6]">
                Research Platform
            </span>
        </div>
    </div>

    <div class="mx-5 shrink-0 border-t border-[#EDF1F8]"></div>

    {{-- Navigation --}}
    <div class="min-h-0 flex-1 overflow-y-auto overflow-x-hidden px-4 py-5 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">

        {{-- Workspace --}}
        <div>
            <p class="mb-3 px-3 text-[10px] font-bold uppercase tracking-[0.12em] text-[#A0AEC3]">
                Workspace
            </p>

            <nav class="space-y-1.5">

                <a
                    href="{{ route('dashboard') }}"
                    @if($dashboardActive) aria-current="page" @endif
                    class="{{ $linkBase }} {{ $dashboardActive ? $linkActive : $linkInactive }}"
                >
                    @if($dashboardActive)
                        <span class="absolute bottom-2 left-0 top-2 w-[3px] rounded-r-full bg-[#3478FF]"></span>
                    @endif

                    <svg class="h-[18px] w-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                        <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                        <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                        <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                    </svg>

                    <span class="min-w-0 flex-1">Dashboard</span>

                    @if($dashboardActive)
                        <span class="h-1.5 w-1.5 rounded-full bg-[#3478FF]"></span>
                    @endif
                </a>

                <a
                    href="{{ route('my-studies.index') }}"
                    @if($studiesActive) aria-current="page" @endif
                    class="{{ $linkBase }} {{ $studiesActive ? $linkActive : $linkInactive }}"
                >
                    @if($studiesActive)
                        <span class="absolute bottom-2 left-0 top-2 w-[3px] rounded-r-full bg-[#3478FF]"></span>
                    @endif

                    <svg class="h-[18px] w-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <rect x="4" y="4" width="16" height="16" rx="3"/>
                        <path stroke-linecap="round" d="M8 9h8M8 13h8M8 17h5"/>
                    </svg>

                    <span class="min-w-0 flex-1 whitespace-nowrap">
                        My Research Requests
                    </span>

                    @if($studiesActive)
                        <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-[#3478FF]"></span>
                    @endif
                </a>

                <a
                    href="{{ route('home') }}#start-study"
                    class="{{ $linkBase }} {{ $linkInactive }}"
                >
                    <svg class="h-[18px] w-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="3"/>
                        <path stroke-linecap="round" d="M12 7v10M7 12h10"/>
                    </svg>

                    <span class="min-w-0 flex-1">Create New Study</span>

                    <svg class="h-3.5 w-3.5 shrink-0 opacity-0 transition group-hover:opacity-100" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6l6 6-6 6"/>
                    </svg>
                </a>

                {{-- Assisted Research Coming Soon --}}
                <div
                    class="flex min-h-[42px] cursor-not-allowed items-center gap-3 rounded-[11px] px-3 py-2.5 text-[11px] font-semibold text-[#A7B3C6]"
                    title="Assisted Research is coming soon"
                >
                    <svg class="h-[18px] w-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3l2.2 5.8L20 11l-5.8 2.2L12 19l-2.2-5.8L4 11l5.8-2.2L12 3z"/>
                        <path stroke-linecap="round" d="M19 18v4M17 20h4"/>
                    </svg>

                    <span class="min-w-0 flex-1 whitespace-nowrap">Assisted Research</span>

                    <span class="shrink-0 rounded-[6px] bg-[#F0F3F8] px-1.5 py-1 text-[8px] font-bold text-[#96A4BA]">
                        Soon
                    </span>
                </div>

            </nav>
        </div>

        {{-- Account --}}
        <div class="mt-6">
            <p class="mb-3 px-3 text-[10px] font-bold uppercase tracking-[0.12em] text-[#A0AEC3]">
                Account
            </p>

            <nav class="space-y-1.5">

                <a
                    href="{{ route('profile-settings') }}"
                    @if($profileActive) aria-current="page" @endif
                    class="{{ $linkBase }} {{ $profileActive ? $linkActive : $linkInactive }}"
                >
                    @if($profileActive)
                        <span class="absolute bottom-2 left-0 top-2 w-[3px] rounded-r-full bg-[#3478FF]"></span>
                    @endif

                    <svg class="h-[18px] w-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <circle cx="12" cy="8" r="4"/>
                        <path stroke-linecap="round" d="M4 21v-2a8 8 0 0116 0v2"/>
                    </svg>

                    <span class="min-w-0 flex-1">Profile Settings</span>

                    @if($profileActive)
                        <span class="h-1.5 w-1.5 rounded-full bg-[#3478FF]"></span>
                    @endif
                </a>

                <a
                    href="{{ route('pricing') }}"
                    @if($subscriptionActive) aria-current="page" @endif
                    class="{{ $linkBase }} {{ $subscriptionActive ? $linkActive : $linkInactive }}"
                >
                    @if($subscriptionActive)
                        <span class="absolute bottom-2 left-0 top-2 w-[3px] rounded-r-full bg-[#3478FF]"></span>
                    @endif

                    <svg class="h-[18px] w-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <rect x="3" y="5" width="18" height="14" rx="3"/>
                        <path stroke-linecap="round" d="M3 10h18M7 15h4"/>
                    </svg>

                    <span class="min-w-0 flex-1">My Subscriptions</span>

                    @if($subscriptionActive)
                        <span class="h-1.5 w-1.5 rounded-full bg-[#3478FF]"></span>
                    @endif
                </a>

            </nav>
        </div>
    </div>

    {{-- Fixed Bottom --}}
    <div class="shrink-0 border-t border-[#EDF1F8] bg-white px-4 pb-4 pt-3">

        <div class="rounded-[13px] border border-[#E1EAF8] bg-gradient-to-br from-[#F2F7FF] to-white p-3">
            <div class="flex items-center gap-2.5">

                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#DCEAFF] text-[11px] font-bold text-[#3478FF]">
                    {{ $sidebarInitials ?: 'U' }}
                </div>

                <div class="min-w-0 flex-1">
                    <p class="truncate text-[11px] font-bold text-[#102243]" title="{{ $sidebarName }}">
                        {{ $sidebarName }}
                    </p>

                    <p class="mt-1 truncate text-[9px] text-[#8294B0]" title="{{ $sidebarUser?->email }}">
                        {{ $sidebarUser?->email }}
                    </p>
                </div>
            </div>

            <div class="mt-2.5 flex items-center gap-2 border-t border-[#E6EEF9] pt-2.5">
                <span class="h-1.5 w-1.5 rounded-full bg-[#19B981]"></span>
                <span class="text-[9px] font-semibold text-[#8294B0]">Signed in</span>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf

            <button
                type="submit"
                class="group flex h-10 w-full cursor-pointer items-center gap-3 rounded-[10px] px-3 text-left text-[11px] font-semibold text-[#8493AB] transition hover:bg-[#FFF1F3] hover:text-[#D9475A]"
            >
                <svg class="h-[18px] w-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 17l5-5-5-5M21 12H9"/>
                </svg>

                <span class="flex-1">Log Out</span>
            </button>
        </form>

        <p class="mt-2 text-center text-[9px] text-[#B1BDD0]">
            © {{ date('Y') }} Impala Research
        </p>
    </div>

</aside>
