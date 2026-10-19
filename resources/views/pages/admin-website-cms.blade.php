
@extends('layouts.app')

@section('content')
@php
    $homeContents = $contents->get('home', collect());

    /*
    |--------------------------------------------------------------------------
    | Hero
    |--------------------------------------------------------------------------
    */
    $heroContents = $homeContents->where('section', 'hero')->keyBy('key');

    $heroFields = [
        'title' => [
            'label' => 'Hero Title',
            'default' => "Design Your\nResearch\nStudy",
            'type' => 'textarea',
            'rows' => 3,
            'required' => true,
            'hint' => 'Use line breaks to control the headline layout.',
        ],
        'subtitle' => [
            'label' => 'Hero Description',
            'default' => "Impala transforms how human development is done — through a infinity\nmodel: testing → development → re-testing.\nNo guessing. Only measurable progress.",
            'type' => 'textarea',
            'rows' => 5,
            'required' => false,
            'hint' => 'Supporting description below the headline.',
        ],
        'button_text' => [
            'label' => 'CTA Button Text',
            'default' => 'Get started',
            'type' => 'text',
            'required' => true,
            'hint' => 'Text displayed inside the main action button.',
        ],
    ];

    /*
    |--------------------------------------------------------------------------
    | Study Progress
    |--------------------------------------------------------------------------
    */
    $progressContents = $homeContents->where('section', 'study_progress')->keyBy('key');

    $progressFields = [
        'goal_label' => [
            'label' => 'Goal Label',
            'default' => 'Goal',
            'type' => 'text',
            'required' => true,
            'hint' => 'First navigation step.',
        ],
        'method_label' => [
            'label' => 'Method Label',
            'default' => 'Method',
            'type' => 'text',
            'required' => true,
            'hint' => 'Second navigation step.',
        ],
        'audience_label' => [
            'label' => 'Audience Label',
            'default' => 'Audience',
            'type' => 'text',
            'required' => true,
            'hint' => 'Third navigation step.',
        ],
        'review_label' => [
            'label' => 'Review Label',
            'default' => 'Review',
            'type' => 'text',
            'required' => true,
            'hint' => 'Fourth navigation step.',
        ],
    ];

    /*
    |--------------------------------------------------------------------------
    | Study Builder
    |--------------------------------------------------------------------------
    */
    $builderContents = $homeContents->where('section', 'study_builder')->keyBy('key');

    $builderFields = [
        'heading' => [
            'label' => 'Section Heading',
            'default' => 'What do you want to test?',
            'type' => 'text',
            'max' => 120,
            'required' => true,
            'hint' => 'Main heading above the research goal cards.',
        ],
        'description' => [
            'label' => 'Section Description',
            'default' => 'Choose the area you want insights on. We’ll recommend the best research methods for you.',
            'type' => 'textarea',
            'rows' => 3,
            'max' => 500,
            'required' => true,
            'hint' => 'Introductory text below the heading.',
        ],
        'product_title' => [
            'label' => 'Product Card Title',
            'default' => 'Product',
            'type' => 'text',
            'max' => 40,
            'required' => true,
            'hint' => 'Visible title only. Internal value: Product Research.',
        ],
        'product_description' => [
            'label' => 'Product Card Description',
            'default' => 'Understand which features or options matter most to your customers.',
            'type' => 'textarea',
            'rows' => 2,
            'max' => 300,
            'required' => true,
            'hint' => 'Description of the Product Research option.',
        ],
        'pricing_title' => [
            'label' => 'Pricing Card Title',
            'default' => 'Pricing',
            'type' => 'text',
            'max' => 40,
            'required' => true,
            'hint' => 'Visible title only. Internal value: Pricing Research.',
        ],
        'pricing_description' => [
            'label' => 'Pricing Card Description',
            'default' => 'Find the right price point people are willing to pay.',
            'type' => 'textarea',
            'rows' => 2,
            'max' => 300,
            'required' => true,
            'hint' => 'Description of the Pricing Research option.',
        ],
        'concept_title' => [
            'label' => 'Concept Card Title',
            'default' => 'Concept',
            'type' => 'text',
            'max' => 40,
            'required' => true,
            'hint' => 'Visible title only. Internal value: Concept Research.',
        ],
        'concept_description' => [
            'label' => 'Concept Card Description',
            'default' => 'Test new ideas or products before launching them.',
            'type' => 'textarea',
            'rows' => 2,
            'max' => 300,
            'required' => true,
            'hint' => 'Description of the Concept Research option.',
        ],
        'brand_title' => [
            'label' => 'Brand Card Title',
            'default' => 'Brand',
            'type' => 'text',
            'max' => 40,
            'required' => true,
            'hint' => 'Visible title only. Internal value: Brand Research.',
        ],
        'brand_description' => [
            'label' => 'Brand Card Description',
            'default' => 'Measure how people see, recognize, and trust your brand.',
            'type' => 'textarea',
            'rows' => 2,
            'max' => 300,
            'required' => true,
            'hint' => 'Description of the Brand Research option.',
        ],
        'ux_title' => [
            'label' => 'UX Card Title',
            'default' => 'UX',
            'type' => 'text',
            'max' => 40,
            'required' => true,
            'hint' => 'Visible title only. Internal value: UX Research.',
        ],
        'ux_description' => [
            'label' => 'UX Card Description',
            'default' => 'Improve how users interact with your website or app.',
            'type' => 'textarea',
            'rows' => 2,
            'max' => 300,
            'required' => true,
            'hint' => 'Description of the UX Research option.',
        ],
        'not_sure_title' => [
            'label' => 'Not Sure Card Title',
            'default' => 'Not sure',
            'type' => 'text',
            'max' => 40,
            'required' => true,
            'hint' => 'Visible title only. Internal value: Not sure.',
        ],
        'not_sure_description' => [
            'label' => 'Not Sure Card Description',
            'default' => 'Not sure what you need? We’ll help you choose the right approach.',
            'type' => 'textarea',
            'rows' => 2,
            'max' => 300,
            'required' => true,
            'hint' => 'Description of the guided research option.',
        ],
        'next_button_text' => [
            'label' => 'Next Button Text',
            'default' => 'Next',
            'type' => 'text',
            'max' => 30,
            'required' => true,
            'hint' => 'Text displayed on the navigation button.',
        ],
    ];

    /*
    |--------------------------------------------------------------------------
    | Prepare Published / Draft Values
    |--------------------------------------------------------------------------
    */
    $sectionDefinitions = [
        'hero' => [
            'name' => 'Hero Section',
            'subtitle' => 'Main headline, description and CTA button',
            'fields' => $heroFields,
            'records' => $heroContents,
        ],
        'study_progress' => [
            'name' => 'Study Progress Navigation',
            'subtitle' => 'Four labels for the sticky research navigation',
            'fields' => $progressFields,
            'records' => $progressContents,
        ],
        'study_builder' => [
            'name' => 'Study Builder',
            'subtitle' => 'Research goal heading, six cards and Next button',
            'fields' => $builderFields,
            'records' => $builderContents,
        ],
    ];

    $sectionData = [];

    foreach ($sectionDefinitions as $sectionKey => $definition) {
        $published = [];
        $editor = [];

        foreach ($definition['fields'] as $key => $field) {
            $record = $definition['records']->get($key);

            $publishedValue = $record?->is_published
                ? ($record->value ?? $field['default'])
                : $field['default'];

            $published[$key] = $publishedValue;

            $editor[$key] = old('section') === $sectionKey
                ? old("contents.{$key}", '')
                : ($record?->has_draft
                    ? ($record->draft_value ?? '')
                    : $publishedValue);
        }

        $sectionData[$sectionKey] = [
            'published' => $published,
            'editor' => $editor,
            'hasDraft' => $definition['records']->contains(
                fn ($item) => $item->has_draft
            ),
            'draftCount' => $definition['records']->filter(
                fn ($item) => $item->has_draft
            )->count(),
        ];
    }

    $cmsSections = [
        [
            'key' => 'hero',
            'number' => '01',
            'name' => 'Hero Section',
            'subtitle' => 'Main headline, description and CTA button',
            'fields' => $heroFields,
            'color' => 'blue',
        ],
        [
            'key' => 'study_progress',
            'number' => '02',
            'name' => 'Study Progress Navigation',
            'subtitle' => 'Edit the four navigation labels without changing their behavior',
            'fields' => $progressFields,
            'color' => 'purple',
        ],
        [
            'key' => 'study_builder',
            'number' => '03',
            'name' => 'Study Builder',
            'subtitle' => 'Customize all six research goal cards and section copy',
            'fields' => $builderFields,
            'color' => 'blue',
        ],
    ];
