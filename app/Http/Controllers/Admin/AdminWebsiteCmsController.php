<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminWebsiteCmsController extends Controller
{
    /**
     * Registered CMS sections and editable fields.
     *
     * Internal application values, including research
     * goal and method identifiers, are excluded.
     */
    private const CMS_SECTIONS = [
        'home' => [
            'hero' => [
                'title' => 'text',
                'subtitle' => 'textarea',
                'button_text' => 'text',
            ],

            'study_progress' => [
                'goal_label' => 'text',
                'method_label' => 'text',
                'audience_label' => 'text',
                'review_label' => 'text',
            ],

            'study_builder' => [
                'heading' => 'text',
                'description' => 'textarea',

                'product_title' => 'text',
                'product_description' => 'textarea',

                'pricing_title' => 'text',
                'pricing_description' => 'textarea',

                'concept_title' => 'text',
                'concept_description' => 'textarea',

                'brand_title' => 'text',
                'brand_description' => 'textarea',

                'ux_title' => 'text',
                'ux_description' => 'textarea',

                'not_sure_title' => 'text',
                'not_sure_description' => 'textarea',

                'next_button_text' => 'text',
            ],

            'method_section' => [
                'heading_prefix' => 'text',
                'description' => 'textarea',

                'eye_tracking_title' => 'text',
                'eye_tracking_description' => 'textarea',

                'clicks_title' => 'text',
                'clicks_description' => 'textarea',

                'emotion_title' => 'text',
                'emotion_description' => 'textarea',

                'surveys_title' => 'text',
                'surveys_description' => 'textarea',

                'rtm_title' => 'text',
                'rtm_description' => 'textarea',

                'next_button_text' => 'text',
            ],

            'study_details' => [
                'heading' => 'text',
                'description' => 'textarea',

                'country_label' => 'text',
                'country_placeholder' => 'text',

                'audience_label' => 'text',
                'audience_placeholder' => 'text',

                'sample_size_label' => 'text',

                'timeline_label' => 'text',
                'timeline_placeholder' => 'text',

                'notes_label' => 'text',
                'notes_placeholder' => 'textarea',

                'next_button_text' => 'text',
            ],

            /*
            |--------------------------------------------------------------------------
            | Section 06 — Submit Request
            |--------------------------------------------------------------------------
            */
            'submit_request' => [
                'heading' => 'text',
                'description' => 'textarea',

                'full_name_label' => 'text',
                'full_name_placeholder' => 'text',

                'email_label' => 'text',
                'email_placeholder' => 'text',

                'phone_label' => 'text',
                'phone_placeholder' => 'text',

                'company_label' => 'text',
                'company_placeholder' => 'text',
                'optional_label' => 'text',

                'notes_heading' => 'text',
                'notes_empty_text' => 'text',
                'edit_notes_text' => 'text',

                'submit_button_text' => 'text',
                'submitting_text' => 'text',
                'guest_notice' => 'textarea',

                'summary_heading' => 'text',
                'summary_edit_text' => 'text',

                'summary_goal_label' => 'text',
                'summary_method_label' => 'text',
                'summary_country_label' => 'text',
                'summary_audience_label' => 'text',
                'summary_sample_size_label' => 'text',
                'summary_timeline_label' => 'text',

                'not_selected_text' => 'text',
                'not_provided_text' => 'text',
            ],
        ],
    ];

    /**
     * Fields required before publishing.
     */
    private const REQUIRED_PUBLISH_FIELDS = [
        'home' => [
            'hero' => [
                'title',
                'button_text',
            ],

            'study_progress' => [
                'goal_label',
                'method_label',
                'audience_label',
                'review_label',
            ],

            'study_builder' => [
                'heading',
                'description',

                'product_title',
                'product_description',

                'pricing_title',
                'pricing_description',

                'concept_title',
                'concept_description',

                'brand_title',
                'brand_description',

                'ux_title',
                'ux_description',

                'not_sure_title',
                'not_sure_description',

                'next_button_text',
            ],

            'method_section' => [
                'heading_prefix',
                'description',

                'eye_tracking_title',
                'eye_tracking_description',

                'clicks_title',
                'clicks_description',

                'emotion_title',
                'emotion_description',

                'surveys_title',
                'surveys_description',

                'rtm_title',
                'rtm_description',

                'next_button_text',
            ],

            'study_details' => [
                'heading',
                'description',

                'country_label',
                'country_placeholder',

                'audience_label',
                'audience_placeholder',

                'sample_size_label',

                'timeline_label',
                'timeline_placeholder',

                'notes_label',
                'notes_placeholder',

                'next_button_text',
            ],

            /*
            |--------------------------------------------------------------------------
            | Submit Request Required Fields
            |--------------------------------------------------------------------------
            */
            'submit_request' => [
                'heading',
                'description',

                'full_name_label',
                'full_name_placeholder',

                'email_label',
                'email_placeholder',

                'phone_label',
                'phone_placeholder',

                'company_label',
                'company_placeholder',
                'optional_label',

                'notes_heading',
                'notes_empty_text',
                'edit_notes_text',

                'submit_button_text',
                'submitting_text',
                'guest_notice',

                'summary_heading',
                'summary_edit_text',

                'summary_goal_label',
                'summary_method_label',
                'summary_country_label',
                'summary_audience_label',
                'summary_sample_size_label',
                'summary_timeline_label',

                'not_selected_text',
                'not_provided_text',
            ],
        ],
    ];

    /**
     * Field-specific character limits.
     *
     * Existing Hero and Study Progress limits
     * remain unchanged.
     */
    private const FIELD_MAX_LENGTHS = [
        'home' => [
            'study_builder' => [
                'heading' => 120,
                'description' => 500,

                'product_title' => 40,
                'product_description' => 300,

                'pricing_title' => 40,
                'pricing_description' => 300,

                'concept_title' => 40,
                'concept_description' => 300,

                'brand_title' => 40,
                'brand_description' => 300,

                'ux_title' => 40,
                'ux_description' => 300,

                'not_sure_title' => 40,
                'not_sure_description' => 300,

                'next_button_text' => 30,
            ],

            'method_section' => [
                'heading_prefix' => 120,
                'description' => 500,

                'eye_tracking_title' => 60,
                'eye_tracking_description' => 300,

                'clicks_title' => 60,
                'clicks_description' => 300,

                'emotion_title' => 60,
                'emotion_description' => 300,

                'surveys_title' => 60,
                'surveys_description' => 300,

                'rtm_title' => 60,
                'rtm_description' => 300,

                'next_button_text' => 30,
            ],

            'study_details' => [
                'heading' => 120,
                'description' => 500,

                'country_label' => 80,
                'country_placeholder' => 80,

                'audience_label' => 80,
                'audience_placeholder' => 150,

                'sample_size_label' => 80,

                'timeline_label' => 80,
                'timeline_placeholder' => 80,

                'notes_label' => 150,
                'notes_placeholder' => 300,

                'next_button_text' => 30,
            ],

            /*
            |--------------------------------------------------------------------------
            | Submit Request Character Limits
            |--------------------------------------------------------------------------
            */
            'submit_request' => [
                'heading' => 120,
                'description' => 500,

                'full_name_label' => 80,
                'full_name_placeholder' => 120,

                'email_label' => 80,
                'email_placeholder' => 120,

                'phone_label' => 80,
                'phone_placeholder' => 120,

                'company_label' => 80,
                'company_placeholder' => 120,
                'optional_label' => 40,

                'notes_heading' => 100,
                'notes_empty_text' => 150,
                'edit_notes_text' => 60,

                'submit_button_text' => 60,
                'submitting_text' => 60,
                'guest_notice' => 300,

                'summary_heading' => 100,
                'summary_edit_text' => 60,

                'summary_goal_label' => 80,
                'summary_method_label' => 80,
                'summary_country_label' => 80,
                'summary_audience_label' => 80,
                'summary_sample_size_label' => 80,
                'summary_timeline_label' => 80,

                'not_selected_text' => 100,
                'not_provided_text' => 100,
            ],
        ],
    ];

    /**
     * Display CMS dashboard.
     */

    
    public function index(): View
    {
        $contents = WebsiteContent::query()
            ->orderBy('page')
            ->orderBy('section')
            ->orderBy('key')
            ->get()
            ->groupBy('page');

        $cmsSections = self::CMS_SECTIONS;

        $totalContents = WebsiteContent::count();

        $publishedContents = WebsiteContent::query()
            ->published()
            ->count();

        $unpublishedContents = WebsiteContent::query()
            ->where('is_published', false)
            ->count();

        /*
         * Count unique CMS sections containing saved drafts.
         *
         * Multiple draft fields in one section count as one.
         * The page + section combination identifies a
         * unique section across the website.
         */
        $draftContents = $contents
            ->flatten(1)
            ->filter(fn ($content) => $content->has_draft)
            ->unique(fn ($content) => $content->page . ':' . $content->section)
            ->count();

        return view('pages.admin-website-cms', compact(
            'contents',
            'cmsSections',
            'totalContents',
            'publishedContents',
            'unpublishedContents',
            'draftContents'
        ));
    }

    /**
     * Save content as a private draft.
     *
     * Published website content is never changed here.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'page' => ['required', 'string', 'max:100'],
            'section' => ['required', 'string', 'max:100'],
            'contents' => ['required', 'array', 'min:1'],
        ]);

        $page = $validated['page'];
        $section = $validated['section'];

        $allowedFields = $this->getAllowedFields(
            $page,
            $section
        );

        $submittedContents = $request->input('contents');

        /*
         * Reject any unregistered field.
         */
        foreach (array_keys($submittedContents) as $key) {
            if (!array_key_exists($key, $allowedFields)) {
                throw ValidationException::withMessages([
                    'contents' => 'One or more CMS fields are invalid.',
                ]);
            }
        }

        /*
         * Apply server-side validation to every submitted
         * field, including section-specific length limits.
         */
        $rules = [];
        $attributes = [];

        foreach ($submittedContents as $key => $value) {
            $rules["contents.{$key}"] = [
                'nullable',
                'string',
                'max:' . $this->getFieldMaxLength(
                    $page,
                    $section,
                    $key
                ),
            ];

            $attributes["contents.{$key}"] =
                $this->getFieldLabel($page, $section, $key);
        }

        $validatedContents = Validator::make(
            ['contents' => $submittedContents],
            $rules,
            [
                'string' => 'The :attribute must contain valid text.',
                'max' => 'The :attribute may not exceed :max characters.',
            ],
            $attributes
        )->validate()['contents'];

        /*
         * Save only validated, registered fields.
         */
        DB::transaction(function () use (
            $validatedContents,
            $page,
            $section,
            $allowedFields
        ) {
            foreach ($validatedContents as $key => $value) {
                WebsiteContent::saveDraft(
                    $page,
                    $section,
                    $key,
                    $value,
                    $allowedFields[$key]
                );
            }
        });

        return redirect()
            ->route('admin.website-cms.index')
            ->with(
                'cms_success',
                'Draft saved successfully. Your public website has not been changed.'
            );
    }

    /**
     * Publish saved drafts for one registered section.
     *
     * The complete resulting section is validated
     * before any published content is changed.
     */
    public function publish(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'page' => ['required', 'string', 'max:100'],
            'section' => ['required', 'string', 'max:100'],
        ]);

        $page = $validated['page'];
        $section = $validated['section'];

        $allowedFields = $this->getAllowedFields(
            $page,
            $section
        );

        $publishedCount = DB::transaction(function () use (
            $page,
            $section,
            $allowedFields
        ) {
            /*
             * Lock registered section records.
             */
            $records = WebsiteContent::query()
                ->forPage($page)
                ->forSection($section)
                ->whereIn('key', array_keys($allowedFields))
                ->lockForUpdate()
                ->get()
                ->keyBy('key');

            $drafts = $records->filter(
                fn ($record) => $record->has_draft
            );

            if ($drafts->isEmpty()) {
                return 0;
            }

            /*
             * Build the final content as it would
             * appear after publishing.
             */
            $effectiveContent = [];

            foreach ($allowedFields as $key => $type) {
                $record = $records->get($key);

                if ($record && $record->has_draft) {
                    $effectiveContent[$key] = $record->draft_value;
                } elseif ($record && $record->is_published) {
                    $effectiveContent[$key] = $record->value;
                } else {
                    $effectiveContent[$key] = null;
                }
            }

            /*
             * Validate every registered field before
             * publishing any of the saved drafts.
             */
            $this->validatePublishContent(
                $page,
                $section,
                $effectiveContent,
                $allowedFields
            );

            $count = 0;

            foreach ($drafts as $content) {
                if ($content->publishDraft()) {
                    $count++;
                }
            }

            return $count;
        });

        if ($publishedCount === 0) {
            return redirect()
                ->route('admin.website-cms.index')
                ->with(
                    'cms_info',
                    'There are no saved drafts to publish in this section.'
                );
        }

        return redirect()
            ->route('admin.website-cms.index')
            ->with(
                'cms_success',
                "{$publishedCount} content item(s) published successfully. Your public website is now updated."
            );
    }

    /**
     * Discard saved drafts for one section.
     *
     * Published content remains unchanged.
     */
    public function discard(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'page' => ['required', 'string', 'max:100'],
            'section' => ['required', 'string', 'max:100'],
        ]);

        $page = $validated['page'];
        $section = $validated['section'];

        $allowedFields = $this->getAllowedFields(
            $page,
            $section
        );

        $discardedCount = DB::transaction(function () use (
            $page,
            $section,
            $allowedFields
        ) {
            $drafts = WebsiteContent::query()
                ->forPage($page)
                ->forSection($section)
                ->withDraft()
                ->whereIn('key', array_keys($allowedFields))
                ->lockForUpdate()
                ->get();

            $count = 0;

            foreach ($drafts as $content) {
                if ($content->discardDraft()) {
                    $count++;
                }
            }

            return $count;
        });

        if ($discardedCount === 0) {
            return redirect()
                ->route('admin.website-cms.index')
                ->with(
                    'cms_info',
                    'There are no saved drafts to discard in this section.'
                );
        }

        return redirect()
            ->route('admin.website-cms.index')
            ->with(
                'cms_success',
                "{$discardedCount} draft item(s) discarded. Your published website content remains unchanged."
            );
    }

    /**
     * Validate the complete section before publishing.
     *
     * Required fields cannot be empty or whitespace-only.
     * Optional fields may be empty.
     */
    private function validatePublishContent(
        string $page,
        string $section,
        array $content,
        array $allowedFields
    ): void {
        $requiredFields = self::REQUIRED_PUBLISH_FIELDS
            [$page][$section] ?? [];

        $rules = [];
        $attributes = [];
        $validationData = $content;

        foreach ($allowedFields as $key => $type) {
            $isRequired = in_array(
                $key,
                $requiredFields,
                true
            );

            $rules[$key] = [
                $isRequired ? 'required' : 'nullable',
                'string',
                'max:' . $this->getFieldMaxLength(
                    $page,
                    $section,
                    $key
                ),
            ];

            $attributes[$key] = $this->getFieldLabel(
                $page,
                $section,
                $key
            );
        }

        /*
         * Trim required values for validation only.
         *
         * The saved content itself is not modified,
         * preserving intentional line breaks.
         */
        foreach ($requiredFields as $key) {
            if (
                isset($validationData[$key]) &&
                is_string($validationData[$key])
            ) {
                $validationData[$key] = trim(
                    $validationData[$key]
                );
            }
        }

        Validator::make(
            $validationData,
            $rules,
            [
                'required' => 'The :attribute field is required before publishing.',
                'string' => 'The :attribute must contain valid text.',
                'max' => 'The :attribute may not exceed :max characters.',
            ],
            $attributes
        )->validate();
    }

    /**
     * Get field-specific length limit.
     *
     * Existing CMS fields retain the original
     * maximum of 10,000 characters.
     */

    private function getFieldMaxLength(
        string $page,
        string $section,
        string $key
    ): int {
        return self::FIELD_MAX_LENGTHS
            [$page][$section][$key] ?? 10000;
    }

    /**
     * Human-readable field names for validation.
     */
    private function getFieldLabel(
        string $page,
        string $section,
        string $key
    ): string {
        if ($page === 'home' && $section === 'hero') {
            return match ($key) {
                'title' => 'Hero Title',
                'subtitle' => 'Hero Description',
                'button_text' => 'CTA Button Text',

                default => str_replace('_', ' ', $key),
            };
        }

        if ($page === 'home' && $section === 'study_progress') {
            return match ($key) {
                'goal_label' => 'Goal Label',
                'method_label' => 'Method Label',
                'audience_label' => 'Audience Label',
                'review_label' => 'Review Label',

                default => str_replace('_', ' ', $key),
            };
        }

        if ($page === 'home' && $section === 'study_builder') {
            return match ($key) {
                'heading' => 'Study Builder Heading',
                'description' => 'Study Builder Description',

                'product_title' => 'Product Card Title',
                'product_description' => 'Product Card Description',

                'pricing_title' => 'Pricing Card Title',
                'pricing_description' => 'Pricing Card Description',

                'concept_title' => 'Concept Card Title',
                'concept_description' => 'Concept Card Description',

                'brand_title' => 'Brand Card Title',
                'brand_description' => 'Brand Card Description',

                'ux_title' => 'UX Card Title',
                'ux_description' => 'UX Card Description',

                'not_sure_title' => 'Not Sure Card Title',
                'not_sure_description' => 'Not Sure Card Description',

                'next_button_text' => 'Next Button Text',

                default => str_replace('_', ' ', $key),
            };
        }

        if ($page === 'home' && $section === 'method_section') {
            return match ($key) {
                'heading_prefix' => 'Method Section Heading Prefix',
                'description' => 'Method Section Description',

                'eye_tracking_title' => 'Eye Tracking Display Title',
                'eye_tracking_description' => 'Eye Tracking Description',

                'clicks_title' => 'Clicks Display Title',
                'clicks_description' => 'Clicks Description',

                'emotion_title' => 'Emotion Measurement Display Title',
                'emotion_description' => 'Emotion Measurement Description',

                'surveys_title' => 'Surveys Display Title',
                'surveys_description' => 'Surveys Description',

                'rtm_title' => 'RTM Display Title',
                'rtm_description' => 'RTM Description',

                'next_button_text' => 'Next Button Text',

                default => str_replace('_', ' ', $key),
            };
        }

        if ($page === 'home' && $section === 'study_details') {
            return match ($key) {
                'heading' => 'Study Details Heading',
                'description' => 'Study Details Description',

                'country_label' => 'Country Label',
                'country_placeholder' => 'Country Placeholder',

                'audience_label' => 'Audience Label',
                'audience_placeholder' => 'Audience Placeholder',

                'sample_size_label' => 'Sample Size Label',

                'timeline_label' => 'Timeline Label',
                'timeline_placeholder' => 'Timeline Placeholder',

                'notes_label' => 'Notes Label',
                'notes_placeholder' => 'Notes Placeholder',

                'next_button_text' => 'Next Button Text',

                default => str_replace('_', ' ', $key),
            };
        }

        /*
        |--------------------------------------------------------------------------
        | Section 06 — Submit Request Field Labels
        |--------------------------------------------------------------------------
        |
        | These labels are used for server-side validation
        | errors in the CMS dashboard.
        |
        */
        if ($page === 'home' && $section === 'submit_request') {
            return match ($key) {
                'heading' => 'Submit Request Heading',
                'description' => 'Submit Request Description',

                'full_name_label' => 'Full Name Label',
                'full_name_placeholder' => 'Full Name Placeholder',

                'email_label' => 'Email Label',
                'email_placeholder' => 'Email Placeholder',

                'phone_label' => 'Phone Number Label',
                'phone_placeholder' => 'Phone Number Placeholder',

                'company_label' => 'Company Label',
                'company_placeholder' => 'Company Placeholder',
                'optional_label' => 'Optional Field Label',

                'notes_heading' => 'Additional Notes Heading',
                'notes_empty_text' => 'Empty Notes Message',
                'edit_notes_text' => 'Edit Notes Button Text',

                'submit_button_text' => 'Submit Request Button Text',
                'submitting_text' => 'Submitting Button Text',
                'guest_notice' => 'Guest Login Notice',

                'summary_heading' => 'Study Summary Heading',
                'summary_edit_text' => 'Study Summary Edit Button',

                'summary_goal_label' => 'Summary Goal Label',
                'summary_method_label' => 'Summary Method Label',
                'summary_country_label' => 'Summary Target Market Label',
                'summary_audience_label' => 'Summary Audience Label',
                'summary_sample_size_label' => 'Summary Sample Size Label',
                'summary_timeline_label' => 'Summary Timeline Label',

                'not_selected_text' => 'Not Selected Placeholder',
                'not_provided_text' => 'Not Provided Placeholder',

                default => str_replace('_', ' ', $key),
            };
        }

        return str_replace('_', ' ', $key);
    }

    /**
     * Return registered fields for a valid CMS section.
     *
     * Unknown pages and sections are rejected.
     */
    private function getAllowedFields(
        string $page,
        string $section
    ): array {
        if (
            !isset(self::CMS_SECTIONS[$page]) ||
            !isset(self::CMS_SECTIONS[$page][$section])
        ) {
            throw ValidationException::withMessages([
                'section' => 'The selected CMS section is invalid.',
            ]);
        }

        return self::CMS_SECTIONS[$page][$section];
    }
}
