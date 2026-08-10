@extends('layouts.app')

@section('content')
<section
    class="min-h-screen w-full bg-white px-6 py-[48px] md:px-10 lg:px-[70px]"
    x-data="paymentPage()"
>
    <div class="mx-auto w-full max-w-[1320px]">

        {{-- HEADER --}}
        <div>
            <h1 class="text-[24px] font-medium text-[#111111]">
                Confirm and pay
            </h1>

            <p class="mt-[10px] max-w-[520px] text-[13px] leading-[1.5] text-[#555555]">
                You're subscribing to the
                <span x-text="planLabel"></span>
                plan. Cancel or switch plans anytime from Billing.
            </p>
        </div>

        {{-- PAYMENT CONTENT --}}
        <div
            class="mt-[52px] grid gap-[48px] lg:grid-cols-[1fr_430px]"
        >
            {{-- LEFT PAYMENT FORM --}}
            <div
                class="rounded-[18px] border border-[#D8D8D8] bg-white p-[22px]"
            >
                {{-- Payment methods --}}
                <div class="grid grid-cols-3 gap-[10px]">
                    <button
                        type="button"
                        @click="paymentMethod = 'card'"
                        :class="paymentMethod === 'card'
                            ? 'border-[#4D7FCF] bg-[#F5F7FF] text-[#222222]'
                            : 'border-transparent bg-[#F1F1F1] text-[#555555]'"
                        class="h-[42px] rounded-[7px] border text-[12px]"
                    >
                        Card
                    </button>

                    <button
                        type="button"
                        @click="paymentMethod = 'paypal'"
                        :class="paymentMethod === 'paypal'
                            ? 'border-[#4D7FCF] bg-[#F5F7FF] text-[#222222]'
                            : 'border-transparent bg-[#F1F1F1] text-[#555555]'"
                        class="h-[42px] rounded-[7px] border text-[12px]"
                    >
                        PayPal
                    </button>

                    <button
                        type="button"
                        @click="paymentMethod = 'bank'"
                        :class="paymentMethod === 'bank'
                            ? 'border-[#4D7FCF] bg-[#F5F7FF] text-[#222222]'
                            : 'border-transparent bg-[#F1F1F1] text-[#555555]'"
                        class="h-[42px] rounded-[7px] border text-[12px]"
                    >
                        Bank transfer
                    </button>
                </div>

                {{-- CARD FORM --}}
                <div x-show="paymentMethod === 'card'" class="mt-[28px]">

                    <p class="text-[12px] text-[#555555]">
                        Card Information
                    </p>

                    <input
                        type="text"
                        x-model="cardNumber"
                        maxlength="19"
                        placeholder="4242 4242 4242 4242"
                        class="mt-[10px] h-[42px] w-full rounded-[4px] border border-[#CFCFCF] px-[13px] text-[12px] outline-none focus:border-[#4D7FCF]"
                    >

                    <div class="mt-[12px] grid grid-cols-2 gap-[14px]">
                        <input
                            type="text"
                            x-model="expiry"
                            maxlength="5"
                            placeholder="MM / YY"
                            class="h-[42px] rounded-[4px] border border-[#CFCFCF] px-[13px] text-[12px] outline-none focus:border-[#4D7FCF]"
                        >

                        <input
                            type="text"
                            x-model="cvc"
                            maxlength="4"
                            placeholder="CVC"
                            class="h-[42px] rounded-[4px] border border-[#CFCFCF] px-[13px] text-[12px] outline-none focus:border-[#4D7FCF]"
                        >
                    </div>

                    <label class="mt-[22px] block">
                        <span class="text-[12px] text-[#555555]">
                            Name on card
                        </span>

                        <input
                            type="text"
                            x-model="cardName"
                            placeholder="Sara Krstic"
                            class="mt-[9px] h-[42px] w-full rounded-[4px] border border-[#CFCFCF] px-[13px] text-[12px] outline-none focus:border-[#4D7FCF]"
                        >
                    </label>

                    <div class="mt-[18px] grid grid-cols-2 gap-[14px]">
                        <label>
                            <span class="text-[12px] text-[#555555]">
                                Country
                            </span>

                            <input
                                type="text"
                                x-model="country"
                                placeholder="North Macedonia"
                                class="mt-[9px] h-[42px] w-full rounded-[4px] border border-[#CFCFCF] px-[13px] text-[12px] outline-none focus:border-[#4D7FCF]"
                            >
                        </label>

                        <label>
                            <span class="text-[12px] text-[#555555]">
                                ZIP / Postal code
                            </span>

                            <input
                                type="text"
                                x-model="zip"
                                placeholder="1000"
                                class="mt-[9px] h-[42px] w-full rounded-[4px] border border-[#CFCFCF] px-[13px] text-[12px] outline-none focus:border-[#4D7FCF]"
                            >
                        </label>
                    </div>

                    <div class="my-[24px] flex items-center gap-[8px]">
                        <div class="h-px flex-1 bg-[#D6D6D6]"></div>

                        <span class="text-[10px] uppercase text-[#8A8A8A]">
                            Billing details
                        </span>

                        <div class="h-px flex-1 bg-[#D6D6D6]"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-[14px]">
                        <label>
                            <span class="text-[12px] text-[#555555]">
                                Company
                            </span>

                            <input
                                type="text"
                                x-model="company"
                                placeholder="Company name"
                                class="mt-[9px] h-[42px] w-full rounded-[4px] border border-[#CFCFCF] px-[13px] text-[12px] outline-none focus:border-[#4D7FCF]"
                            >
                        </label>

                        <label>
                            <span class="text-[12px] text-[#555555]">
                                Tax ID (optional)
                            </span>

                            <input
                                type="text"
                                x-model="taxId"
                                placeholder="Tax ID"
                                class="mt-[9px] h-[42px] w-full rounded-[4px] border border-[#CFCFCF] px-[13px] text-[12px] outline-none focus:border-[#4D7FCF]"
                            >
                        </label>
                    </div>

                    <label class="mt-[18px] flex items-center gap-[10px] text-[11px] text-[#555555]">
                        <input
                            type="checkbox"
                            x-model="saveCard"
                            class="h-[15px] w-[15px] accent-[#4D7FCF]"
                        >

                        Save this card as the default payment method for future renewals.
                    </label>

                    <p
                        x-show="error"
                        x-cloak
                        x-text="error"
                        class="mt-[16px] text-center text-[11px] text-red-600"
                    ></p>

                    <button
                        type="button"
                        @click="pay()"
                        class="mt-[28px] h-[46px] w-full rounded-[6px] bg-[#4D7FCF] text-[13px] font-medium text-white transition hover:bg-[#416FB4]"
                    >
                        Pay $<span x-text="price"></span> and activate plan
                    </button>
                </div>

                {{-- PAYPAL DEMO --}}
                <div
                    x-show="paymentMethod === 'paypal'"
                    class="mt-[34px] rounded-[10px] bg-[#F7F7F7] p-[24px] text-center"
                >
                    <p class="text-[13px] text-[#444444]">
                        PayPal checkout will be available in the production version.
                    </p>
                </div>

                {{-- BANK DEMO --}}
                <div
                    x-show="paymentMethod === 'bank'"
                    class="mt-[34px] rounded-[10px] bg-[#F7F7F7] p-[24px] text-center"
                >
                    <p class="text-[13px] text-[#444444]">
                        Bank transfer instructions will be provided here.
                    </p>
                </div>
            </div>

            {{-- RIGHT SUMMARY --}}
            <aside
                class="h-fit rounded-[26px] bg-[#142A78] px-[30px] py-[30px] text-white"
            >
                <div
                    x-show="selectedPlan === 'semi-yearly'"
                    class="inline-flex rounded-full bg-[#758CE5] px-[12px] py-[5px] text-[10px]"
                >
                    Most popular
                </div>

                <h2
                    class="mt-[16px] text-[20px] font-medium"
                    x-text="planLabel + ' plan'"
                ></h2>

                <p class="mt-[12px] text-[12px] text-[#94A9F4]">
                    <span x-text="respondents"></span> respondents

                    <span class="mx-[8px]">
                        •
                    </span>

                    <span x-text="duration"></span>
                </p>

                <div class="mt-[42px] space-y-[14px] text-[12px]">

                    <div class="flex justify-between">
                        <span class="text-[#8FA4EF]">
                            Subscription
                        </span>

                        <span>
                            $<span x-text="price"></span>
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-[#8FA4EF]">
                            Respondents included
                        </span>

                        <span x-text="respondents"></span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-[#8FA4EF]">
                            Setup fee
                        </span>

                        <span>
                            $0.00
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-[#8FA4EF]">
                            Tax
                        </span>

                        <span>
                            Calculated at checkout
                        </span>
                    </div>

                </div>

                <div class="my-[18px] h-px bg-white/20"></div>

                <div class="mt-[28px] flex items-end justify-between">

                    <span class="text-[11px] text-[#8FA4EF]">
                        Total due today
                    </span>

                    <span class="text-[28px] font-semibold">
                        $<span x-text="price"></span>
                    </span>

                </div>

                <button
                    type="button"
                    @click="pay()"
                    class="mt-[28px] h-[44px] w-full rounded-full bg-[#4D7FCF] text-[12px] font-medium text-white transition hover:bg-[#5C89D0]"
                >
                    Activate plan
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

<script>
    function paymentPage() {
        const params = new URLSearchParams(window.location.search);

        const selectedPlan =
            params.get('plan') || 'semi-yearly';

        const plans = {
            monthly: {
                label: 'Monthly',
                price: '390.00',
                respondents: '500',
                duration: '1 month'
            },

            'semi-yearly': {
                label: 'Semi-Yearly',
                price: '720.00',
                respondents: '500',
                duration: '6 months'
            },

            yearly: {
                label: 'Yearly',
                price: '1320.00',
                respondents: '500',
                duration: '12 months'
            }
        };

        const plan =
            plans[selectedPlan] || plans['semi-yearly'];

        return {
            selectedPlan: selectedPlan,

            planLabel: plan.label,
            price: plan.price,
            respondents: plan.respondents,
            duration: plan.duration,

            paymentMethod: 'card',

            cardNumber: '',
            expiry: '',
            cvc: '',
            cardName: '',
            country: '',
            zip: '',
            company: '',
            taxId: '',
            saveCard: false,

            error: '',

            pay() {
                this.error = '';

                if (this.paymentMethod !== 'card') {
                    return;
                }

                const cardDigits =
                    this.cardNumber.replace(/\D/g, '');

                if (cardDigits.length !== 16) {
                    this.error =
                        'Please enter a valid 16-digit card number.';
                    return;
                }

                if (
                    !/^\d{2}\/\d{2}$/.test(
                        this.expiry.replace(/\s/g, '')
                    )
                ) {
                    this.error =
                        'Please enter expiry date as MM/YY.';
                    return;
                }

                if (!/^\d{3,4}$/.test(this.cvc)) {
                    this.error =
                        'Please enter a valid CVC.';
                    return;
                }

                if (!this.cardName.trim()) {
                    this.error =
                        'Name on card is required.';
                    return;
                }

                if (!this.country.trim()) {
                    this.error =
                        'Country is required.';
                    return;
                }

                if (!this.zip.trim()) {
                    this.error =
                        'ZIP / Postal code is required.';
                    return;
                }

                alert('Demo payment successful. Plan activated.');

                window.location.href = '{{ route('dashboard') }}';
            }
        };
    }
</script>
@endsection