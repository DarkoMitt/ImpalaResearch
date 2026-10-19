<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('website_contents', function (Blueprint $table) {
            $table->id();

            // Website page, e.g. home, contact, pricing
            $table->string('page', 100);

            // Page section, e.g. hero, about, methodology
            $table->string('section', 100);

            // Content identifier, e.g. title, subtitle, button_text
            $table->string('key', 150);

            // Content value
            $table->longText('value')->nullable();

            // Content type: text, textarea, image, url
            $table->string('type', 30)->default('text');

            // Draft / published state
            $table->boolean('is_published')->default(true);

            $table->timestamps();

            // Prevent duplicate content identifiers
            $table->unique(
                ['page', 'section', 'key'],
                'website_contents_unique'
            );

            // Improve CMS filtering
            $table->index(['page', 'section']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_contents');
    }
};
