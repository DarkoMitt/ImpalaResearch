@extends('layouts.app')

@section('content')
    <section class="flex min-h-[75vh] items-center justify-center bg-white px-6 text-center">
        <div class="max-w-[720px]">
            <h1 class="flex items-center justify-center gap-3 text-[42px] font-bold text-[#2F2F32]">
                <span class="text-[#52D318]">✓</span>
                Request sent
            </h1>

            <p class="mt-8 text-[20px] text-[#2F2F32]">
                We’ll get back to you shortly with a tailored proposal.
            </p>

            <div class="mt-14 grid gap-4 md:grid-cols-2">
                <a
                    href="{{ route('home') }}"
                    class="flex h-[60px] items-center justify-center rounded-[4px] bg-[#BDBDBD] text-[18px] font-medium text-white transition hover:bg-[#A8A8A8]"
                >
                    Back to homepage
                </a>

                <a
                    href="{{ route('home') }}#start-study"
                    class="flex h-[60px] items-center justify-center rounded-[4px] bg-[#33B6FF] text-[18px] font-medium text-white transition hover:bg-[#159fe4]"
                >
                    Submit another request
                </a>
            </div>
        </div>
    </section>
@endsection