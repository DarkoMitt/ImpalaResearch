
<section
    id="submit-request"
    class="scroll-mt-[74px] bg-white px-6 py-[110px] md:px-10 lg:px-0"
>
    <div
        class="mx-auto w-full max-w-[980px]"
        x-data="{
            fullName: @js(old('full_name', auth()->user()?->name ?? '')),
            email: @js(old('email', auth()->user()?->email ?? '')),
            phone: @js(old('phone', '')),
            company: @js(old('company', auth()->user()?->company_name ?? '')),
            submitting: false,

            errors: {
                fullName: '',
                email: '',
                phone: ''
            },

            validateSubmit(event) {
                if (this.submitting) return;

                this.errors = {
                    fullName: '',
                    email: '',
                    phone: ''
                };

                if (!this.validateStudy()) return;

                let valid = true;

                if (!this.fullName.trim()) {
                    this.errors.fullName = 'Full name is required.';
                    valid = false;
                }

                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (!emailPattern.test(this.email.trim())) {
                    this.errors.email = 'Please enter a valid email address.';
                    valid = false;
                }

                const cleanPhone = this.phone.replace(/\D/g, '');

                if (
                    cleanPhone.length < 9 ||
                    cleanPhone.length > 30 ||
                    !/^\+?[0-9\s().-]{9,30}$/.test(this.phone.trim())
                ) {
                    this.errors.phone = 'Please enter a valid phone number.';
                    valid = false;
                }

                if (!valid) return;

                this.submitting = true;
                event.target.submit();
            }
        }"
    >
        <div class="text-center">
            <h2 class="text-[28px] font-medium leading-tight tracking-[-0.6px] text-[#242424]">
                Almost there! Let’s get your details
            </h2>

            <p class="mx-auto mt-[16px] max-w-[500px] text-[13px] leading-[1.5] text-[#686868]">
                We’ll use this information to send you a tailored proposal.
            </p>
        </div>

        @if ($errors->any())
            <div class="mx-auto mt-[30px] max-w-[790px] rounded-[6px] border border-red-200 bg-red-50 px-[18px] py-[14px] text-left">
                <p class="text-[12px] font-medium text-red-700">
                    Please check your information:
                </p>

                <ul class="mt-[8px] list-disc space-y-[4px] pl-[18px] text-[11px] text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('research-requests.store') }}"
            @submit.prevent="validateSubmit($event)"
            class="mx-auto mt-[76px] grid w-full max-w-[790px] items-start gap-[42px] lg:grid-cols-[1fr_200px]"
            novalidate
        >
            @csrf

            {{-- Alpine Study Builder values --}}
            <input type="hidden" name="goal" :value="goal">
            <input type="hidden" name="country" :value="country">
            <input type="hidden" name="audience" :value="audience">
            <input type="hidden" name="sample_size" :value="sampleSize">
            <input type="hidden" name="timeline" :value="timeline">
            <input type="hidden" name="notes" :value="notes">

            <template x-for="method in methods" :key="method">
                <input type="hidden" name="methods[]" :value="method">
            </template>

            {{-- Contact fields --}}
            <div>
                <div class="grid gap-x-[40px] gap-y-[22px] md:grid-cols-2">

                    {{-- Full Name --}}
                    <label class="block">
                        <span class="text-[13px] font-normal text-[#242424]">
                            Full Name <span class="text-red-500">*</span>
                        </span>

                        <input
                            type="text"
                            name="full_name"
                            x-model="fullName"
                            @input="errors.fullName = ''"
                            maxlength="255"
                            autocomplete="name"
                            placeholder="Enter your name"
                            :class="errors.fullName ? 'border-red-500' : 'border-[#BFBFBF]'"
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
                            name="email"
                            x-model="email"
                            @input="errors.email = ''"
                            maxlength="255"
                            autocomplete="email"
                            placeholder="Enter your email"
                            :class="errors.email ? 'border-red-500' : 'border-[#BFBFBF]'"
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
                            name="phone"
                            x-model="phone"
                            @input="errors.phone = ''"
                            maxlength="30"
                            autocomplete="tel"
                            placeholder="Enter your phone number"
                            :class="errors.phone ? 'border-red-500' : 'border-[#BFBFBF]'"
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
                            Company
                            <span class="text-[11px] text-[#888888]">
                                (optional)
                            </span>
                        </span>

                        <input
                            type="text"
                            name="company"
                            x-model="company"
                            maxlength="255"
                            placeholder="Enter company name"
                            class="mt-[9px] h-[40px] w-full rounded-[3px] border border-[#BFBFBF] px-[13px] text-[12px] text-[#242424] outline-none transition placeholder:text-[#8D8D8D] focus:border-[#4D7FCF]"
                        >
                    </label>
                </div>

                {{-- Notes are already entered in Study Details --}}
                <div class="mt-[22px] rounded-[5px] bg-[#F7F9FD] px-[14px] py-[12px]">
                    <p class="text-[11px] text-[#666666]">
                        Additional study notes
                    </p>

                    <p
                        class="mt-[5px] whitespace-pre-wrap break-words text-[11px] text-[#333333]"
                        x-text="notes || 'No additional notes provided.'"
                    ></p>

                    <button
                        type="button"
                        @click="scrollTo('study-details')"
                        class="mt-[8px] text-[11px] text-[#4D7FCF] hover:underline"
                    >
                        Edit notes
                    </button>
                </div>

                {{-- Submit --}}
                <div class="mt-[62px] flex flex-col items-center">

                    <button
                        type="submit"
                        :disabled="submitting"
                        class="h-[42px] w-[240px] rounded-[3px] bg-[#4D7FCF] text-[13px] font-medium text-white transition hover:bg-[#416FB4] disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        <span x-text="submitting ? 'Submitting...' : 'Submit Request'"></span>
                    </button>

                    @guest
                        <p class="mt-[12px] max-w-[300px] text-center text-[11px] leading-[1.5] text-[#777777]">
                            You’ll be asked to log in or register.
                            Your study details will be saved temporarily.
                        </p>
                    @endguest

                </div>
            </div>

            {{-- Study Summary --}}
            <aside class="rounded-[8px] border border-[#BFBFBF] bg-white px-[18px] py-[18px]">

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
                        <p class="text-[#555555]">Goal</p>
                        <p class="mt-[2px] text-[#333333]" x-text="goal || 'Not selected'"></p>
                    </div>

                    <div>
                        <p class="text-[#555555]">Method</p>
                        <p
                            class="mt-[2px] leading-[1.45] text-[#333333]"
                            x-text="methods.length ? methods.join(', ') : 'Not selected'"
                        ></p>
                    </div>

                    <div>
                        <p class="text-[#555555]">Target Market</p>
                        <p class="mt-[2px] text-[#333333]" x-text="country || 'Not selected'"></p>
                    </div>

                    <div>
                        <p class="text-[#555555]">Audience</p>
                        <p class="mt-[2px] text-[#333333]" x-text="audience || 'Not provided'"></p>
                    </div>

                    <div>
                        <p class="text-[#555555]">Sample Size</p>
                        <p class="mt-[2px] text-[#333333]" x-text="sampleSize || '400'"></p>
                    </div>

                    <div>
                        <p class="text-[#555555]">Timeline</p>
                        <p class="mt-[2px] text-[#333333]" x-text="timeline || 'Not selected'"></p>
                    </div>

                </div>
            </aside>
        </form>
    </div>
</section>
