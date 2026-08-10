<aside
    class="flex min-h-screen w-[235px] shrink-0 flex-col bg-white px-[46px] py-[42px]"
>
    {{-- Logo --}}
    <a href="{{ route('dashboard') }}">
        <img
            src="{{ asset('images/logo.svg') }}"
            alt="Impala"
            class="h-[34px] w-auto"
        >
    </a>

    {{-- Workspace --}}
    <div class="mt-[70px]">
        <p class="text-[10px] font-medium uppercase tracking-[0.02em] text-[#555555]">
            Workspace
        </p>

        <nav class="mt-[16px] space-y-[4px] text-[11px]">
            <a
                href="{{ route('dashboard') }}"
                class="block rounded-[5px] bg-[#E9EDFF] px-[12px] py-[8px] text-[#4D7FCF]"
            >
                Dashboard
            </a>

            <a
                href="#recent-studies"
                class="block rounded-[5px] px-[12px] py-[8px] text-[#555555] transition hover:bg-[#F5F5F5]"
            >
                My Research Requests
            </a>

            <a
                href="{{ route('home') }}#start-study"
                class="block rounded-[5px] px-[12px] py-[8px] text-[#555555] transition hover:bg-[#F5F5F5]"
            >
                Create New Study
            </a>

            <a
                href="#"
                class="block rounded-[5px] px-[12px] py-[8px] text-[#555555] transition hover:bg-[#F5F5F5]"
            >
                Assisted Research
            </a>
        </nav>
    </div>

    {{-- Account --}}
    <div class="mt-[36px]">
        <p class="text-[10px] font-medium uppercase tracking-[0.02em] text-[#555555]">
            Account
        </p>

        <nav class="mt-[16px] space-y-[4px] text-[11px]">
            <a
                href="{{ route('profile-settings') }}"
                class="block rounded-[5px] px-[12px] py-[8px] text-[#555555] transition hover:bg-[#F5F5F5]"
            >
                Profile Settings
            </a>

            <a
                href="{{ route('pricing') }}"
                class="block rounded-[5px] px-[12px] py-[8px] text-[#555555] transition hover:bg-[#F5F5F5]"
            >
                My subscriptions
            </a>
        </nav>
    </div>

    {{-- Demo logout --}}
    <div class="mt-auto pt-10">
        <a
            href="{{ route('login') }}"
            class="block px-[12px] py-[8px] text-[11px] text-[#777777] hover:text-red-500"
        >
            Log out
        </a>
    </div>
</aside>