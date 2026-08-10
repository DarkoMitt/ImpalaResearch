@extends('layouts.app')

@section('content')
<div class="flex min-h-screen w-full bg-white">

    @include('partials.user-sidebar')

    {{-- Main --}}
    <main
        class="min-h-screen flex-1 bg-gradient-to-br from-[#DCEAFF] via-[#F7FAFF] to-white px-[30px] py-[46px] lg:px-[42px]"
    >
        {{-- Header --}}
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-[25px] font-medium tracking-[-0.6px] text-[#171717]">
                    Welcome back, Sara
                </h1>

                <p class="mt-[6px] text-[12px] text-[#666666]">
                    Here's an overview of your research activity.
                </p>
            </div>

            <a
                href="{{ route('home') }}#start-study"
                class="inline-flex h-[42px] items-center gap-[8px] rounded-[7px] bg-[#4D7FCF] px-[18px] text-[12px] font-medium text-white transition hover:bg-[#416FB4]"
            >
                <span class="text-[18px] font-light">+</span>
                Create New Study
            </a>
        </div>

        {{-- Stats --}}
        <div class="mt-[34px] grid gap-[14px] xl:grid-cols-4">

            <div class="rounded-[15px] border border-[#BFBFBF] bg-white px-[20px] py-[21px]">
                <p class="text-[10px] uppercase text-[#666666]">
                    Active Plan
                </p>

                <h2 class="mt-[20px] text-[23px] font-medium">
                    Semi-Yearly
                </h2>

                <p class="mt-[8px] text-[10px] text-[#777777]">
                    Renews Jan 14, 2027
                </p>
            </div>

            <div class="rounded-[15px] border border-[#BFBFBF] bg-white px-[20px] py-[21px]">
                <p class="text-[10px] uppercase text-[#666666]">
                    Remaining Respondents
                </p>

                <div class="mt-[20px] flex items-end gap-1">
                    <span class="text-[23px] font-medium">340</span>
                    <span class="pb-[2px] text-[14px] text-[#888888]">/ 500</span>
                </div>

                <div class="mt-[18px] h-[6px] overflow-hidden rounded-full bg-[#E4EAF2]">
                    <div class="h-full w-[68%] rounded-full bg-[#4D7FCF]"></div>
                </div>
            </div>

            <div class="rounded-[15px] border border-[#BFBFBF] bg-white px-[20px] py-[21px]">
                <p class="text-[10px] uppercase text-[#666666]">
                    Subscription Expiration
                </p>

                <h2 class="mt-[20px] text-[23px] font-medium">
                    196 days
                </h2>

                <p class="mt-[8px] text-[10px] text-[#777777]">
                    Auto-renew is on
                </p>
            </div>

            <div class="rounded-[15px] border border-[#BFBFBF] bg-white px-[20px] py-[21px]">
                <p class="text-[10px] uppercase text-[#666666]">
                    Studies in Field
                </p>

                <h2 class="mt-[20px] text-[23px] font-medium">
                    3
                </h2>

                <p class="mt-[8px] text-[10px] text-[#777777]">
                    2 launched this week
                </p>
            </div>
        </div>

        {{-- Lower section --}}
        <div class="mt-[16px] grid gap-[16px] xl:grid-cols-[1fr_260px]">

            {{-- Recent Studies --}}
            <section
                id="recent-studies"
                class="overflow-hidden rounded-[12px] border border-[#BFC3C9] bg-white"
            >
                <div class="flex h-[50px] items-center justify-between border-b border-[#E3E3E3] px-[22px]">
                    <h2 class="text-[13px] font-medium">
                        Recent Studies
                    </h2>

                    <button class="text-[11px] text-[#4D7FCF]">
                        View all
                    </button>
                </div>

                @php
                    $studies = [
                        [
                            'Packaging A/B — Facial Coding',
                            'CX-1042',
                            '120',
                            'IN FIELD',
                            'green'
                        ],
                        [
                            'Homepage Hero — Eye Tracking',
                            'CX-1039',
                            '80',
                            'AWAITING REVIEW',
                            'yellow'
                        ],
                        [
                            'Brand Recall — Implicit Testing',
                            'CX-1031',
                            '200',
                            'REPORT READY',
                            'blue'
                        ],
                        [
                            'Ad Creative Lift — Pre/Post',
                            'CX-1024',
                            '150',
                            'REPORT READY',
                            'blue'
                        ],
                    ];
                @endphp

                @foreach ($studies as $study)
                    <div class="flex min-h-[72px] items-center justify-between border-b border-[#E9E9E9] px-[22px] last:border-b-0">
                        <div>
                            <h3 class="text-[13px] font-medium text-[#192231]">
                                {{ $study[0] }}
                            </h3>

                            <p class="mt-[6px] font-mono text-[11px] tracking-[0.06em] text-[#555555]">
                                ID {{ $study[1] }}
                                <span class="mx-[8px]">•</span>
                                {{ $study[2] }} RESPONDENTS
                            </p>
                        </div>

                        @if ($study[4] === 'green')
                            <span class="rounded-full bg-[#E0F4E5] px-[14px] py-[5px] text-[9px] font-medium text-[#248743]">
                                ● &nbsp; {{ $study[3] }}
                            </span>
                        @elseif ($study[4] === 'yellow')
                            <span class="rounded-full bg-[#FFF3D8] px-[14px] py-[5px] text-[9px] font-medium text-[#C37B0A]">
                                ● &nbsp; {{ $study[3] }}
                            </span>
                        @else
                            <span class="rounded-full bg-[#E7EFFF] px-[14px] py-[5px] text-[9px] font-medium text-[#3775D6]">
                                ● &nbsp; {{ $study[3] }}
                            </span>
                        @endif
                    </div>
                @endforeach
            </section>

            {{-- Quick Profile --}}
            <aside class="rounded-[12px] border border-[#BFC3C9] bg-white">
                <div class="flex h-[50px] items-center border-b border-[#E3E3E3] px-[20px]">
                    <h2 class="text-[13px] font-medium">
                        Quick Profile
                    </h2>
                </div>

                <div class="p-[20px]">
                    <div class="flex items-center gap-[14px]">
                        <div
                            class="flex h-[44px] w-[44px] items-center justify-center rounded-full bg-[#262B34] text-[15px] text-white"
                        >
                            MK
                        </div>

                        <div>
                            <p class="text-[13px] font-semibold">
                                Mila Krstić
                            </p>

                            <p class="text-[10px] text-[#666666]">
                                mila@brightlensmedia.com
                            </p>
                        </div>
                    </div>

                    <div class="mt-[28px] space-y-[24px] text-[12px]">
                        <div class="flex justify-between">
                            <span class="text-[#555555]">Company</span>
                            <strong>Bright Lens Media</strong>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-[#555555]">Account type</span>
                            <strong>Agency</strong>
                        </div>

                        <div>
                            <div class="flex justify-between">
                                <span class="text-[#555555]">Seats used</span>
                                <strong>4 / 6</strong>
                            </div>

                            <div class="ml-auto mt-[10px] h-[5px] w-[80px] rounded-full bg-[#E6ECF5]">
                                <div class="h-full w-[67%] rounded-full bg-[#5646E8]"></div>
                            </div>
                        </div>
                    </div>

                    <a
                        href="{{ route('profile-settings') }}"
                        class="mt-[30px] flex h-[38px] w-full items-center justify-center rounded-[7px] border border-[#C5C5C5] text-[11px] transition hover:border-[#4D7FCF]"
                    >
                        Edit profile
                    </a>
                </div>
            </aside>
        </div>
    </main>
</div>
@endsection