<aside
    class="flex min-h-screen w-[225px] shrink-0 flex-col bg-white px-[34px] py-[38px]"
>
    {{-- Logo --}}
    <a href="{{ route('admin.dashboard') }}">
        <img
            src="{{ asset('images/logo.svg') }}"
            alt="Impala"
            class="h-[34px] w-auto"
        >
    </a>

    {{-- Workspace --}}
    <div class="mt-[72px]">
        <p class="text-[10px] font-medium uppercase tracking-[0.02em] text-[#666666]">
            Workspace
        </p>

        <nav class="mt-[16px] space-y-[4px] text-[11px]">
            <a
                href="{{ route('admin.dashboard') }}"
                class="block rounded-[5px] px-[10px] py-[8px]
                {{ request()->routeIs('admin.dashboard')
                    ? 'bg-[#E9EDFF] text-[#4D7FCF]'
                    : 'text-[#555555] hover:bg-[#F5F5F5]' }}"
            >
                Overview
            </a>

            <a
                href="{{ route('admin.users') }}"
                class="block rounded-[5px] px-[10px] py-[8px]
                {{ request()->routeIs('admin.users')
                    ? 'bg-[#E9EDFF] text-[#4D7FCF]'
                    : 'text-[#555555] hover:bg-[#F5F5F5]' }}"
            >
                Users
            </a>

            <a
                href="#"
                class="block rounded-[5px] px-[10px] py-[8px] text-[#555555] transition hover:bg-[#F5F5F5]"
            >
                Contact & Demo
            </a>

            <a
                href="#"
                class="block rounded-[5px] px-[10px] py-[8px] text-[#555555] transition hover:bg-[#F5F5F5]"
            >
                Website CMS
            </a>
        </nav>
    </div>

    {{-- Logout --}}
    <div class="mt-auto pt-10">
        <a
            href="{{ route('login') }}"
            class="block px-[10px] py-[8px] text-[11px] text-[#777777] transition hover:text-red-500"
        >
            Log out
        </a>
    </div>
</aside>