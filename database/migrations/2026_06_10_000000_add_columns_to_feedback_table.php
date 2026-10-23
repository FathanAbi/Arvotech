<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * SEC-001 follow-up.
 *
 * `2026_06_03_083149_create_feedback_table` shipped with a broken `up()`
 * (only `id` + timestamps) and a `down()` that actually created the table.
 * Databases that already ran it have a `feedback` table with 3 columns, so
 * `Admin\FeedbackController@index` fails on `where('category', 'kritik')`.
 *
 * This migration repairs already-migrated databases. It is a no-op on fresh
 * databases, where the corrected base migration already creates the columns.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('feedback', function (Blueprint $table) {

            if (! Schema::hasColumn('feedback', 'name')) {
                $table->string('name')->after('id');
            }

            if (! Schema::hasColumn('feedback', 'email')) {
                $table->string('email')->nullable()->after('name');
            }

            if (! Schema::hasColumn('feedback', 'category')) {
                $table->enum('category', [
                    'kritik',
                    'saran'
                ])->after('email');
            }

            if (! Schema::hasColumn('feedback', 'message')) {
                $table->text('message')->after('category');
            }

            if (! Schema::hasColumn('feedback', 'is_read')) {
                $table->boolean('is_read')
                    ->default(false)
                    ->after('message');
            }

        });
    }

    /**
     * Reverse the migrations.
     *
     * Intentionally a no-op. `up()` is conditional: on a fresh database the
     * corrected base migration already owns these columns, so dropping them
     * here would destroy schema this migration never created. On a legacy
     * database, reverting would restore the broken 3-column state. Neither
     * outcome is useful, so rollback leaves the table repaired.
     */
    public function down(): void
    {
        //
    }
};
