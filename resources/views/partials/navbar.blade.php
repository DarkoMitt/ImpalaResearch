<header class="w-full bg-white">
    <nav class="mx-auto flex max-w-[1400px] items-center justify-between px-8 py-8 lg:px-20">
        <a href="{{ route('home') }}" class="flex items-center">
            <img
                src="{{ asset('images/logo.svg') }}"
                alt="Impala Logo"
                class="h-[42px] w-auto"
            >
        </a>

        <div class="hidden items-center gap-12 text-[18px] font-medium text-[#111111] md:flex">
            <a href="{{ route('home') }}" class="transition hover:text-[#33B6FF]">Home</a>
            <a href="#infinity" class="transition hover:text-[#33B6FF]">Impala Infinity</a>
            <a href="#science" class="transition hover:text-[#33B6FF]">Science</a>
            <a href="{{ route('contact') }}" class="transition hover:text-[#33B6FF]">Contact</a>
        </div>
    </nav>
</header>