
@php
    use App\Models\WebsiteContent;

    /*
    |--------------------------------------------------------------------------
    | Hero CMS Content
    |--------------------------------------------------------------------------
    |
    | Read published content from the website_contents table.
    | If a CMS entry does not exist, use the original website text.
    |
    */

    $heroTitle = WebsiteContent::getValue(
        'home',
        'hero',
        'title',
        "Design Your\nResearch\nStudy"
    );

    $heroDescription = WebsiteContent::getValue(
        'home',
        'hero',
        'subtitle',
        "Impala transforms how human development is done — through a infinity\nmodel: testing → development → re-testing.\nNo guessing. Only measurable progress."
    );

    $heroButtonText = WebsiteContent::getValue(
        'home',
        'hero',
        'button_text',
        'Get started'
    );
@endphp

<section
    class="mx-auto grid min-h-[610px] w-full max-w-[1173px] grid-cols-1 items-center gap-14 px-5 py-14 md:px-8 lg:grid-cols-[1fr_436px] lg:px-0 lg:py-0"
>
    {{-- Left Content --}}
    <div class="flex flex-col items-start">

        {{-- CMS: Hero Title --}}
        <h1
            class="max-w-[360px] whitespace-pre-line text-[48px] font-semibold leading-[0.98] tracking-[-2px] text-[#111111] sm:text-[54px] lg:text-[58px]"
        >{{ $heroTitle }}</h1>

        {{-- CMS: Hero Description --}}
        <p
            class="mt-[38px] max-w-[608px] whitespace-pre-line text-[16px] font-normal leading-[1.55] tracking-[-0.25px] text-[#2F2F32]"
        >{{ $heroDescription }}</p>

        {{-- CMS: CTA Button --}}
        <a
            href="#start-study"
            class="mt-[32px] inline-flex h-[48px] w-[257px] items-center justify-center gap-2 rounded-full border border-[#BDBDBD] bg-white text-[14px] font-normal text-[#111111] transition hover:border-[#4D7FCF] hover:text-[#4D7FCF]"
        >
            {{ $heroButtonText }}

            <span class="text-[16px] leading-none">
                ›
            </span>
        </a>

    </div>

    {{-- Right Image --}}
    <div class="flex w-full justify-center lg:justify-end">
        <img
            src="{{ asset('images/slika1.png') }}"
            alt="Impala facial recognition research"
            class="h-auto w-full max-w-[436px] object-contain"
        >
    </div>
</section>
