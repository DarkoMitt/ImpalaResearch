@extends('layouts.app')

@section('content')

<section
    class="min-h-screen w-full bg-white px-6 py-[70px] md:px-10 lg:px-[70px]"
    x-data="{
        selectedPlan: 'semi-yearly',

        choosePlan(plan) {
            this.selectedPlan = plan;

            window.location.href =
                '{{ route('payment') }}?plan=' + encodeURIComponent(plan);
        }
    }"
>

    {{-- Heading --}}
    <div class="mx-auto text-center">

        <h1
            class="text-[28px] font-medium leading-[1.2] tracking-[-0.7px] text-[#242424] md:text-[30px]"
        >
            One respondent pool, three
            <br>
            cadences
        </h1>

        <p
            class="mx-auto mt-[20px] max-w-[570px] text-[12px] leading-[1.6] text-[#8A8A8A]"
        >
            Every plan includes all six methodologies, unlimited team seats
            and the same reporting dashboard.
        </p>

    </div>


    {{-- Pricing Cards --}}
    <div
        class="mx-auto mt-[70px] grid w-full max-w-[1180px] gap-[38px] lg:grid-cols-3"
    >

        {{-- MONTHLY --}}
        <article
            class="flex min-h-[470px] flex-col rounded-[24px] border border-[#CFCFCF] bg-white px-[28px] py-[28px]"
        >

            <p class="text-[11px] font-medium uppercase text-[#333333]">
                Monthly
            </p>

            {{-- Price --}}
            <div class="mt-[48px] flex items-end gap-[12px]">

                <span
                    class="text-[46px] font-semibold leading-none tracking-[-1.5px] text-black"
                >
                    $390
                </span>

                <span class="pb-[4px] text-[13px] text-[#A0A0A0]">
                    / month
                </span>

            </div>

            {{-- Respondents --}}
            <div
                class="mt-[38px] flex h-[42px] items-center justify-between rounded-[6px] bg-[#F1F4FC] px-[16px] text-[13px] text-[#4D7FCF]"
            >
                <span>Respondents</span>
                <span>500</span>
            </div>

            {{-- Features --}}
            <div
                class="mt-[28px] space-y-[9px] text-[13px] text-[#A5A5A5]"
            >
                <p>All 6 methodologies</p>
                <p>Standard reporting dashboard</p>
                <p>Email support</p>
                <p>Cancel anytime</p>
            </div>

            {{-- Button --}}
            <button
                type="button"
                @click="choosePlan('monthly')"
                class="mt-auto h-[48px] w-full rounded-full border border-[#C5C5C5] text-[14px] text-[#242424] transition hover:border-[#4D7FCF] hover:text-[#4D7FCF]"
            >
                Choose plan
            </button>

        </article>


        {{-- SEMI YEARLY --}}
        <article
            class="relative flex min-h-[470px] flex-col rounded-[24px] border border-[#4D7FCF] bg-white px-[28px] py-[28px] shadow-[0_8px_24px_rgba(77,127,207,0.25)]"
        >

            {{-- Badge --}}
            <div
                class="absolute -top-[14px] right-[28px] rounded-full bg-[#4D7FCF] px-[18px] py-[7px] text-[12px] font-medium text-white"
            >
                Most popular
            </div>

            <p class="text-[11px] font-medium uppercase text-[#333333]">
                Semi-Yearly
            </p>

            {{-- Price --}}
            <div class="mt-[48px] flex items-end gap-[12px]">

                <span
                    class="text-[46px] font-semibold leading-none tracking-[-1.5px] text-black"
                >
                    $720
                </span>

                <span class="pb-[4px] text-[13px] text-[#A0A0A0]">
                    / month
                </span>

            </div>

            {{-- Respondents --}}
            <div
                class="mt-[38px] flex h-[42px] items-center justify-between rounded-[6px] bg-[#F1F4FC] px-[16px] text-[13px] text-[#4D7FCF]"
            >
                <span>Respondents</span>
                <span>500</span>
            </div>

            {{-- Features --}}
            <div
                class="mt-[28px] space-y-[9px] text-[13px] text-[#A5A5A5]"
            >
                <p>All 6 methodologies</p>
                <p>Standard reporting dashboard</p>
                <p>Email support</p>
                <p>Cancel anytime</p>
            </div>

            {{-- Button --}}
            <button
                type="button"
                @click="choosePlan('semi-yearly')"
                class="mt-auto h-[48px] w-full rounded-full bg-[#4D7FCF] text-[14px] font-medium text-white transition hover:bg-[#416FB4]"
            >
                Choose plan
            </button>

        </article>


        {{-- YEARLY --}}
        <article
            class="flex min-h-[470px] flex-col rounded-[24px] border border-[#CFCFCF] bg-white px-[28px] py-[28px]"
        >

            <p class="text-[11px] font-medium uppercase text-[#333333]">
                Yearly
            </p>

            {{-- Price --}}
            <div class="mt-[48px] flex items-end gap-[12px]">

                <span
                    class="text-[46px] font-semibold leading-none tracking-[-1.5px] text-black"
                >
                    $1.320
                </span>

                <span class="pb-[4px] text-[13px] text-[#A0A0A0]">
                    / month
                </span>

            </div>

            {{-- Respondents --}}
            <div
                class="mt-[38px] flex h-[42px] items-center justify-between rounded-[6px] bg-[#F1F4FC] px-[16px] text-[13px] text-[#4D7FCF]"
            >
                <span>Respondents</span>
                <span>500</span>
            </div>

            {{-- Features --}}
            <div
                class="mt-[28px] space-y-[9px] text-[13px] text-[#A5A5A5]"
            >
                <p>All 6 methodologies</p>
                <p>Standard reporting dashboard</p>
                <p>Email support</p>
                <p>Cancel anytime</p>
            </div>

            {{-- Button --}}
            <button
                type="button"
                @click="choosePlan('yearly')"
                class="mt-auto h-[48px] w-full rounded-full border border-[#C5C5C5] text-[14px] text-[#242424] transition hover:border-[#4D7FCF] hover:text-[#4D7FCF]"
            >
                Choose plan
            </button>

        </article>

    </div>

</section>

@endsection