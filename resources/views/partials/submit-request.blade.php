<section
    id="submit-request"
    class="scroll-mt-[74px] bg-white px-6 py-[110px] md:px-10 lg:px-0"
>
    <div
        class="mx-auto w-full max-w-[980px]"
        x-data="{
            fullName: '',
            email: '',
            phone: '',
            company: '',

            errors: {
                fullName: '',
                email: '',
                phone: '',
                company: ''
            },

            validateSubmit() {
                this.errors = {
                    fullName: '',
                    email: '',
                    phone: '',
                    company: ''
                };

                let valid = true;

                if (!this.fullName.trim()) {
                    this.errors.fullName = 'Full name is required.';
                    valid = false;
                }

                if (!this.email.trim()) {
                    this.errors.email = 'Email is required.';
                    valid = false;
                } else {
                    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                    if (!emailPattern.test(this.email.trim())) {
                        this.errors.email = 'Please enter a valid email address.';
                        valid = false;
                    }
                }

                if (!this.phone.trim()) {
                    this.errors.phone = 'Phone number is required.';
                    valid = false;
                } else {
                    const cleanPhone = this.phone.replace(/\D/g, '');

                    if (cleanPhone.length < 9) {
                        this.errors.phone = 'Phone number must contain at least 9 digits.';
                        valid = false;
                    }
                }

                if (!this.company.trim()) {
                    this.errors.company = 'Company is required.';
                    valid = false;
                }

                if (!goal) {
                    alert('Please select a research goal first.');
                    scrollTo('start-study');
                    return;
                }

                if (!methods.length) {
                    alert('Please select at least one research method.');
                    scrollTo('method');
                    return;
                }

                if (!country) {
                    alert('Please select a target market / country.');
                    scrollTo('study-details');
                    return;
                }

                if (!audience.trim()) {
                    alert('Please enter a target audience.');
                    scrollTo('study-details');
                    return;
                }

                if (!valid) {
                    return;
                }

                window.location.href = '{{ route('login') }}';
            }
        }"
    >

        {{-- Heading --}}
        <div class="text-center">
            <h2
                class="text-[28px] font-medium leading-tight tracking-[-0.6px] text-[#242424]"
            >
                Almost there! Let’s get your details
            </h2>

            <p
                class="mx-auto mt-[16px] max-w-[500px] text-[13px] leading-[1.5] text-[#686868]"
            >
                We’ll use this information to send you a tailored proposal.
            </p>
        </div>

        {{-- Form + Summary --}}
        <form
            class="mx-auto mt-[76px] grid w-full max-w-[790px] items-start gap-[42px] lg:grid-cols-[1fr_200px]"
            @submit.prevent="validateSubmit()"
            novalidate
        >
            {{-- Left side --}}
            <div>

                {{-- Fields --}}
                <div class="grid gap-x-[40px] gap-y-[22px] md:grid-cols-2">

                    {{-- Full Name --}}
                    <label class="block">
                        <span class="text-[13px] font-normal text-[#242424]">
                            Full Name <span class="text-red-500">*</span>
                        </span>

                        <input
                            type="text"
                            x-model="fullName"
                            @input="errors.fullName = ''"
                            placeholder="Enter your name"
                            :class="errors.fullName
                                ? 'border-red-500'
                                : 'border-[#BFBFBF]'"
                            class="mt-[9px] h-[40px] w-full rounded-[3px] border px-[13px] text-[12px] text-[#242424] outline-none transition placeholder:text-[#8D8D8D] focus:border-[#4D7FCF]"
                        >

                        <p
                            x-show="errors.fullName"
                            x-text="errors.fullName"
                            class="mt-[5px] text-[10px] text-red-500"
                        ></p>
                    </label>

                    {{-- Email --}}
                    <label class="block">
                        <span class="text-[13px] font-normal text-[#242424]">
                            Email <span class="text-red-500">*</span>
                        </span>

                        <input
                            type="email"
                            x-model="email"
                            @input="errors.email = ''"
                            placeholder="Enter your email"
                            :class="errors.email
                                ? 'border-red-500'
                                : 'border-[#BFBFBF]'"
                            class="mt-[9px] h-[40px] w-full rounded-[3px] border px-[13px] text-[12px] text-[#242424] outline-none transition placeholder:text-[#8D8D8D] focus:border-[#4D7FCF]"
                        >

                        <p
                            x-show="errors.email"
                            x-text="errors.email"
                            class="mt-[5px] text-[10px] text-red-500"
                        ></p>
                    </label>

                    {{-- Phone --}}
                    <label class="block">
                        <span class="text-[13px] font-normal text-[#242424]">
                            Phone Number <span class="text-red-500">*</span>
                        </span>

                        <input
                            type="tel"
                            x-model="phone"
                            @input="errors.phone = ''"
                            placeholder="Enter your phone number"
                            :class="errors.phone
                                ? 'border-red-500'
                                : 'border-[#BFBFBF]'"
                            class="mt-[9px] h-[40px] w-full rounded-[3px] border px-[13px] text-[12px] text-[#242424] outline-none transition placeholder:text-[#8D8D8D] focus:border-[#4D7FCF]"
                        >

                        <p
                            x-show="errors.phone"
                            x-text="errors.phone"
                            class="mt-[5px] text-[10px] text-red-500"
                        ></p>
                    </label>

                    {{-- Company --}}
                    <label class="block">
                        <span class="text-[13px] font-normal text-[#242424]">
                            Company <span class="text-red-500">*</span>
                        </span>

                        <input
                            type="text"
                            x-model="company"
                            @input="errors.company = ''"
                            placeholder="Enter company name"
                            :class="errors.company
                                ? 'border-red-500'
                                : 'border-[#BFBFBF]'"
                            class="mt-[9px] h-[40px] w-full rounded-[3px] border px-[13px] text-[12px] text-[#242424] outline-none transition placeholder:text-[#8D8D8D] focus:border-[#4D7FCF]"
                        >

                        <p
                            x-show="errors.company"
                            x-text="errors.company"
                            class="mt-[5px] text-[10px] text-red-500"
                        ></p>
                    </label>
                </div>

                {{-- Notes --}}
                <label class="mt-[22px] block">
                    <span class="text-[12px] font-normal text-[#242424]">
                        Anything else we should know? (optional)
                    </span>

                    <textarea
                        x-model="notes"
                        placeholder="Add any specific requirements or context about your study..."
                        class="mt-[9px] h-[86px] w-full resize-none rounded-[3px] border border-[#BFBFBF] px-[13px] py-[12px] text-[12px] text-[#242424] outline-none transition placeholder:text-[#8D8D8D] focus:border-[#4D7FCF]"
                    ></textarea>
                </label>

                {{-- Submit --}}
                <div class="mt-[62px] flex justify-center">
                    <button
                        type="submit"
                        class="h-[42px] w-[240px] rounded-[3px] bg-[#4D7FCF] text-[13px] font-medium text-white transition hover:bg-[#416FB4]"
                    >
                        Submit Request
                    </button>
                </div>
            </div>

            {{-- Study Summary --}}
            <aside
                class="rounded-[8px] border border-[#BFBFBF] bg-white px-[18px] py-[18px]"
            >
                <div class="flex items-center justify-between">
                    <h3 class="text-[12px] font-medium text-[#242424]">
                        Study Summary
                    </h3>

                    <button
                        type="button"
                        @click="scrollTo('start-study')"
                        class="text-[11px] text-[#242424] underline transition hover:text-[#4D7FCF]"
                    >
                        Edit
                    </button>
                </div>

                <div class="mt-[20px] space-y-[18px] text-[11px]">

                    <div>
                        <p class="text-[#555555]">
                            Goal
                        </p>

                        <p
                            class="mt-[2px] text-[#333333]"
                            x-text="goal || 'Not selected'"
                        ></p>
                    </div>

                    <div>
                        <p class="text-[#555555]">
                            Method
                        </p>

                        <p
                            class="mt-[2px] leading-[1.45] text-[#333333]"
                            x-text="methods.length ? methods.join(', ') : 'Not selected'"
                        ></p>
                    </div>

                    <div>
                        <p class="text-[#555555]">
                            Target Market
                        </p>

                        <p
                            class="mt-[2px] text-[#333333]"
                            x-text="country || 'Not selected'"
                        ></p>
                    </div>

                    <div>
                        <p class="text-[#555555]">
                            Audience
                        </p>

                        <p
                            class="mt-[2px] text-[#333333]"
                            x-text="audience || 'Not provided'"
                        ></p>
                    </div>

                    <div>
                        <p class="text-[#555555]">
                            Sample Size
                        </p>

                        <p
                            class="mt-[2px] text-[#333333]"
                            x-text="sampleSize || '400'"
                        ></p>
                    </div>

                    <div>
                        <p class="text-[#555555]">
                            Timeline
                        </p>

                        <p
                            class="mt-[2px] text-[#333333]"
                            x-text="timeline || 'Not selected'"
                        ></p>
                    </div>
                </div>
            </aside>
        </form>
    </div>
</section>