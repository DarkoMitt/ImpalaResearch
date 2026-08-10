@extends('layouts.app')

@section('content')
<div class="flex min-h-screen w-full bg-white">

    @include('partials.admin-sidebar')

    {{-- Main --}}
    <main
        class="min-h-screen flex-1 bg-gradient-to-br from-[#DCEAFF] via-[#F6F9FF] to-white px-[26px] py-[42px] lg:px-[34px]"
    >
        {{-- Header --}}
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-[25px] font-medium tracking-[-0.6px] text-[#171717]">
                    Platform overview
                </h1>

                <p class="mt-[6px] text-[12px] text-[#666666]">
                    Snapshot across accounts, subscriptions and inbound requests.
                </p>
            </div>

            <div
                class="inline-flex items-center gap-[8px] rounded-full bg-[#F0F2F7] px-[14px] py-[8px] text-[11px] text-[#666666]"
            >
                <span class="h-[6px] w-[6px] rounded-full bg-[#777777]"></span>
                Updated 4 min ago
            </div>
        </div>

        {{-- Stats --}}
        <div class="mt-[34px] grid gap-[12px] xl:grid-cols-4">

            <div class="rounded-[14px] border border-[#BFC3C9] bg-white px-[18px] py-[20px]">
                <p class="text-[10px] uppercase text-[#666666]">
                    Total Users
                </p>

                <h2 class="mt-[20px] text-[22px] font-medium">
                    1,284
                </h2>

                <p class="mt-[10px] text-[10px] text-[#3D9C54]">
                    + 38 this month
                </p>
            </div>

            <div class="rounded-[14px] border border-[#BFC3C9] bg-white px-[18px] py-[20px]">
                <p class="text-[10px] uppercase text-[#666666]">
                    Active Subscriptions
                </p>

                <h2 class="mt-[20px] text-[22px] font-medium">
                    312
                </h2>

                <p class="mt-[10px] text-[10px] text-[#3D9C54]">
                    + 12 this month
                </p>
            </div>

            <div class="rounded-[14px] border border-[#BFC3C9] bg-white px-[18px] py-[20px]">
                <p class="text-[10px] uppercase text-[#666666]">
                    Research Requests
                </p>

                <h2 class="mt-[20px] text-[22px] font-medium">
                    57
                </h2>

                <p class="mt-[10px] text-[10px] text-[#777777]">
                    9 pending review
                </p>
            </div>

            <div class="rounded-[14px] border border-[#BFC3C9] bg-white px-[18px] py-[20px]">
                <p class="text-[10px] uppercase text-[#666666]">
                    Contact Messages
                </p>

                <h2 class="mt-[20px] text-[22px] font-medium">
                    23
                </h2>

                <p class="mt-[10px] text-[10px] text-[#777777]">
                    6 unread
                </p>
            </div>
        </div>

        {{-- Lower grid --}}
        <div class="mt-[14px] grid gap-[14px] xl:grid-cols-[1fr_230px]">

            {{-- Demo requests --}}
            <section
                class="overflow-hidden rounded-[12px] border border-[#BFC3C9] bg-white"
            >
                <div
                    class="flex h-[48px] items-center justify-between border-b border-[#E6E6E6] px-[20px]"
                >
                    <h2 class="text-[13px] font-medium text-[#202939]">
                        Demo Requests — latest
                    </h2>

                    <button
                        type="button"
                        class="text-[10px] text-[#4D7FCF]"
                    >
                        View all
                    </button>
                </div>

                {{-- Request 1 --}}
                <div
                    class="flex min-h-[78px] items-center justify-between border-b border-[#EAEAEA] px-[18px]"
                >
                    <div class="flex items-center gap-[12px]">
                        <div
                            class="flex h-[34px] w-[34px] items-center justify-center rounded-[7px] bg-[#E9EDFF] text-[16px]"
                        >
                            🏪
                        </div>

                        <div>
                            <p class="text-[12px] font-medium text-[#202939]">
                                Nova Retail Group
                            </p>

                            <p class="mt-[5px] text-[10px] text-[#777777]">
                                Requested Jul 1 · Facial Coding demo
                            </p>
                        </div>
                    </div>

                    <span
                        class="rounded-full bg-[#FFF0E4] px-[14px] py-[5px] text-[9px] font-medium text-[#D66A16]"
                    >
                        ● &nbsp; NEW
                    </span>
                </div>

                {{-- Request 2 --}}
                <div
                    class="flex min-h-[78px] items-center justify-between border-b border-[#EAEAEA] px-[18px]"
                >
                    <div class="flex items-center gap-[12px]">
                        <div
                            class="flex h-[34px] w-[34px] items-center justify-center rounded-[7px] bg-[#E9EDFF] text-[16px]"
                        >
                            📣
                        </div>

                        <div>
                            <p class="text-[12px] font-medium text-[#202939]">
                                Halcyon Ads
                            </p>

                            <p class="mt-[5px] text-[10px] text-[#777777]">
                                Requested Jun 29 · Full platform demo
                            </p>
                        </div>
                    </div>

                    <span
                        class="rounded-full bg-[#E8F0FF] px-[14px] py-[5px] text-[9px] font-medium text-[#3775D6]"
                    >
                        ● &nbsp; SCHEDULED
                    </span>
                </div>

                {{-- Request 3 --}}
                <div
                    class="flex min-h-[78px] items-center justify-between px-[18px]"
                >
                    <div class="flex items-center gap-[12px]">
                        <div
                            class="flex h-[34px] w-[34px] items-center justify-center rounded-[7px] bg-[#E9EDFF] text-[16px]"
                        >
                            🍴
                        </div>

                        <div>
                            <p class="text-[12px] font-medium text-[#202939]">
                                Fresh & Field Foods
                            </p>

                            <p class="mt-[5px] text-[10px] text-[#777777]">
                                Requested Jun 27 · Eye tracking demo
                            </p>
                        </div>
                    </div>

                    <span
                        class="rounded-full bg-[#E3F7EA] px-[14px] py-[5px] text-[9px] font-medium text-[#258B4B]"
                    >
                        ● &nbsp; COMPLETED
                    </span>
                </div>
            </section>

            {{-- Plan distribution --}}
            <aside
                class="rounded-[12px] border border-[#BFC3C9] bg-white px-[18px] py-[18px]"
            >
                <h2 class="text-[13px] font-medium text-[#202939]">
                    Plan distribution
                </h2>

                <div class="mt-[28px] space-y-[26px] text-[12px]">
                    <div class="flex justify-between">
                        <span>Monthly</span>
                        <span class="text-[#555555]">
                            148 <span class="text-[#888888]">accounts</span>
                        </span>
                    </div>

                    <div class="border-t border-dashed border-[#D9D9D9] pt-[24px]">
                        <div class="flex justify-between">
                            <span>Semi-Yearly</span>
                            <span class="text-[#555555]">
                                <strong>104</strong>
                                <span class="text-[#888888]">accounts</span>
                            </span>
                        </div>
                    </div>

                    <div class="border-t border-dashed border-[#D9D9D9] pt-[24px]">
                        <div class="flex justify-between">
                            <span>Yearly</span>
                            <span class="text-[#555555]">
                                60 <span class="text-[#888888]">accounts</span>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Capacity --}}
                <div class="mt-[38px]">
                    <div class="flex justify-between text-[11px]">
                        <span class="font-medium">
                            Capacity Usage
                        </span>

                        <span>
                            74%
                        </span>
                    </div>

                    <div class="mt-[9px] h-[6px] overflow-hidden rounded-full bg-[#E6ECF5]">
                        <div class="h-full w-[74%] rounded-full bg-[#4D7FCF]"></div>
                    </div>
                </div>
            </aside>
        </div>
    </main>
</div>
@endsection