<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Add Public Request ID Column
        |--------------------------------------------------------------------------
        |
        | This ID is shared between users and administrators.
        | Example: REQ-K7M4P2
        |
        | Nullable initially so existing records can be updated.
        |
        */

        Schema::table('research_requests', function (Blueprint $table) {
            $table->string('public_request_id', 20)
                ->nullable()
                ->unique()
                ->after('user_request_number');
        });

        /*
        |--------------------------------------------------------------------------
        | Generate Public IDs for Existing Research Requests
        |--------------------------------------------------------------------------
        */

        DB::table('research_requests')
            ->select('id')
            ->orderBy('id')
            ->chunkById(100, function ($requests) {

                foreach ($requests as $request) {

                    do {
                        $publicId = 'REQ-' . $this->generateRandomCode();

                        $exists = DB::table('research_requests')
                            ->where('public_request_id', $publicId)
                            ->exists();

                    } while ($exists);

                    DB::table('research_requests')
                        ->where('id', $request->id)
                        ->update([
                            'public_request_id' => $publicId,
                        ]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('research_requests', function (Blueprint $table) {
            $table->dropUnique(
                'research_requests_public_request_id_unique'
            );

            $table->dropColumn('public_request_id');
        });
    }

    /**
     * Generate a secure random six-character code.
     */
    private function generateRandomCode(): string
    {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        $code = '';

        $maxIndex = strlen($characters) - 1;

        for ($i = 0; $i < 6; $i++) {
            $code .= $characters[random_int(0, $maxIndex)];
        }

        return $code;
    }
};
