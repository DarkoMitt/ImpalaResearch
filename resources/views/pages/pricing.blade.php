
@extends('layouts.app')

@section('content')

<section class="min-h-screen w-full bg-white px-6 py-[70px] md:px-10 lg:px-[70px]">

    {{-- Heading --}}
    <div class="mx-auto text-center">

        <h1 class="text-[28px] font-medium leading-[1.2] tracking-[-0.7px] text-[#242424] md:text-[30px]">
            One respondent pool, three
            <br>
            cadences
        </h1>

        <p class="mx-auto mt-[20px] max-w-[570px] text-[12px] leading-[1.6] text-[#8A8A8A]">
            Choose the right plan for your research needs.
            Save more with longer subscriptions and unlock additional benefits.
        </p>
    </div>

    {{-- Pricing Cards --}}
    <div class="mx-auto mt-[70px] grid w-full max-w-[1180px] gap-[38px] lg:grid-cols-3">

        @foreach ($plans as $plan)
            @php
                $isPopular = $plan->slug === 'semi-yearly';

                $monthlyPrice = ($plan->price_cents / 100) / $plan->duration_months;

                $totalPrice = $plan->price_cents / 100;

                $totalRespondents = $plan->initial_respondents
                    + max(0, $plan->duration_months - 1) * $plan->monthly_respondents;

                $savings = 0;

                if ($plan->duration_months > 1) {
                    $monthlyPlan = $plans->firstWhere('slug', 'monthly');

                    if ($monthlyPlan) {
                        $regularTotal = ($monthlyPlan->price_cents / 100) * $plan->duration_months;

                        if ($regularTotal > 0) {
                            $savings = round((1 - ($totalPrice / $regularTotal)) * 100);
                        }
                    }
                }
            @endphp

            <article
                class="relative flex min-h-[470px] flex-col rounded-[24px] border bg-white px-[28px] py-[28px]
                {{ $isPopular
                    ? 'border-[#4D7FCF] shadow-[0_8px_24px_rgba(77,127,207,0.25)]'
                    : 'border-[#CFCFCF]' }}"
            >

                @if ($isPopular)
                    <div class="absolute -top-[14px] right-[28px] rounded-full bg-[#4D7FCF] px-[18px] py-[7px] text-[12px] font-medium text-white">
                        Most popular
                    </div>
                @endif

                <p class="text-[11px] font-medium uppercase text-[#333333]">
                    {{ $plan->name }}
                </p>

                {{-- Price --}}
                <div class="mt-[48px] flex items-end gap-[12px]">

                    <span class="text-[46px] font-semibold leading-none tracking-[-1.5px] text-black">
                        ${{ number_format($monthlyPrice, 0) }}
                    </span>

                    <span class="pb-[4px] text-[13px] text-[#A0A0A0]">
                        / month
                    </span>
                </div>

                <div class="mt-[13px] flex flex-wrap items-center gap-[10px]">
                    <p class="text-[11px] text-[#777777]">
                        ${{ number_format($totalPrice, 0) }}
                        billed {{ $plan->duration_months === 1 ? 'monthly' : 'every ' . $plan->duration_months . ' months' }}
                    </p>

                    @if ($savings > 0)
                        <span class="rounded-full bg-[#E0F4E5] px-[9px] py-[4px] text-[10px] font-medium text-[#248743]">
                            Save {{ $savings }}%
                        </span>
                    @endif
                </div>

                {{-- Respondents --}}
                <div class="mt-[30px] flex min-h-[42px] items-center justify-between gap-3 rounded-[6px] bg-[#F1F4FC] px-[16px] text-[12px] text-[#4D7FCF]">
                    <span>Total respondents</span>

                    <span class="font-medium">
                        {{ number_format($totalRespondents) }}
                    </span>
                </div>

                @if ($plan->duration_months > 1)
                    <p class="mt-[9px] text-[10px] leading-[1.5] text-[#777777]">
                        {{ number_format($plan->initial_respondents) }} initially,
                        +{{ number_format($plan->monthly_respondents) }} each following month.
                        Unused respondents roll over during your subscription.
                    </p>
                @else
                    <p class="mt-[9px] text-[10px] leading-[1.5] text-[#777777]">
                        {{ number_format($plan->initial_respondents) }} respondents for your monthly subscription.
                    </p>
                @endif

                {{-- Features --}}
                <div class="mt-[28px] space-y-[9px] text-[12px] text-[#777777]">
                    @foreach ($plan->features ?? [] as $feature)
                        <div class="flex items-start gap-[9px]">
                            <span class="mt-[1px] text-[#4D7FCF]">✓</span>
                            <span>{{ $feature }}</span>
                        </div>
                    @endforeach
                </div>

                {{-- Choose Plan --}}
                <a
                    href="{{ route('payment', ['plan' => $plan->slug]) }}"
                    class="mt-auto flex h-[48px] w-full items-center justify-center rounded-full text-[14px] transition
                    {{ $isPopular
                        ? 'bg-[#4D7FCF] font-medium text-white hover:bg-[#416FB4]'
                        : 'border border-[#C5C5C5] text-[#242424] hover:border-[#4D7FCF] hover:text-[#4D7FCF]' }}"
                >
                    Choose plan
                </a>
            </article>
        @endforeach

    </div>

    {{-- Cancellation Information --}}
    <div class="mx-auto mt-[42px] max-w-[800px] text-center">
        <p class="text-[11px] leading-[1.8] text-[#777777]">
            Plans renew automatically unless cancelled.
            Early cancellation of multi-month plans is subject to a refund
            calculation based on months used at the standard monthly rate.
            Full terms will be provided before checkout.
        </p>
    </div>

</section>

@endsection
