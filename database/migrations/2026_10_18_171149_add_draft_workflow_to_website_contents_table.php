<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Add Draft / Preview / Publish workflow fields
     * without modifying existing published content.
     */
    public function up(): void
    {
        Schema::table('website_contents', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Draft Content
            |--------------------------------------------------------------------------
            |
            | Stores unpublished changes.
            | The existing "value" column remains
            | the currently published content.
            |
            */

            $table->longText('draft_value')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Draft Status
            |--------------------------------------------------------------------------
            |
            | Indicates whether an unpublished draft exists.
            |
            */

            $table->boolean('has_draft')
                ->default(false);

            /*
            |--------------------------------------------------------------------------
            | Publication Timestamp
            |--------------------------------------------------------------------------
            |
            | Stores the date and time of the last publication.
            |
            */

            $table->timestamp('published_at')
                ->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * Only remove workflow columns.
     * Preserve existing CMS records and published values.
     */
    public function down(): void
    {
        Schema::table('website_contents', function (Blueprint $table) {

            $table->dropColumn([
                'draft_value',
                'has_draft',
                'published_at',
            ]);

        });
    }
};
