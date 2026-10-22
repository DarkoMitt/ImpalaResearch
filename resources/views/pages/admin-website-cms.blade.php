
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
    | Method Section
    |--------------------------------------------------------------------------
    */
    $methodContents = $homeContents->where('section', 'method_section')->keyBy('key');

    $methodFields = [
        'heading_prefix' => [
            'label' => 'Section Heading Prefix',
            'default' => 'Recommended methods for',
            'type' => 'text',
            'max' => 120,
            'required' => true,
            'hint' => 'Displayed before the dynamically selected research goal.',
        ],
        'description' => [
            'label' => 'Section Description',
            'default' => 'Based on your selection, these methods will give you the most relevant insights.',
            'type' => 'textarea',
            'rows' => 3,
            'max' => 500,
            'required' => true,
            'hint' => 'Introductory description below the heading.',
        ],
        'eye_tracking_title' => [
            'label' => 'Eye Tracking Display Title',
            'default' => 'Eye Tracking',
            'type' => 'text',
            'max' => 60,
            'required' => true,
            'hint' => 'Visible title only. Internal identifier remains Eye Tracking.',
        ],
        'eye_tracking_description' => [
            'label' => 'Eye Tracking Description',
            'default' => 'Understand where people look, what captures attention, and how visual elements are viewed.',
            'type' => 'textarea',
            'rows' => 2,
            'max' => 300,
            'required' => true,
            'hint' => 'Description of the Eye Tracking method.',
        ],
        'clicks_title' => [
            'label' => 'Clicks Display Title',
            'default' => 'Clicks',
            'type' => 'text',
            'max' => 60,
            'required' => true,
            'hint' => 'Visible title only. Internal identifier remains Clicks.',
        ],
        'clicks_description' => [
            'label' => 'Clicks Description',
            'default' => 'Tracks user interactions to understand engagement, navigation behavior, and decision-making.',
            'type' => 'textarea',
            'rows' => 2,
            'max' => 300,
            'required' => true,
            'hint' => 'Description of the Clicks method.',
        ],
        'emotion_title' => [
            'label' => 'Emotion Measurement Display Title',
            'default' => 'Emotion Measurement',
            'type' => 'text',
            'max' => 60,
            'required' => true,
            'hint' => 'Visible title only. Internal identifier remains Emotion Measurement.',
        ],
        'emotion_description' => [
            'label' => 'Emotion Measurement Description',
            'default' => 'Analyzes emotional reactions to brands, ads, products, or experiences.',
            'type' => 'textarea',
            'rows' => 2,
            'max' => 300,
            'required' => true,
            'hint' => 'Description of the Emotion Measurement method.',
        ],
        'surveys_title' => [
            'label' => 'Surveys Display Title',
            'default' => 'Surveys',
            'type' => 'text',
            'max' => 60,
            'required' => true,
            'hint' => 'Visible title only. Internal identifier remains Surveys.',
        ],
        'surveys_description' => [
            'label' => 'Surveys Description',
            'default' => 'Collects direct consumer feedback, opinions, attitudes, and purchase intentions.',
            'type' => 'textarea',
            'rows' => 2,
            'max' => 300,
            'required' => true,
            'hint' => 'Description of the Surveys method.',
        ],
        'rtm_title' => [
            'label' => 'RTM Display Title',
            'default' => 'RTM (Reaction Time Measurement)',
            'type' => 'text',
            'max' => 60,
            'required' => true,
            'hint' => 'Visible title only. Internal identifier remains unchanged.',
        ],
        'rtm_description' => [
            'label' => 'RTM Description',
            'default' => 'Measures reaction speed to understand automatic responses, associations, and decision-making.',
            'type' => 'textarea',
            'rows' => 2,
            'max' => 300,
            'required' => true,
            'hint' => 'Description of the RTM method.',
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
    | Study Details / Audience
    |--------------------------------------------------------------------------
    */
    $detailsContents = $homeContents->where('section', 'study_details')->keyBy('key');

    $detailsFields = [
        'heading' => [
            'label' => 'Section Heading',
            'default' => 'Tell us about your study',
            'type' => 'text',
            'max' => 120,
            'required' => true,
            'hint' => 'Main heading above the study details form.',
        ],
        'description' => [
            'label' => 'Section Description',
            'default' => 'These details help us tailor the study for you.',
            'type' => 'textarea',
            'rows' => 3,
            'max' => 500,
            'required' => true,
            'hint' => 'Supporting description below the heading.',
        ],
        'country_label' => [
            'label' => 'Country Label',
            'default' => 'Target Market / Country',
            'type' => 'text',
            'max' => 80,
            'required' => true,
            'hint' => 'Label displayed above the country dropdown.',
        ],
        'country_placeholder' => [
            'label' => 'Country Placeholder',
            'default' => 'Select Country',
            'type' => 'text',
            'max' => 80,
            'required' => true,
            'hint' => 'Default option shown before selecting a country.',
        ],
        'audience_label' => [
            'label' => 'Audience Label',
            'default' => 'Target Audience',
            'type' => 'text',
            'max' => 80,
            'required' => true,
            'hint' => 'Label displayed above the target audience input.',
        ],
        'audience_placeholder' => [
            'label' => 'Audience Placeholder',
            'default' => 'e.g US Adults 18–65',
            'type' => 'text',
            'max' => 150,
            'required' => true,
            'hint' => 'Example text inside the audience input.',
        ],
        'sample_size_label' => [
            'label' => 'Sample Size Label',
            'default' => 'Sample Size',
            'type' => 'text',
            'max' => 80,
            'required' => true,
            'hint' => 'Only the label is editable. Sample size remains limited to 1–1000.',
        ],
        'timeline_label' => [
            'label' => 'Timeline Label',
            'default' => 'Timeline (optional)',
            'type' => 'text',
            'max' => 80,
            'required' => true,
            'hint' => 'Label displayed above the timeline dropdown.',
        ],
        'timeline_placeholder' => [
            'label' => 'Timeline Placeholder',
            'default' => 'Select timeline',
            'type' => 'text',
            'max' => 80,
            'required' => true,
            'hint' => 'Default option shown before selecting a timeline.',
        ],
        'notes_label' => [
            'label' => 'Notes Label',
            'default' => 'Anything else we should know? (optional)',
            'type' => 'text',
            'max' => 150,
            'required' => true,
            'hint' => 'Label displayed above the additional notes field.',
        ],
        'notes_placeholder' => [
            'label' => 'Notes Placeholder',
            'default' => 'Add any specific requirements or context about your study...',
            'type' => 'textarea',
            'rows' => 2,
            'max' => 300,
            'required' => true,
            'hint' => 'Placeholder text displayed inside the notes field.',
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
    | Submit Request — Section 06
    |--------------------------------------------------------------------------
    */
    $submitContents = $homeContents
        ->where('section', 'submit_request')
        ->keyBy('key');

    $submitFields = [
        'heading' => [
            'label' => 'Section Heading',
            'default' => 'Almost there! Let’s get your details',
            'type' => 'text',
            'max' => 120,
            'required' => true,
        ],
        'description' => [
            'label' => 'Section Description',
            'default' => 'We’ll use this information to send you a tailored proposal.',
            'type' => 'textarea',
            'rows' => 3,
            'max' => 500,
            'required' => true,
        ],
        'full_name_label' => [
            'label' => 'Full Name Label',
            'default' => 'Full Name',
            'type' => 'text',
            'max' => 80,
            'required' => true,
        ],
        'full_name_placeholder' => [
            'label' => 'Full Name Placeholder',
            'default' => 'Enter your name',
            'type' => 'text',
            'max' => 120,
            'required' => true,
        ],
        'email_label' => [
            'label' => 'Email Label',
            'default' => 'Email',
            'type' => 'text',
            'max' => 80,
            'required' => true,
        ],
        'email_placeholder' => [
            'label' => 'Email Placeholder',
            'default' => 'Enter your email',
            'type' => 'text',
            'max' => 120,
            'required' => true,
        ],
        'phone_label' => [
            'label' => 'Phone Number Label',
            'default' => 'Phone Number',
            'type' => 'text',
            'max' => 80,
            'required' => true,
        ],
        'phone_placeholder' => [
            'label' => 'Phone Placeholder',
            'default' => 'Enter your phone number',
            'type' => 'text',
            'max' => 120,
            'required' => true,
        ],
        'company_label' => [
            'label' => 'Company Label',
            'default' => 'Company',
            'type' => 'text',
            'max' => 80,
            'required' => true,
        ],
        'company_placeholder' => [
            'label' => 'Company Placeholder',
            'default' => 'Enter company name',
            'type' => 'text',
            'max' => 120,
            'required' => true,
        ],
        'optional_label' => [
            'label' => 'Optional Field Label',
            'default' => '(optional)',
            'type' => 'text',
            'max' => 40,
            'required' => true,
        ],
        'notes_heading' => [
            'label' => 'Additional Notes Heading',
            'default' => 'Additional study notes',
            'type' => 'text',
            'max' => 100,
            'required' => true,
        ],
        'notes_empty_text' => [
            'label' => 'Empty Notes Message',
            'default' => 'No additional notes provided.',
            'type' => 'text',
            'max' => 150,
            'required' => true,
        ],
        'edit_notes_text' => [
            'label' => 'Edit Notes Button',
            'default' => 'Edit notes',
            'type' => 'text',
            'max' => 60,
            'required' => true,
        ],
        'submit_button_text' => [
            'label' => 'Submit Button Text',
            'default' => 'Submit Request',
            'type' => 'text',
            'max' => 60,
            'required' => true,
        ],
        'submitting_text' => [
            'label' => 'Submitting Text',
            'default' => 'Submitting...',
            'type' => 'text',
            'max' => 60,
            'required' => true,
        ],
        'guest_notice' => [
            'label' => 'Guest Notice',
            'default' => 'You’ll be asked to log in or register. Your study details will be saved temporarily.',
            'type' => 'textarea',
            'rows' => 3,
            'max' => 300,
            'required' => true,
        ],
        'summary_heading' => [
            'label' => 'Study Summary Heading',
            'default' => 'Study Summary',
            'type' => 'text',
            'max' => 100,
            'required' => true,
        ],
        'summary_edit_text' => [
            'label' => 'Summary Edit Button',
            'default' => 'Edit',
            'type' => 'text',
            'max' => 60,
            'required' => true,
        ],
        'summary_goal_label' => [
            'label' => 'Summary Goal Label',
            'default' => 'Goal',
            'type' => 'text',
            'max' => 80,
            'required' => true,
        ],
        'summary_method_label' => [
            'label' => 'Summary Method Label',
            'default' => 'Method',
            'type' => 'text',
            'max' => 80,
            'required' => true,
        ],
        'summary_country_label' => [
            'label' => 'Summary Country Label',
            'default' => 'Target Market',
            'type' => 'text',
            'max' => 80,
            'required' => true,
        ],
        'summary_audience_label' => [
            'label' => 'Summary Audience Label',
            'default' => 'Audience',
            'type' => 'text',
            'max' => 80,
            'required' => true,
        ],
        'summary_sample_size_label' => [
            'label' => 'Summary Sample Size Label',
            'default' => 'Sample Size',
            'type' => 'text',
            'max' => 80,
            'required' => true,
        ],
        'summary_timeline_label' => [
            'label' => 'Summary Timeline Label',
            'default' => 'Timeline',
            'type' => 'text',
            'max' => 80,
            'required' => true,
        ],
        'not_selected_text' => [
            'label' => 'Not Selected Message',
            'default' => 'Not selected',
            'type' => 'text',
            'max' => 100,
            'required' => true,
        ],
        'not_provided_text' => [
            'label' => 'Not Provided Message',
            'default' => 'Not provided',
            'type' => 'text',
            'max' => 100,
            'required' => true,
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
        'method_section' => [
            'name' => 'Method Section',
            'subtitle' => 'Research method heading, five cards and Next button',
            'fields' => $methodFields,
            'records' => $methodContents,
        ],
        'study_details' => [
            'name' => 'Study Details / Audience',
            'subtitle' => 'Country, audience, sample size, timeline and notes',
            'fields' => $detailsFields,
            'records' => $detailsContents,
        ],    
        'submit_request' => [
            'name' => 'Submit Request',
            'subtitle' => 'Contact form, additional notes and study summary',
            'fields' => $submitFields,
            'records' => $submitContents,
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

            $savedEditorValue = $record?->has_draft
                ? ($record->draft_value ?? '')
                : $publishedValue;

            $editor[$key] = old('section') === $sectionKey
                ? old("contents.{$key}", $savedEditorValue)
                : $savedEditorValue;
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
        [
            'key' => 'method_section',
            'number' => '04',
            'name' => 'Method Section',
            'subtitle' => 'Customize five research methods and their descriptions',
            'fields' => $methodFields,
            'color' => 'blue',
        ],
        [
            'key' => 'study_details',
            'number' => '05',
            'name' => 'Study Details / Audience',
            'subtitle' => 'Customize form labels, descriptions and placeholders',
            'fields' => $detailsFields,
            'color' => 'blue',
        ],      
        [
            'key' => 'submit_request',
            'number' => '06',
            'name' => 'Submit Request',
            'subtitle' => 'Customize contact fields, buttons and study summary',
            'fields' => $submitFields,
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
                        'label' => 'Saved Draft Sections',
                        'value' => $draftContents,
                        'caption' => 'Sections awaiting publication',
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
                    {{ count($cmsSections) }} Sections Available
                </span>
            </div>

            
            {{-- Quick Navigation --}}
            <div class="mt-6 rounded-[17px] border border-[#D8E6FA] bg-white p-5">
                <div class="mb-4 flex items-center justify-between gap-3">
                    <h3 class="text-[12px] font-bold text-[#102243]">
                        Quick Navigation
                    </h3>
                    <span class="text-[10px] text-[#8192AB]">
                        Jump to section
                    </span>
                </div>

                <div class="flex flex-wrap gap-2">
                    @foreach($cmsSections as $navSection)
                        <a
                            href="#cms-{{ str_replace('_', '-', $navSection['key']) }}"
                            class="rounded-full border border-[#D9E6FA] bg-[#F7FAFF] px-4 py-2 text-[10px] font-bold text-[#526887] transition hover:border-[#397CF6] hover:bg-[#EAF2FF] hover:text-[#397CF6]"
                        >
                            {{ $navSection['number'] }} · {{ $navSection['name'] }}
                        </a>
                    @endforeach
                </div>
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
                    $isMethod = $sectionKey === 'method_section';
                    $isDetails = $sectionKey === 'study_details';
                    $isSubmit = $sectionKey === 'submit_request';

                @endphp

                <div
                    id="cms-{{ str_replace('_', '-', $sectionKey) }}"
                    data-cms-editor
                    class="mt-8 scroll-mt-8"
                    x-data="{
                        fields: @js($sectionState['editor']),
                        savedFields: @js($sectionState['editor']),
                        publishedFields: @js($sectionState['published']),
                        fieldKeys: @js(array_keys($sectionFields)),
                        hasDraft: @js($sectionState['hasDraft']),
                        previewMode: 'draft',
                        isExpanded: true,
                        activePreviewStep: 0,
                        selectedGoal: null,
                        selectedMethods: [],
                        previewCountry: '',
                        previewAudience: '',
                        previewSampleSize: 400,
                        previewTimeline: '',
                        previewNotes: '',

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

                        toggleMethod(identifier) {
                            if (this.selectedMethods.includes(identifier)) {
                                this.selectedMethods = this.selectedMethods.filter(
                                    item => item !== identifier
                                );
                            } else {
                                this.selectedMethods.push(identifier);
                            }
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

                        
                        <div class="flex items-center gap-2">
                            <span class="rounded-full border border-[#D9E6FA] bg-white px-3 py-1.5 text-[10px] font-bold text-[#7184A5]">
                                Home Page · Section {{ $section['number'] }}
                            </span>

                            <button
                                type="button"
                                x-on:click="isExpanded = !isExpanded"
                                x-bind:aria-expanded="isExpanded"
                                class="inline-flex items-center gap-2 rounded-[10px] border border-[#D9E6FA] bg-white px-3 py-2 text-[10px] font-bold text-[#397CF6] transition hover:bg-[#EAF2FF]"
                            >
                                <span x-text="isExpanded ? 'Collapse' : 'Expand'"></span>
                                <svg
                                    class="h-3.5 w-3.5 transition-transform"
                                    x-bind:class="isExpanded ? 'rotate-180' : ''"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    viewBox="0 0 24 24"
                                >
                                    <path d="m6 9 6 6 6-6"/>
                                </svg>
                            </button>
                        </div>

                    </div>

                    <div
                        x-show="isExpanded"
                        x-cloak
                        class="grid items-start gap-6 xl:grid-cols-2"
                    >

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
                                    {{
                                        $isSubmit
                                            ? 'Submit Request Content'
                                            : ($isBuilder
                                                ? 'Research Goal Content'
                                                : ($isProgress
                                                    ? 'Progress Labels'
                                                    : ($isMethod
                                                        ? 'Research Method Content'
                                                        : ($isDetails
                                                            ? 'Study Details Content'
                                                            : 'Hero Content'))))
                                    }}
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
                                data-cms-save-form
                                x-on:submit="previewMode = 'draft'"
                            >
                                @csrf
                                @method('PATCH')

                                <input type="hidden" name="page" value="home">
                                <input type="hidden" name="section" value="{{ $sectionKey }}">

                                @if($isBuilder || $isMethod || $isDetails || $isSubmit)
                                    <div class="mb-6 rounded-[12px] border border-[#DCE8FA] bg-[#F4F8FF] px-4 py-3">
                                        <p class="text-[11px] font-bold text-[#397CF6]">
                                            Protected Research Logic
                                        </p>

                                        <p class="mt-1 text-[10px] leading-[1.8] text-[#7184A5]">
                                            @if($isMethod)
                                                You can edit visible method titles and descriptions only.
                                                Internal method identifiers, multi-selection,
                                                validation and navigation remain protected.
                                            @elseif($isDetails)
                                                You can edit visible form labels, descriptions and placeholders.
                                                Country values, timeline values, sample size limits,
                                                validation and navigation remain protected.
                                            @elseif($isSubmit)
                                                You can edit contact form labels, placeholders,
                                                button text and study summary descriptions.
                                                Form validation, research data, authentication
                                                and request submission remain protected.

                                            @else
                                                You can edit visible content only.
                                                Internal goal identifiers, card selection,
                                                validation and navigation are protected.
                                            @endif
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
                                            @if(($isBuilder || $isMethod) && str_ends_with($key, '_title') && $key !== 'heading')
                                                <div class="mb-4 mt-2 border-t border-[#EDF1F8] pt-5">
                                                    <p class="text-[10px] font-bold uppercase tracking-[1px] text-[#397CF6]">
                                                        {{ ucwords(str_replace('_', ' ', str_replace('_title', '', $key))) }}
                                                        {{ $isMethod ? 'Method' : 'Card' }}
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

                                                
                                            <div class="flex items-center gap-3">
                                                @if(isset($field['max']))
                                                    <span class="text-[9px] font-semibold text-[#A0AEC1]">
                                                        <span x-text="(fields[@js($key)] || '').length"></span>
                                                        / {{ $field['max'] }}
                                                    </span>
                                                @endif

                                                <button
                                                    type="button"
                                                    x-on:click="fields[@js($key)] = savedFields[@js($key)]"
                                                    x-bind:disabled="fields[@js($key)] === savedFields[@js($key)]"
                                                    class="text-[10px] font-semibold text-[#397CF6] transition hover:underline disabled:cursor-not-allowed disabled:opacity-30"
                                                    title="Restore last saved value"
                                                >
                                                    Reset
                                                </button>
                                            </div>

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
                                        data-cms-action-form
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
                                        data-cms-action-form
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

                                    
                                    {{-- Method Section Preview --}}
                                    @if($isMethod)
                                        <div class="bg-white px-5 py-9 text-center sm:px-7">

                                            <h4 class="break-words text-[23px] font-medium leading-tight tracking-[-0.5px] text-[#242424]">
                                                <span x-text="previewFields.heading_prefix"></span>
                                                <span class="text-[#4D7FCF]">your research</span>
                                            </h4>

                                            <p
                                                x-text="previewFields.description"
                                                class="mx-auto mt-4 max-w-[440px] whitespace-pre-line break-words text-[11px] leading-[1.6] text-[#686868]"
                                            ></p>

                                            <div class="mt-9 grid gap-3 text-left sm:grid-cols-2">

                                                @foreach([
                                                    [
                                                        'key' => 'eye_tracking',
                                                        'identifier' => 'Eye Tracking',
                                                    ],
                                                    [
                                                        'key' => 'clicks',
                                                        'identifier' => 'Clicks',
                                                    ],
                                                    [
                                                        'key' => 'emotion',
                                                        'identifier' => 'Emotion Measurement',
                                                    ],
                                                    [
                                                        'key' => 'surveys',
                                                        'identifier' => 'Surveys',
                                                    ],
                                                    [
                                                        'key' => 'rtm',
                                                        'identifier' => 'RTM (Reaction Time Measurement)',
                                                    ],
                                                ] as $index => $method)

                                                    <button
                                                        type="button"
                                                        x-on:click="toggleMethod(@js($method['identifier']))"
                                                        x-bind:aria-pressed="selectedMethods.includes(@js($method['identifier']))"
                                                        x-bind:class="selectedMethods.includes(@js($method['identifier']))
                                                            ? 'border-[#4D7FCF] bg-[#F7F9FD] ring-1 ring-[#4D7FCF]'
                                                            : 'border-transparent bg-[#F5F5F5]'"
                                                        class="min-h-[110px] rounded-[5px] border px-4 py-4 text-left transition hover:border-[#4D7FCF] {{ $index === 4 ? 'sm:col-span-2' : '' }}"
                                                    >
                                                        <div class="flex h-full items-start justify-between gap-3">

                                                            <div class="min-w-0 flex-1">
                                                                <h5
                                                                    x-text="previewFields[@js($method['key'] . '_title')]"
                                                                    class="break-words text-[12px] font-medium text-[#242424]"
                                                                ></h5>

                                                                <p
                                                                    x-text="previewFields[@js($method['key'] . '_description')]"
                                                                    class="mt-4 whitespace-pre-line break-words text-[10px] leading-[1.5] text-[#5F5F5F]"
                                                                ></p>
                                                            </div>

                                                            <span
                                                                class="mt-[1px] flex h-[18px] w-[18px] shrink-0 items-center justify-center rounded-[3px] border"
                                                                x-bind:class="selectedMethods.includes(@js($method['identifier']))
                                                                    ? 'border-[#4D7FCF] bg-[#4D7FCF]'
                                                                    : 'border-[#BFBFBF] bg-white'"
                                                            >
                                                                <svg
                                                                    x-show="selectedMethods.includes(@js($method['identifier']))"
                                                                    x-cloak
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    viewBox="0 0 20 20"
                                                                    fill="currentColor"
                                                                    class="h-[12px] w-[12px] text-white"
                                                                >
                                                                    <path
                                                                        fill-rule="evenodd"
                                                                        d="M16.704 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3.25-3.25a1 1 0 011.414-1.414l2.543 2.543 6.543-6.543a1 1 0 011.414 0z"
                                                                        clip-rule="evenodd"
                                                                    />
                                                                </svg>
                                                            </span>
                                                        </div>
                                                    </button>
                                                @endforeach
                                            </div>

                                            <button
                                                type="button"
                                                x-on:click="
                                                    if (selectedMethods.length === 0) {
                                                        alert('Please select at least one research method.');
                                                        return;
                                                    }

                                                    alert('Preview selection: ' + selectedMethods.join(', ') + '. Navigation is disabled in CMS preview.');
                                                "
                                                class="mt-8 inline-flex h-[42px] min-w-[170px] items-center justify-center rounded-[3px] bg-[#4D7FCF] px-5 text-[12px] font-medium text-white transition hover:bg-[#416FB4]"
                                            >
                                                <span x-text="previewFields.next_button_text"></span>
                                            </button>

                                            <div class="mt-5 flex flex-wrap items-center justify-center gap-2">
                                                <span class="rounded-full bg-[#EAF2FF] px-3 py-1.5 text-[9px] font-bold text-[#397CF6]">
                                                    Interactive Method Selection
                                                </span>

                                                <span
                                                    x-show="selectedMethods.length > 0"
                                                    x-cloak
                                                    class="rounded-full bg-[#EEFBF5] px-3 py-1.5 text-[9px] font-bold text-[#247D59]"
                                                >
                                                    Selected:
                                                    <span x-text="selectedMethods.length"></span> / 5
                                                </span>
                                            </div>

                                            <div class="mt-6 rounded-[12px] border border-[#DCE8FA] bg-[#F4F8FF] px-4 py-3 text-left">
                                                <p class="text-[10px] font-bold text-[#397CF6]">
                                                    Dynamic Research Goal
                                                </p>

                                                <p class="mt-1 text-[10px] leading-[1.8] text-[#7184A5]">
                                                    The public website automatically displays the selected research goal
                                                    after the heading prefix. The preview uses "your research" as an example.
                                                </p>
                                            </div>

                                        </div>
                                    @endif

                                    {{-- Study Details / Audience Preview --}}
                                    @if($isDetails)
                                        <div class="bg-white px-5 py-8 sm:px-7">

                                            <div class="text-center">
                                                <h4
                                                    x-text="previewFields.heading"
                                                    class="break-words text-[23px] font-medium leading-tight tracking-[-0.5px] text-[#242424]"
                                                ></h4>

                                                <p
                                                    x-text="previewFields.description"
                                                    class="mx-auto mt-4 max-w-[440px] whitespace-pre-line break-words text-[11px] leading-[1.7] text-[#686868]"
                                                ></p>
                                            </div>

                                            <div class="mt-8 space-y-5">

                                                {{-- Country --}}
                                                <div>
                                                    <label
                                                        for="cms-preview-country"
                                                        x-text="previewFields.country_label"
                                                        class="mb-2 block break-words text-[11px] font-medium text-[#242424]"
                                                    ></label>

                                                    <select
                                                        id="cms-preview-country"
                                                        x-model="previewCountry"
                                                        class="block h-11 w-full rounded-[5px] border border-[#D9D9D9] bg-white px-3 text-[11px] text-[#242424] outline-none transition focus:border-[#4D7FCF]"
                                                    >
                                                        <option value="" x-text="previewFields.country_placeholder"></option>
                                                        <option value="North Macedonia">North Macedonia</option>
                                                        <option value="United States">United States</option>
                                                        <option value="Germany">Germany</option>
                                                        <option value="United Kingdom">United Kingdom</option>
                                                    </select>
                                                </div>

                                                {{-- Target Audience --}}
                                                <div>
                                                    <label
                                                        for="cms-preview-audience"
                                                        x-text="previewFields.audience_label"
                                                        class="mb-2 block break-words text-[11px] font-medium text-[#242424]"
                                                    ></label>

                                                    <input
                                                        id="cms-preview-audience"
                                                        type="text"
                                                        x-model="previewAudience"
                                                        x-bind:placeholder="previewFields.audience_placeholder"
                                                        class="block h-11 w-full rounded-[5px] border border-[#D9D9D9] bg-white px-3 text-[11px] text-[#242424] outline-none transition placeholder:text-[#A3A3A3] focus:border-[#4D7FCF]"
                                                    >
                                                </div>

                                                {{-- Sample Size --}}
                                                <div>
                                                    <div class="mb-3 flex flex-wrap items-center justify-between gap-3">
                                                        <label
                                                            for="cms-preview-sample-range"
                                                            x-text="previewFields.sample_size_label"
                                                            class="block break-words text-[11px] font-medium text-[#242424]"
                                                        ></label>

                                                        <span class="rounded-[5px] bg-[#F2F6FD] px-3 py-1.5 text-[11px] font-semibold text-[#4D7FCF]">
                                                            <span x-text="previewSampleSize"></span>
                                                        </span>
                                                    </div>

                                                    <input
                                                        id="cms-preview-sample-range"
                                                        type="range"
                                                        min="1"
                                                        max="1000"
                                                        step="1"
                                                        x-model.number="previewSampleSize"
                                                        class="w-full cursor-pointer accent-[#4D7FCF]"
                                                    >

                                                    <div class="mt-3 flex items-center justify-between gap-3">
                                                        <span class="text-[10px] text-[#919191]">1</span>

                                                        <input
                                                            type="number"
                                                            min="1"
                                                            max="1000"
                                                            step="1"
                                                            x-model.number="previewSampleSize"
                                                            x-on:change="
                                                                previewSampleSize = Math.min(
                                                                    1000,
                                                                    Math.max(1, Math.round(Number(previewSampleSize) || 1))
                                                                )
                                                            "
                                                            class="h-10 w-[100px] rounded-[5px] border border-[#D9D9D9] bg-white px-3 text-center text-[11px] text-[#242424] outline-none focus:border-[#4D7FCF]"
                                                            aria-label="Preview sample size"
                                                        >

                                                        <span class="text-[10px] text-[#919191]">1000</span>
                                                    </div>
                                                </div>

                                                {{-- Timeline --}}
                                                <div>
                                                    <label
                                                        for="cms-preview-timeline"
                                                        x-text="previewFields.timeline_label"
                                                        class="mb-2 block break-words text-[11px] font-medium text-[#242424]"
                                                    ></label>

                                                    <select
                                                        id="cms-preview-timeline"
                                                        x-model="previewTimeline"
                                                        class="block h-11 w-full rounded-[5px] border border-[#D9D9D9] bg-white px-3 text-[11px] text-[#242424] outline-none transition focus:border-[#4D7FCF]"
                                                    >
                                                        <option value="" x-text="previewFields.timeline_placeholder"></option>
                                                        <option value="1-2 weeks">1–2 weeks</option>
                                                        <option value="2-4 weeks">2–4 weeks</option>
                                                        <option value="1-2 months">1–2 months</option>
                                                    </select>
                                                </div>

                                                {{-- Additional Notes --}}
                                                <div>
                                                    <label
                                                        for="cms-preview-notes"
                                                        x-text="previewFields.notes_label"
                                                        class="mb-2 block break-words text-[11px] font-medium text-[#242424]"
                                                    ></label>

                                                    <textarea
                                                        id="cms-preview-notes"
                                                        rows="4"
                                                        x-model="previewNotes"
                                                        x-bind:placeholder="previewFields.notes_placeholder"
                                                        class="block w-full resize-y rounded-[5px] border border-[#D9D9D9] bg-white px-3 py-3 text-[11px] leading-[1.7] text-[#242424] outline-none transition placeholder:text-[#A3A3A3] focus:border-[#4D7FCF]"
                                                    ></textarea>
                                                </div>

                                                {{-- Next Button --}}
                                                <div class="pt-2 text-center">
                                                    <button
                                                        type="button"
                                                        x-on:click="
                                                            if (!previewCountry || !previewAudience.trim()) {
                                                                alert('Please select a country and enter a target audience.');
                                                                return;
                                                            }

                                                            alert('Study Details preview validated. Navigation is disabled in CMS preview.');
                                                        "
                                                        class="inline-flex h-[42px] min-w-[170px] items-center justify-center rounded-[3px] bg-[#4D7FCF] px-5 text-[12px] font-medium text-white transition hover:bg-[#416FB4]"
                                                    >
                                                        <span x-text="previewFields.next_button_text"></span>
                                                    </button>
                                                </div>

                                                {{-- Preview Status --}}
                                                <div class="flex flex-wrap items-center justify-center gap-2 pt-2">
                                                    <span class="rounded-full bg-[#EAF2FF] px-3 py-1.5 text-[9px] font-bold text-[#397CF6]">
                                                        Interactive Form Preview
                                                    </span>

                                                    <span class="rounded-full bg-[#F4F7FB] px-3 py-1.5 text-[9px] font-bold text-[#7184A5]">
                                                        Sample Size: 1–1000
                                                    </span>
                                                </div>

                                            </div>

                                            {{-- Protected Form Logic --}}
                                            <div class="mt-7 rounded-[12px] border border-[#DCE8FA] bg-[#F4F8FF] px-4 py-3">
                                                <p class="text-[10px] font-bold text-[#397CF6]">
                                                    Protected Study Details Logic
                                                </p>

                                                <p class="mt-1 text-[10px] leading-[1.8] text-[#7184A5]">
                                                    This preview allows you to test the form layout and editable text.
                                                    Country options, timeline options, sample size restrictions,
                                                    required-field validation and navigation are managed by the
                                                    public website and cannot be changed through the CMS.
                                                </p>
                                            </div>

                                        </div>
                                    @endif

                                    
                                {{-- Submit Request Preview --}}
                                @if($isSubmit)
                                    <div class="bg-white px-5 py-8 sm:px-7">

                                        <div class="text-center">
                                            <h4
                                                x-text="previewFields.heading"
                                                class="break-words text-[23px] font-medium leading-tight tracking-[-0.5px] text-[#242424]"
                                            ></h4>

                                            <p
                                                x-text="previewFields.description"
                                                class="mx-auto mt-4 max-w-[440px] whitespace-pre-line break-words text-[11px] leading-[1.7] text-[#686868]"
                                            ></p>
                                        </div>

                                        <div class="mt-8 grid gap-6 lg:grid-cols-[1fr_155px]">

                                            {{-- Contact Fields --}}
                                            <div class="space-y-4">

                                                @foreach([
                                                    ['key' => 'full_name', 'type' => 'text'],
                                                    ['key' => 'email', 'type' => 'email'],
                                                    ['key' => 'phone', 'type' => 'tel'],
                                                    ['key' => 'company', 'type' => 'text'],
                                                ] as $contactField)

                                                    <div>
                                                        <label class="mb-2 block text-[11px] font-medium text-[#242424]">
                                                            <span
                                                                x-text="previewFields[@js($contactField['key'] . '_label')]"
                                                            ></span>

                                                            @if($contactField['key'] === 'company')
                                                                <span
                                                                    x-text="previewFields.optional_label"
                                                                    class="text-[#888888]"
                                                                ></span>
                                                            @else
                                                                <span class="text-red-500">*</span>
                                                            @endif
                                                        </label>

                                                        <input
                                                            type="{{ $contactField['type'] }}"
                                                            x-bind:placeholder="previewFields[@js($contactField['key'] . '_placeholder')]"
                                                            class="h-10 w-full rounded-[4px] border border-[#BFBFBF] bg-white px-3 text-[11px] text-[#242424] outline-none placeholder:text-[#999999] focus:border-[#4D7FCF]"
                                                        >
                                                    </div>

                                                @endforeach

                                                {{-- Additional Notes --}}
                                                <div class="rounded-[6px] bg-[#F7F9FD] p-4">
                                                    <p
                                                        x-text="previewFields.notes_heading"
                                                        class="text-[11px] text-[#666666]"
                                                    ></p>

                                                    <p
                                                        x-text="previewFields.notes_empty_text"
                                                        class="mt-2 break-words text-[11px] text-[#333333]"
                                                    ></p>

                                                    <button
                                                        type="button"
                                                        x-on:click="alert('Navigation is disabled in CMS preview.')"
                                                        x-text="previewFields.edit_notes_text"
                                                        class="mt-3 text-[11px] text-[#4D7FCF] hover:underline"
                                                    ></button>
                                                </div>

                                                {{-- Submit Button --}}
                                                <div class="pt-4 text-center">
                                                    <button
                                                        type="button"
                                                        x-on:click="alert('Submission is disabled in CMS preview.')"
                                                        x-text="previewFields.submit_button_text"
                                                        class="inline-flex h-[42px] min-w-[170px] items-center justify-center rounded-[3px] bg-[#4D7FCF] px-5 text-[12px] font-medium text-white transition hover:bg-[#416FB4]"
                                                    ></button>

                                                    <p class="mt-2 text-[10px] text-[#8192AB]">
                                                        Loading state:
                                                        <span
                                                            x-text="previewFields.submitting_text"
                                                            class="font-semibold"
                                                        ></span>
                                                    </p>

                                                    <p
                                                        x-text="previewFields.guest_notice"
                                                        class="mt-3 whitespace-pre-line break-words text-[10px] leading-[1.6] text-[#777777]"
                                                    ></p>
                                                </div>

                                            </div>

                                            {{-- Study Summary --}}
                                            <aside class="self-start rounded-[8px] border border-[#BFBFBF] bg-white p-3">

                                                <div class="flex items-start justify-between gap-2">
                                                    <h5
                                                        x-text="previewFields.summary_heading"
                                                        class="break-words text-[11px] font-medium text-[#242424]"
                                                    ></h5>

                                                    <button
                                                        type="button"
                                                        x-on:click="alert('Navigation is disabled in CMS preview.')"
                                                        x-text="previewFields.summary_edit_text"
                                                        class="shrink-0 text-[10px] underline"
                                                    ></button>
                                                </div>

                                                <div class="mt-5 space-y-4">

                                                    @foreach([
                                                        'summary_goal_label',
                                                        'summary_method_label',
                                                        'summary_country_label',
                                                        'summary_audience_label',
                                                        'summary_sample_size_label',
                                                        'summary_timeline_label',
                                                    ] as $summaryKey)

                                                        <div>
                                                            <p
                                                                x-text="previewFields[@js($summaryKey)]"
                                                                class="break-words text-[10px] text-[#555555]"
                                                            ></p>

                                                            <p
                                                                x-text="@js($summaryKey) === 'summary_audience_label'
                                                                    ? previewFields.not_provided_text
                                                                    : previewFields.not_selected_text"
                                                                class="mt-1 break-words text-[10px] text-[#333333]"
                                                            ></p>
                                                        </div>

                                                    @endforeach

                                                </div>
                                            </aside>
                                        </div>

                                        {{-- Preview Information --}}
                                        <div class="mt-7 rounded-[12px] border border-[#DCE8FA] bg-[#F4F8FF] p-4">
                                            <p class="text-[10px] font-bold text-[#397CF6]">
                                                Protected Submit Request Logic
                                            </p>

                                            <p class="mt-2 text-[10px] leading-[1.8] text-[#7184A5]">
                                                This preview displays editable contact form content
                                                and study summary labels. Actual form submission,
                                                field validation, authentication and research data
                                                are handled by the public website.
                                            </p>
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

{{-- Global Unsaved Changes Protection --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    let allowNavigation = false;

    function hasUnsavedCmsChanges() {
        return [...document.querySelectorAll('[data-cms-editor]')]
            .some(element => {
                const component = window.Alpine?.$data(element);
                return component?.hasChanges === true;
            });
    }

    window.addEventListener('beforeunload', function (event) {
        if (allowNavigation || !hasUnsavedCmsChanges()) {
            return;
        }

        event.preventDefault();
        event.returnValue = '';
    });

    document.querySelectorAll('[data-cms-action-form]').forEach(form => {
    form.addEventListener('submit', function (event) {
        if (!event.defaultPrevented) {
            allowNavigation = true;
        }
    });
});

    document.querySelectorAll('[data-cms-action-form]').forEach(form => {
    form.addEventListener('submit', function (event) {
        if (!event.defaultPrevented) {
            allowNavigation = true;
        }
    });
});
});
</script>
```

@endsection
