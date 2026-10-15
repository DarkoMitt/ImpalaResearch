<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('research_requests', function (Blueprint $table) {
            $table->unsignedInteger('user_request_number')
                ->nullable()
                ->after('user_id');
        });

        // Number existing studies independently for each user.
        // Ordering by ID keeps the sequence stable.
        $lastUserId = null;
        $sequence = 0;

        foreach (
            DB::table('research_requests')
                ->whereNotNull('user_id')
                ->orderBy('user_id')
                ->orderBy('id')
                ->cursor() as $study
        ) {
            if ($lastUserId !== (int) $study->user_id) {
                $lastUserId = (int) $study->user_id;
                $sequence = 0;
            }

            $sequence++;

            DB::table('research_requests')
                ->where('id', $study->id)
                ->update([
                    'user_request_number' => $sequence,
                ]);
        }

        Schema::table('research_requests', function (Blueprint $table) {
            $table->unique(
                ['user_id', 'user_request_number'],
                'research_requests_user_number_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('research_requests', function (Blueprint $table) {
            $table->dropUnique(
                'research_requests_user_number_unique'
            );

            $table->dropColumn('user_request_number');
        });
    }
};
