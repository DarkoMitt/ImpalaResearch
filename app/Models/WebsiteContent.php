<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class WebsiteContent extends Model
{
    /**
     * The database table associated with the model.
     */
    protected $table = 'website_contents';

    /**
     * Attributes that can be mass assigned.
     */
    protected $fillable = [
        'page',
        'section',
        'key',
        'value',
        'type',
        'is_published',
        'draft_value',
        'has_draft',
        'published_at',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'is_published' => 'boolean',
        'has_draft' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Scope: Filter content by page.
     */
    public function scopeForPage(
        Builder $query,
        string $page
    ): Builder {
        return $query->where('page', $page);
    }

    /**
     * Scope: Filter content by section.
     */
    public function scopeForSection(
        Builder $query,
        string $section
    ): Builder {
        return $query->where('section', $section);
    }

    /**
     * Scope: Only published content.
     */
    public function scopePublished(
        Builder $query
    ): Builder {
        return $query->where('is_published', true);
    }

    /**
     * Scope: Content with unpublished drafts.
     */
    public function scopeWithDraft(
        Builder $query
    ): Builder {
        return $query->where('has_draft', true);
    }

    /**
     * Get the published content value.
     *
     * Draft values are never exposed through this method.
     */
    public static function getValue(
        string $page,
        string $section,
        string $key,
        ?string $default = null
    ): ?string {
        $content = static::query()
            ->forPage($page)
            ->forSection($section)
            ->published()
            ->where('key', $key)
            ->first();

        return $content?->value ?? $default;
    }

    /**
     * Create or update a published content entry.
     *
     * Kept for backward compatibility with the
     * existing CMS implementation.
     *
     * The workflow controller should use
     * saveDraft() instead.
     */
    public static function setValue(
        string $page,
        string $section,
        string $key,
        ?string $value,
        string $type = 'text'
    ): self {
        return static::updateOrCreate(
            [
                'page' => $page,
                'section' => $section,
                'key' => $key,
            ],
            [
                'value' => $value,
                'type' => $type,
            ]
        );
    }

    /**
     * Save an unpublished draft.
     *
     * Existing published content remains unchanged.
     */
    public static function saveDraft(
        string $page,
        string $section,
        string $key,
        ?string $draftValue,
        string $type = 'text'
    ): self {
        $content = static::firstOrNew([
            'page' => $page,
            'section' => $section,
            'key' => $key,
        ]);

        $content->type = $type;

        /*
         * Store the new content separately.
         */
        $content->draft_value = $draftValue;

        /*
         * A draft can intentionally contain
         * an empty or null value.
         */
        $content->has_draft = true;

        /*
         * Do not modify:
         *
         * - value
         * - is_published
         * - published_at
         */
        $content->save();

        return $content;
    }

    /**
     * Publish the current draft.
     *
     * Draft content becomes the live website content.
     */
    public function publishDraft(): bool
    {
        if (!$this->has_draft) {
            return false;
        }

        /*
         * Replace the published value.
         */
        $this->value = $this->draft_value;

        /*
         * Mark content as published.
         */
        $this->is_published = true;

        /*
         * Clear the draft after publication.
         */
        $this->draft_value = null;
        $this->has_draft = false;

        /*
         * Record publication time.
         */
        $this->published_at = now();

        $this->save();

        return true;
    }

    /**
     * Discard unpublished changes.
     *
     * Published content remains unchanged.
     */
    public function discardDraft(): bool
    {
        if (!$this->has_draft) {
            return false;
        }

        $this->draft_value = null;
        $this->has_draft = false;

        $this->save();

        return true;
    }
}