@endphp

<div class="flex min-h-screen w-full bg-white">
    @include('partials.admin-sidebar')

    <main class="min-w-0 flex-1 bg-gradient-to-br from-[#DCEAFF] via-[#F5F9FF] to-white px-5 py-8 lg:px-9 lg:py-10">
        <div class="mx-auto w-full max-w-[1250px]">

            {{-- Page Header --}}
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <span class="inline-flex items-center gap-2 rounded-full border border-[#D2E2FF] bg-white/80 px-3 py-1.5 text-[10px] font-bold text-[#3478F6]">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#3478F6]"></span>
                        Content Management
                    </span>

                    <h1 class="mt-3 text-[30px] font-bold tracking-[-1px] text-[#0B1B39]">
                        Website CMS
                    </h1>

                    <p class="mt-2 text-[12px] text-[#7184A5]">
                        Create, preview and publish content for your public website.
                    </p>
                </div>

                <a
                    href="{{ route('home') }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex h-10 items-center gap-2 rounded-[11px] border border-[#D8E5F6] bg-white px-4 text-[11px] font-bold text-[#526887] transition hover:border-[#397CF6] hover:text-[#397CF6]"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M14 3h7v7M10 14 21 3"/>
                        <path d="M21 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h6"/>
                    </svg>
                    View Website
                </a>
            </div>

            {{-- Notifications --}}
            @if(session('cms_success'))
                <div class="mt-6 flex items-start gap-3 rounded-[14px] border border-[#BDE8D2] bg-[#EDFBF3] px-5 py-4">
                    <svg class="h-5 w-5 shrink-0 text-[#149B69]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M8 12l3 3 5-6"/>
                    </svg>
                    <p class="text-[12px] font-semibold text-[#14875E]">
                        {{ session('cms_success') }}
                    </p>
                </div>
            @endif

            @if(session('cms_info'))
                <div class="mt-6 rounded-[14px] border border-[#C9DDFB] bg-[#EEF5FF] px-5 py-4">
                    <p class="text-[12px] font-semibold text-[#397CF6]">
                        {{ session('cms_info') }}
                    </p>
                </div>
            @endif

            @if($errors->any())
                <div class="mt-6 rounded-[14px] border border-[#F1CDD2] bg-[#FFF4F5] px-5 py-4">
                    <p class="text-[12px] font-bold text-[#C44858]">
                        Please review the following errors:
                    </p>

                    <ul class="mt-2 list-inside list-disc space-y-1 text-[11px] text-[#C44858]">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Statistics --}}
            <div class="mt-7 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach([
                    [
                        'label' => 'Total Content',
                        'value' => $totalContents,
                        'caption' => 'Stored content entries',
                        'background' => 'bg-[#EEF5FF]',
                        'border' => 'border-[#D9E7FF]',
                        'color' => 'text-[#163968]',
                    ],
                    [
                        'label' => 'Published',
                        'value' => $publishedContents,
                        'caption' => 'Currently live entries',
                        'background' => 'bg-[#EEFBF5]',
                        'border' => 'border-[#D3EFE2]',
                        'color' => 'text-[#247D59]',
                    ],
                    [
                        'label' => 'Unpublished',
                        'value' => $unpublishedContents,
                        'caption' => 'Not currently published',
                        'background' => 'bg-[#F6F1FF]',
                        'border' => 'border-[#E8DEFA]',
                        'color' => 'text-[#7555B4]',
                    ],
                    [
                        'label' => 'Saved Drafts',
                        'value' => $draftContents,
                        'caption' => 'Entries awaiting publication',
                        'background' => 'bg-[#FFF8EC]',
                        'border' => 'border-[#F7E3BF]',
                        'color' => 'text-[#B47B2D]',
                    ],
                ] as $stat)
                    <div class="rounded-[17px] border p-5 {{ $stat['background'] }} {{ $stat['border'] }}">
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-[11px] font-bold {{ $stat['color'] }}">
                                {{ $stat['label'] }}
                            </span>

                            <span class="flex h-9 w-9 items-center justify-center rounded-[11px] bg-white/60 {{ $stat['color'] }}">
                                <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <rect x="4" y="4" width="16" height="16" rx="3"/>
                                    <path d="M8 9h8M8 13h8M8 17h5"/>
                                </svg>
                            </span>
                        </div>

                        <p class="mt-4 text-[28px] font-bold {{ $stat['color'] }}">
                            {{ $stat['value'] }}
                        </p>

                        <p class="mt-1 text-[10px] text-[#8197B7]">
                            {{ $stat['caption'] }}
                        </p>
                    </div>
                @endforeach
            </div>

            {{-- Workflow --}}
            <div class="mt-6 rounded-[17px] border border-[#D8E6FA] bg-white/85 p-5">
                <div class="flex flex-wrap items-center gap-3 text-[11px] font-bold">
                    @foreach(['Edit Content', 'Save Draft', 'Preview', 'Publish'] as $index => $step)
                        @if($index > 0)
                            <span class="text-[#B4C3D9]">→</span>
                        @endif

                        <span class="inline-flex items-center gap-2 {{ $index === 3 ? 'text-[#20A675]' : 'text-[#397CF6]' }}">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full {{ $index === 3 ? 'bg-[#E9F9F3]' : 'bg-[#EAF2FF]' }} text-[10px]">
                                {{ $index + 1 }}
                            </span>
                            {{ $step }}
                        </span>
                    @endforeach
                </div>

                <p class="mt-3 text-[10px] leading-5 text-[#8192AB]">
                    Drafts are private. Public website content changes only after publication.
                </p>
            </div>

            {{-- Home Page --}}
            <div class="mt-8 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-[17px] font-bold text-[#102243]">
                        Home Page
                    </h2>

                    <p class="mt-1 text-[11px] text-[#8192AB]">
                        Manage your website's main sections independently.
                    </p>
                </div>

                <span class="rounded-full border border-[#D9E6FA] bg-white px-3 py-1.5 text-[10px] font-bold text-[#7184A5]">
                    3 Sections Available
                </span>
            </div>

            {{-- CMS Sections --}}
            @foreach($cmsSections as $section)
                @php
                    $sectionKey = $section['key'];
                    $sectionState = $sectionData[$sectionKey];
                    $sectionFields = $section['fields'];
                    $isHero = $sectionKey === 'hero';
                    $isProgress = $sectionKey === 'study_progress';
                    $isBuilder = $sectionKey === 'study_builder';
                @endphp

                <div
                    id="cms-{{ str_replace('_', '-', $sectionKey) }}"
                    class="mt-8"
                    x-data="{
                        fields: @js($sectionState['editor']),
                        savedFields: @js($sectionState['editor']),
                        publishedFields: @js($sectionState['published']),
                        fieldKeys: @js(array_keys($sectionFields)),
                        hasDraft: @js($sectionState['hasDraft']),
                        previewMode: 'draft',
                        activePreviewStep: 0,
                        selectedGoal: null,

                        get hasChanges() {
                            return this.fieldKeys.some(
                                key => this.fields[key] !== this.savedFields[key]
                            );
                        },

                        get previewFields() {
                            return this.previewMode === 'published'
                                ? this.publishedFields
                                : this.fields;
                        },

                        resetChanges() {
                            this.fields = { ...this.savedFields };
                        },

                        confirmPublish() {
                            if (this.hasChanges) {
                                alert('You have unsaved changes. Save your draft before publishing.');
                                return false;
                            }

                            if (!this.hasDraft) {
                                return false;
                            }

                            return confirm(
                                'Publish the {{ $section['name'] }} draft? These changes will become visible on your public website.'
                            );
                        },

                        confirmDiscard() {
                            if (!this.hasDraft) {
                                return false;
                            }

                            return confirm(
                                'Discard the {{ $section['name'] }} draft? Your published website content will remain unchanged.'
                            );
                        }
                    }"
                >
                    {{-- Section Header --}}
                    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h2 class="text-[15px] font-bold text-[#102243]">
                                {{ $section['name'] }}
                            </h2>

                            <p class="mt-1 text-[11px] text-[#8192AB]">
                                {{ $section['subtitle'] }}
                            </p>
                        </div>

                        <span class="rounded-full border border-[#D9E6FA] bg-white px-3 py-1.5 text-[10px] font-bold text-[#7184A5]">
                            Home Page · Section {{ $section['number'] }}
                        </span>
                    </div>

                    <div class="grid items-start gap-6 xl:grid-cols-2">

                        {{-- Editor --}}
                        <section class="overflow-hidden rounded-[19px] border border-[#DFE8F6] bg-white shadow-[0_2px_6px_rgba(30,64,120,0.06)]">
                            <div class="flex items-center gap-3 border-b border-[#EDF1F8] px-6 py-5">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-[13px] bg-[#EAF2FF] text-[#397CF6]">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <rect x="4" y="4" width="16" height="16" rx="2"/>
                                        <path d="M8 9h8M8 13h8M8 17h4"/>
                                    </svg>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <h3 class="text-[13px] font-bold text-[#102243]">
                                        {{ $isBuilder ? 'Research Goal Content' : ($isProgress ? 'Progress Labels' : 'Hero Content') }}
                                    </h3>

                                    <p class="mt-1 text-[10px] text-[#91A0B6]">
                                        {{ count($sectionFields) }} editable content fields
                                    </p>
                                </div>

                                @if($sectionState['hasDraft'])
                                    <span class="shrink-0 rounded-full bg-[#FFF2D9] px-3 py-1.5 text-[9px] font-bold text-[#BD852C]">
                                        Draft Saved
                                    </span>
                                @else
                                    <span class="shrink-0 rounded-full bg-[#EAF7EF] px-3 py-1.5 text-[9px] font-bold text-[#23976B]">
                                        Published
                                    </span>
                                @endif
                            </div>

                            @if($sectionState['hasDraft'])
                                <div class="mx-6 mt-5 rounded-[12px] border border-[#F4DDB4] bg-[#FFFAF0] px-4 py-3">
                                    <p class="text-[11px] font-bold text-[#B78231]">
                                        Unpublished changes available
                                    </p>

                                    <p class="mt-1 text-[10px] leading-5 text-[#A48B67]">
                                        {{ $sectionState['draftCount'] }} saved draft item(s).
                                        Review and publish when ready.
                                    </p>
                                </div>
                            @endif

                            <form
                                method="POST"
                                action="{{ route('admin.website-cms.update') }}"
                                class="p-6"
                                x-on:submit="previewMode = 'draft'"
                            >
                                @csrf
                                @method('PATCH')

                                <input type="hidden" name="page" value="home">
                                <input type="hidden" name="section" value="{{ $sectionKey }}">

                                @if($isBuilder)
                                    <div class="mb-6 rounded-[12px] border border-[#DCE8FA] bg-[#F4F8FF] px-4 py-3">
                                        <p class="text-[11px] font-bold text-[#397CF6]">
                                            Protected Research Logic
                                        </p>

                                        <p class="mt-1 text-[10px] leading-[1.8] text-[#7184A5]">
                                            You can edit visible content only.
                                            Internal goal identifiers, card selection,
                                            validation and navigation are protected.
                                        </p>
                                    </div>
                                @elseif($isProgress)
                                    <div class="mb-6 rounded-[12px] border border-[#DCE8FA] bg-[#F4F8FF] px-4 py-3">
                                        <p class="text-[10px] leading-[1.8] text-[#7184A5]">
                                            Only visible labels are editable.
                                            Scroll targets and progress animations remain unchanged.
                                        </p>
                                    </div>
                                @endif

                                <div class="space-y-5">
                                    @foreach($sectionFields as $key => $field)
                                        <div>
                                            @if($isBuilder && str_ends_with($key, '_title') && $key !== 'heading')
                                                <div class="mb-4 mt-2 border-t border-[#EDF1F8] pt-5">
                                                    <p class="text-[10px] font-bold uppercase tracking-[1px] text-[#397CF6]">
                                                        {{ str_replace('_title', '', ucwords(str_replace('_', ' ', $key))) }} Card
                                                    </p>
                                                </div>
                                            @endif

                                            <div class="flex items-center justify-between gap-3">
                                                <label
                                                    for="{{ $sectionKey }}-{{ $key }}"
                                                    class="block text-[11px] font-bold text-[#516581]"
                                                >
                                                    {{ $field['label'] }}
                                                </label>

                                                @if(isset($field['max']))
                                                    <span class="text-[9px] font-semibold text-[#A0AEC1]">
                                                        <span x-text="(fields[@js($key)] || '').length"></span>
                                                        / {{ $field['max'] }}
                                                    </span>
                                                @endif
                                            </div>

                                            @if($field['type'] === 'textarea')
                                                <textarea
                                                    id="{{ $sectionKey }}-{{ $key }}"
                                                    name="contents[{{ $key }}]"
                                                    rows="{{ $field['rows'] ?? 3 }}"
                                                    maxlength="{{ $field['max'] ?? 10000 }}"
                                                    @if($field['required'] ?? false) required @endif
                                                    x-model="fields[@js($key)]"
                                                    class="mt-2 block w-full resize-y rounded-[11px] border border-[#DCE5F3] bg-[#FAFCFF] px-4 py-3 text-[12px] leading-6 text-[#102243] outline-none transition focus:border-[#397CF6] focus:ring-2 focus:ring-[#397CF6]/10"
                                                ></textarea>
                                            @else
                                                <input
                                                    id="{{ $sectionKey }}-{{ $key }}"
                                                    type="text"
                                                    name="contents[{{ $key }}]"
                                                    maxlength="{{ $field['max'] ?? 10000 }}"
                                                    @if($field['required'] ?? false) required @endif
                                                    x-model="fields[@js($key)]"
                                                    class="mt-2 block h-12 w-full rounded-[11px] border border-[#DCE5F3] bg-[#FAFCFF] px-4 text-[12px] text-[#102243] outline-none transition focus:border-[#397CF6] focus:ring-2 focus:ring-[#397CF6]/10"
                                                >
                                            @endif

                                            @if(!empty($field['hint']))
                                                <p class="mt-2 text-[10px] leading-5 text-[#91A0B6]">
                                                    {{ $field['hint'] }}
                                                </p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-[#EDF1F8] pt-5">
                                    <button
                                        type="button"
                                        x-on:click="resetChanges()"
                                        x-bind:disabled="!hasChanges"
                                        class="rounded-[10px] border border-[#DCE5F3] bg-white px-4 py-3 text-[11px] font-bold text-[#7184A5] transition hover:bg-[#F7FAFF] disabled:cursor-not-allowed disabled:opacity-40"
                                    >
                                        Reset Changes
                                    </button>

                                    <button
                                        type="submit"
                                        x-bind:disabled="!hasChanges"
                                        class="inline-flex items-center gap-2 rounded-[11px] bg-[#397CF6] px-5 py-3 text-[11px] font-bold text-white transition hover:bg-[#2869E3] disabled:cursor-not-allowed disabled:opacity-50"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path d="M5 4h12l3 3v13H4V4h1z"/>
                                            <path d="M8 4v6h8V4M8 20v-7h8v7"/>
                                        </svg>
                                        Save Draft
                                    </button>
                                </div>

                                <p
                                    x-show="hasChanges"
                                    x-cloak
                                    class="mt-3 text-right text-[10px] font-semibold text-[#C58B38]"
                                >
                                    You have unsaved changes.
                                </p>
                            </form>

                            {{-- Publication Controls --}}
                            <div class="border-t border-[#EDF1F8] bg-[#FAFCFF] px-6 py-5">
                                <h4 class="text-[12px] font-bold text-[#102243]">
                                    Publication Controls
                                </h4>

                                <p class="mt-1 text-[10px] leading-5 text-[#8192AB]">
                                    Publish or discard only this section's saved draft.
                                </p>

                                <div class="mt-4 flex flex-wrap items-center gap-3">
                                    <form
                                        method="POST"
                                        action="{{ route('admin.website-cms.publish') }}"
                                        x-on:submit="if (!confirmPublish()) $event.preventDefault()"
                                    >
                                        @csrf

                                        <input type="hidden" name="page" value="home">
                                        <input type="hidden" name="section" value="{{ $sectionKey }}">

                                        <button
                                            type="submit"
                                            x-bind:disabled="!hasDraft || hasChanges"
                                            class="inline-flex items-center gap-2 rounded-[11px] bg-[#20A675] px-5 py-3 text-[11px] font-bold text-white transition hover:bg-[#168D62] disabled:cursor-not-allowed disabled:opacity-40"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <path d="M12 3v12"/>
                                                <path d="m7 10 5 5 5-5"/>
                                                <path d="M5 17v3h14v-3"/>
                                            </svg>
                                            Publish Draft
                                        </button>
                                    </form>

                                    <form
                                        method="POST"
                                        action="{{ route('admin.website-cms.discard') }}"
                                        x-on:submit="if (!confirmDiscard()) $event.preventDefault()"
                                    >
                                        @csrf

                                        <input type="hidden" name="page" value="home">
                                        <input type="hidden" name="section" value="{{ $sectionKey }}">

                                        <button
                                            type="submit"
                                            x-bind:disabled="!hasDraft"
                                            class="inline-flex items-center gap-2 rounded-[11px] border border-[#F0D0D5] bg-white px-5 py-3 text-[11px] font-bold text-[#C45B68] transition hover:bg-[#FFF4F5] disabled:cursor-not-allowed disabled:opacity-40"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <path d="M3 6h18M8 6V4h8v2M6 6l1 15h10l1-15"/>
                                                <path d="M10 10v7M14 10v7"/>
                                            </svg>
                                            Discard Draft
                                        </button>
                                    </form>
                                </div>

                                <p class="mt-4 text-[10px] leading-5 text-[#91A0B6]">
                                    Publish uses the last saved draft. Unsaved edits must be saved first.
                                </p>
                            </div>
                        </section>

                        {{-- Live Preview --}}
                        <section class="overflow-hidden rounded-[19px] border border-[#DFE8F6] bg-white shadow-[0_2px_6px_rgba(30,64,120,0.06)]">
                            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#EDF1F8] px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-11 w-11 items-center justify-center rounded-[13px] bg-[#E9F9F3] text-[#20A675]">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7-10-7-10-7z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </div>

                                    <div>
                                        <h3 class="text-[13px] font-bold text-[#102243]">
                                            {{ $isProgress ? 'Navigation Preview' : 'Live Preview' }}
                                        </h3>

                                        <p class="mt-1 text-[10px] text-[#91A0B6]">
                                            Compare your edits with the published version
                                        </p>
                                    </div>
                                </div>

                                <span
                                    x-show="hasChanges && previewMode === 'draft'"
                                    x-cloak
                                    class="rounded-full bg-[#FFF4E5] px-2.5 py-1 text-[9px] font-bold text-[#C58B38]"
                                >
                                    Unsaved Edits
                                </span>
                            </div>

                            <div class="p-6">
                                {{-- Preview Mode --}}
                                <div class="mb-5 flex rounded-[11px] border border-[#DCE6F4] bg-[#F5F8FE] p-1">
                                    <button
                                        type="button"
                                        x-on:click="previewMode = 'draft'"
                                        x-bind:class="previewMode === 'draft'
                                            ? 'bg-white text-[#397CF6] shadow-sm'
                                            : 'text-[#8192AB]'"
                                        class="flex-1 rounded-[8px] px-4 py-2.5 text-[11px] font-bold transition"
                                    >
                                        Draft Preview
                                    </button>

                                    <button
                                        type="button"
                                        x-on:click="previewMode = 'published'"
                                        x-bind:class="previewMode === 'published'
                                            ? 'bg-white text-[#20A675] shadow-sm'
                                            : 'text-[#8192AB]'"
                                        class="flex-1 rounded-[8px] px-4 py-2.5 text-[11px] font-bold transition"
                                    >
                                        Published Version
                                    </button>
                                </div>

                                {{-- Browser Frame --}}
                                <div class="overflow-hidden rounded-[15px] border border-[#E2EAF5] bg-white">
                                    <div class="flex items-center gap-2 border-b border-[#EEF2F7] px-5 py-4">
                                        <span class="h-2.5 w-2.5 rounded-full bg-[#FFB9B9]"></span>
                                        <span class="h-2.5 w-2.5 rounded-full bg-[#FFE2A8]"></span>
                                        <span class="h-2.5 w-2.5 rounded-full bg-[#BDECCF]"></span>

                                        <span class="ml-auto text-[9px] text-[#A2AEC0]">
                                            impala / {{ str_replace('_', ' ', $sectionKey) }}
                                        </span>
                                    </div>

                                    {{-- Hero Preview --}}
                                    @if($isHero)
                                        <div class="p-6">
                                            <h4
                                                x-text="previewFields.title"
                                                class="whitespace-pre-line break-words text-[28px] font-bold leading-[1.05] tracking-[-1px] text-[#111111]"
                                            ></h4>

                                            <p
                                                x-text="previewFields.subtitle"
                                                class="mt-5 whitespace-pre-line break-words text-[11px] leading-[1.8] text-[#48546A]"
                                            ></p>

                                            <div class="mt-6 inline-flex min-h-10 items-center gap-2 rounded-full border border-[#CBD5E1] bg-white px-5 text-[11px] text-[#172235]">
                                                <span x-text="previewFields.button_text"></span>
                                                <span>›</span>
                                            </div>

                                            <div class="mt-7 flex justify-center rounded-[12px] bg-[#F5F9FF] p-4">
                                                <img
                                                    src="{{ asset('images/slika1.png') }}"
                                                    alt="Hero image preview"
                                                    class="max-h-[180px] w-auto object-contain"
                                                >
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Study Progress Preview --}}
                                    @if($isProgress)
                                        <div class="border-b border-[#EAEAEA] bg-white px-5 py-6">
                                            <div class="relative">
                                                <div class="absolute bottom-0 left-0 h-px w-full bg-[#D4D4D4]"></div>

                                                <div
                                                    class="absolute bottom-0 left-0 h-px bg-[#242424] transition-all duration-300"
                                                    x-bind:style="'width: ' + ((activePreviewStep + 1) * 25) + '%'"
                                                ></div>

                                                <div class="relative grid grid-cols-4">
                                                    <template
                                                        x-for="(key, index) in ['goal_label', 'method_label', 'audience_label', 'review_label']"
                                                        :key="key"
                                                    >
                                                        <button
                                                            type="button"
                                                            x-on:click="activePreviewStep = index"
                                                            x-bind:class="activePreviewStep === index
                                                                ? 'font-bold text-[#242424]'
                                                                : 'font-normal text-[#A7A7A7]'"
                                                            x-bind:title="previewFields[key]"
                                                            x-text="previewFields[key]"
                                                            class="min-w-0 truncate px-1 pb-3 text-center text-[10px] transition-colors"
                                                        ></button>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bg-gradient-to-b from-[#F8FBFF] to-white px-6 py-8">
                                            <div class="mb-5 flex items-center justify-between gap-3">
                                                <span class="rounded-full bg-[#EAF2FF] px-3 py-1.5 text-[9px] font-bold text-[#397CF6]">
                                                    Interactive Preview
                                                </span>

                                                <span class="text-[10px] font-semibold text-[#91A0B6]">
                                                    <span x-text="activePreviewStep + 1"></span> / 4
                                                </span>
                                            </div>

                                            <h4
                                                x-text="previewFields[['goal_label', 'method_label', 'audience_label', 'review_label'][activePreviewStep]]"
                                                class="break-words text-[21px] font-bold tracking-[-0.5px] text-[#102243]"
                                            ></h4>

                                            <p class="mt-3 text-[11px] leading-[1.9] text-[#7184A5]">
                                                Select a navigation step above to preview its label.
                                            </p>

                                            <div class="mt-6 rounded-[12px] border border-[#E2EAF5] bg-white p-4">
                                                <p class="text-[11px] font-bold text-[#102243]">
                                                    Navigation behavior preserved
                                                </p>

                                                <p class="mt-1 text-[10px] leading-5 text-[#8192AB]">
                                                    CMS changes affect labels only, not scroll targets or animations.
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Study Builder Preview --}}
                                    @if($isBuilder)
                                        <div class="bg-white px-5 py-9 text-center sm:px-7">
                                            <h4
                                                x-text="previewFields.heading"
                                                class="break-words text-[23px] font-medium leading-tight tracking-[-0.5px] text-[#242424]"
                                            ></h4>

                                            <p
                                                x-text="previewFields.description"
                                                class="mx-auto mt-4 max-w-[440px] whitespace-pre-line break-words text-[11px] leading-[1.6] text-[#686868]"
                                            ></p>

                                            <div class="mt-9 grid grid-cols-2 gap-3 text-left sm:grid-cols-3">
                                                @foreach([
                                                    ['key' => 'product', 'goal' => 'Product Research'],
                                                    ['key' => 'pricing', 'goal' => 'Pricing Research'],
                                                    ['key' => 'concept', 'goal' => 'Concept Research'],
                                                    ['key' => 'brand', 'goal' => 'Brand Research'],
                                                    ['key' => 'ux', 'goal' => 'UX Research'],
                                                    ['key' => 'not_sure', 'goal' => 'Not sure'],
                                                ] as $card)
                                                    <button
                                                        type="button"
                                                        x-on:click="selectedGoal = @js($card['goal'])"
                                                        x-bind:aria-pressed="selectedGoal === @js($card['goal'])"
                                                        x-bind:class="selectedGoal === @js($card['goal'])
                                                            ? 'border-[#4D7FCF] bg-[#F7F9FD] ring-1 ring-[#4D7FCF]'
                                                            : 'border-transparent bg-[#F5F5F5]'"
                                                        class="min-h-[120px] rounded-[5px] border px-3 py-4 text-left transition hover:border-[#4D7FCF]"
                                                    >
                                                        <h5
                                                            x-text="previewFields[@js($card['key'] . '_title')]"
                                                            class="break-words text-[12px] font-medium text-[#242424]"
                                                        ></h5>

                                                        <p
                                                            x-text="previewFields[@js($card['key'] . '_description')]"
                                                            class="mt-4 break-words text-[10px] leading-[1.5] text-[#5F5F5F]"
                                                        ></p>
                                                    </button>
                                                @endforeach
                                            </div>

                                            <button
                                                type="button"
                                                x-on:click="
                                                    if (!selectedGoal) {
                                                        alert('Please select a research goal in the preview.');
                                                        return;
                                                    }

                                                    alert('Preview selection: ' + selectedGoal + '. Navigation is disabled in CMS preview.');
                                                "
                                                class="mt-9 inline-flex h-[42px] min-w-[170px] items-center justify-center rounded-[3px] bg-[#4D7FCF] px-5 text-[12px] font-medium text-white transition hover:bg-[#416FB4]"
                                            >
                                                <span x-text="previewFields.next_button_text"></span>
                                            </button>

                                            <div class="mt-5 flex flex-wrap items-center justify-center gap-2">
                                                <span class="rounded-full bg-[#EAF2FF] px-3 py-1.5 text-[9px] font-bold text-[#397CF6]">
                                                    Interactive Goal Selection
                                                </span>

                                                <span
                                                    x-show="selectedGoal !== null"
                                                    x-cloak
                                                    class="rounded-full bg-[#EEFBF5] px-3 py-1.5 text-[9px] font-bold text-[#247D59]"
                                                >
                                                    Selected:
                                                    <span x-text="selectedGoal"></span>
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Preview Information --}}
                                <div class="mt-5 rounded-[12px] border border-[#DCE8FA] bg-[#F4F8FF] p-4">
                                    <p class="text-[11px] font-bold text-[#397CF6]">
                                        Preview Information
                                    </p>

                                    <p
                                        x-show="previewMode === 'draft'"
                                        class="mt-1 text-[10px] leading-[1.8] text-[#7184A5]"
                                    >
                                        This preview updates as you type, including unsaved edits.
                                        Save and publish the draft to update the public website.
                                    </p>

                                    <p
                                        x-show="previewMode === 'published'"
                                        x-cloak
                                        class="mt-1 text-[10px] leading-[1.8] text-[#7184A5]"
                                    >
                                        This preview shows the currently published content visible on your public website.
                                    </p>
                                </div>

                                {{-- Preview Status --}}
                                <div class="mt-4 flex flex-wrap items-center gap-2">
                                    <span
                                        x-show="previewMode === 'published'"
                                        x-cloak
                                        class="rounded-full bg-[#EAF7EF] px-3 py-1.5 text-[10px] font-bold text-[#23976B]"
                                    >
                                        Live Website Content
                                    </span>

                                    <span
                                        x-show="previewMode === 'draft' && hasDraft"
                                        x-cloak
                                        class="rounded-full bg-[#FFF2D9] px-3 py-1.5 text-[10px] font-bold text-[#BD852C]"
                                    >
                                        Draft Preview
                                    </span>

                                    <span
                                        x-show="previewMode === 'draft' && !hasDraft"
                                        x-cloak
                                        class="rounded-full bg-[#EAF2FF] px-3 py-1.5 text-[10px] font-bold text-[#397CF6]"
                                    >
                                        Editor Preview
                                    </span>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            @endforeach

            {{-- Footer --}}
            <div class="mt-8 flex flex-wrap items-center justify-between gap-3 border-t border-[#DCE7F5] pt-5">
                <p class="text-[10px] text-[#91A0B6]">
                    Impala Research · Website CMS
                </p>

                <p class="text-[10px] text-[#91A0B6]">
                    Draft → Preview → Publish
                </p>
            </div>

        </div>
    </main>
</div>
@endsection
    