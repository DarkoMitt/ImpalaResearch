
@extends('layouts.app')

@section('content')

@php
    $totalPrice = $plan->price_cents / 100;
    $monthlyPrice = $totalPrice / $plan->duration_months;

    $billingPeriod = match ($plan->duration_months) {
        1 => '1 month',
        6 => '6 months',
        12 => '12 months',
        default => $plan->duration_months . ' months',
    };

    $isPopular = $plan->slug === 'semi-yearly';
@endphp

<section class="min-h-screen w-full bg-white px-6 py-[48px] md:px-10 lg:px-[70px]">

    <div class="mx-auto w-full max-w-[1320px]">

        {{-- HEADER --}}
        <div>
            <h1 class="text-[24px] font-medium text-[#111111]">
                Confirm and pay
            </h1>

            <p class="mt-[10px] max-w-[600px] text-[13px] leading-[1.6] text-[#555555]">
                You're subscribing to the
                <span class="font-medium">{{ $plan->name }}</span>
                plan. Review your subscription before proceeding
                to secure checkout.
            </p>
        </div>

        {{-- PAYMENT CONTENT --}}
        <div class="mt-[52px] grid gap-[48px] lg:grid-cols-[1fr_430px]">

            {{-- LEFT PAYMENT CARD --}}
            <div class="h-fit rounded-[18px] border border-[#D8D8D8] bg-white p-[22px]">

                {{-- PAYMENT METHOD --}}
                <div class="grid grid-cols-3 gap-[10px]">

                    <div class="flex h-[42px] items-center justify-center rounded-[7px] border border-[#4D7FCF] bg-[#F5F7FF] text-[12px] text-[#222222]">
                        Card
                    </div>

                    <div class="flex h-[42px] items-center justify-center rounded-[7px] bg-[#F1F1F1] text-[12px] text-[#999999]">
                        PayPal
                    </div>

                    <div class="flex h-[42px] items-center justify-center rounded-[7px] bg-[#F1F1F1] text-[12px] text-[#999999]">
                        Bank transfer
                    </div>

                </div>

                {{-- SECURE CHECKOUT --}}
                <div class="mt-[32px]">

                    <h2 class="text-[16px] font-medium text-[#222222]">
                        Secure card payment
                    </h2>

                    <p class="mt-[12px] max-w-[650px] text-[12px] leading-[1.8] text-[#666666]">
                        Your payment will be processed securely through
                        Stripe Checkout. Card information will be entered
                        on Stripe's secure payment page, not stored
                        by Impala Research.
                    </p>

                    <div class="mt-[25px] rounded-[10px] border border-[#E0E7F5] bg-[#F7F9FE] p-[20px]">

                        <div class="flex items-center gap-[12px]">
                            <div class="flex h-[42px] w-[42px] shrink-0 items-center justify-center rounded-full bg-[#E5EDFC] text-[#4D7FCF]">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="21"
                                     height="21"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="1.7"
                                     stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                            </div>

                            <div>
                                <p class="text-[13px] font-medium text-[#222222]">
                                    Protected checkout
                                </p>

                                <p class="mt-[4px] text-[11px] text-[#777777]">
                                    Powered by Stripe
                                </p>
                            </div>
                        </div>

                        <div class="mt-[22px] space-y-[13px] text-[12px] text-[#555555]">

                            <div class="flex items-start gap-[10px]">
                                <span class="text-[#4D7FCF]">✓</span>
                                <span>Secure payment processing</span>
                            </div>

                            <div class="flex items-start gap-[10px]">
                                <span class="text-[#4D7FCF]">✓</span>
                                <span>Automatic subscription renewals</span>
                            </div>

                            <div class="flex items-start gap-[10px]">
                                <span class="text-[#4D7FCF]">✓</span>
                                <span>
                                    Early cancellation refund policy
                                    for multi-month plans
                                </span>
                            </div>

                        </div>
                    </div>

                    {{-- BILLING DETAILS --}}
                    <div class="my-[28px] flex items-center gap-[8px]">
                        <div class="h-px flex-1 bg-[#D6D6D6]"></div>

                        <span class="text-[10px] uppercase text-[#8A8A8A]">
                            Subscription details
                        </span>

                        <div class="h-px flex-1 bg-[#D6D6D6]"></div>
                    </div>

                    <div class="space-y-[16px] text-[12px]">

                        <div class="flex justify-between gap-4">
                            <span class="text-[#777777]">Selected plan</span>
                            <span class="font-medium text-[#222222]">
                                {{ $plan->name }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-[#777777]">Monthly equivalent</span>
                            <span class="font-medium text-[#222222]">
                                ${{ number_format($monthlyPrice, 2) }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-[#777777]">Billing period</span>
                            <span class="font-medium text-[#222222]">
                                {{ $billingPeriod }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-[#777777]">Initial respondents</span>
                            <span class="font-medium text-[#222222]">
                                {{ number_format($plan->initial_respondents) }}
                            </span>
                        </div>

                        @if ($plan->monthly_respondents > 0)
                            <div class="flex justify-between gap-4">
                                <span class="text-[#777777]">
                                    Additional respondents / month
                                </span>
                                <span class="font-medium text-[#222222]">
                                    {{ number_format($plan->monthly_respondents) }}
                                </span>
                            </div>
                        @endif

                        <div class="flex justify-between gap-4">
                            <span class="text-[#777777]">
                                Total respondents over plan period
                            </span>
                            <span class="font-medium text-[#222222]">
                                {{ number_format($totalRespondents) }}
                            </span>
                        </div>

                    </div>

                    {{-- CANCELLATION POLICY --}}
                    <div class="mt-[28px] rounded-[8px] bg-[#F7F7F7] px-[17px] py-[16px]">

                        <p class="text-[12px] font-medium text-[#333333]">
                            Cancellation policy
                        </p>

                        @if ($plan->duration_months === 1)
                            <p class="mt-[9px] text-[11px] leading-[1.8] text-[#666666]">
                                You may cancel automatic renewal.
                                Your current paid month remains available
                                until the end of the billing period.
                            </p>
                        @else
                            <p class="mt-[9px] text-[11px] leading-[1.8] text-[#666666]">
                                You may request early cancellation.
                                Used months, including any started month,
                                are calculated at the standard monthly rate.
                                The remaining eligible amount may be refunded
                                under the cancellation policy.
                            </p>
                        @endif

                    </div>

                    {{-- CHECKOUT STATUS --}}
                    <div class="mt-[25px] rounded-[8px] border border-[#E4EAF2] bg-[#FAFBFE] px-[16px] py-[14px]">

                        <p class="text-[12px] font-medium text-[#333333]">
                            Checkout integration in progress
                        </p>

                        <p class="mt-[6px] text-[11px] leading-[1.7] text-[#777777]">
                            Stripe Checkout is not connected yet.
                            Payment will be enabled after secure
                            backend integration and testing.
                        </p>

                    </div>

                    <button
                        type="button"
                        disabled
                        class="mt-[25px] h-[46px] w-full cursor-not-allowed rounded-[6px] bg-[#4D7FCF] text-[13px] font-medium text-white opacity-60"
                    >
                        Continue to secure checkout
                    </button>

                    <p class="mt-[12px] text-center text-[10px] text-[#999999]">
                        No payment will be charged at this stage.
                    </p>

                </div>
            </div>

            {{-- RIGHT SUMMARY --}}
            <aside class="h-fit rounded-[26px] bg-[#142A78] px-[30px] py-[30px] text-white">

                @if ($isPopular)
                    <div class="inline-flex rounded-full bg-[#758CE5] px-[12px] py-[5px] text-[10px]">
                        Most popular
                    </div>
                @endif

                <h2 class="mt-[16px] text-[20px] font-medium">
                    {{ $plan->name }} plan
                </h2>

                <p class="mt-[12px] text-[12px] text-[#94A9F4]">
                    {{ number_format($totalRespondents) }} total respondents

                    <span class="mx-[8px]">•</span>

                    {{ $billingPeriod }}
                </p>

                <p class="mt-[9px] text-[11px] leading-[1.7] text-[#B7C6FF]">
                    {{ number_format($plan->initial_respondents) }}
                    available initially

                    @if ($plan->monthly_respondents > 0)
                        • +{{ number_format($plan->monthly_respondents) }}
                        in each following month
                    @endif
                </p>

                <div class="mt-[42px] space-y-[14px] text-[12px]">

                    <div class="flex justify-between gap-4">
                        <span class="text-[#8FA4EF]">Subscription</span>

                        <span>
                            ${{ number_format($totalPrice, 2) }}
                        </span>
                    </div>

                    <div class="flex justify-between gap-4">
                        <span class="text-[#8FA4EF]">
                            Respondents included
                        </span>

                        <span>
                            {{ number_format($totalRespondents) }}
                        </span>
                    </div>

                    <div class="flex justify-between gap-4">
                        <span class="text-[#8FA4EF]">Setup fee</span>
                        <span>$0.00</span>
                    </div>

                    <div class="flex justify-between gap-4">
                        <span class="text-[#8FA4EF]">Tax</span>
                        <span class="text-right">
                            Calculated at checkout
                        </span>
                    </div>

                </div>

                <div class="my-[18px] h-px bg-white/20"></div>

                <div class="mt-[28px] flex items-end justify-between gap-4">

                    <span class="text-[11px] text-[#8FA4EF]">
                        Total before tax
                    </span>

                    <span class="text-[28px] font-semibold">
                        ${{ number_format($totalPrice, 2) }}
                    </span>

                </div>

                <button
                    type="button"
                    disabled
                    class="mt-[28px] h-[44px] w-full cursor-not-allowed rounded-full bg-[#4D7FCF] text-[12px] font-medium text-white opacity-60"
                >
                    Continue to secure checkout
                </button>

                <a
                    href="{{ route('pricing') }}"
                    class="mt-[14px] block text-center text-[11px] text-[#8FA4EF] hover:underline"
                >
                    Choose a different plan
                </a>

            </aside>

        </div>
    </div>
</section>

@endsection
