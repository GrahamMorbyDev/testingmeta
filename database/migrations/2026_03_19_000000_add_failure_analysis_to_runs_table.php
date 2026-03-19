<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFailureAnalysisToRunsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds columns to store an analysed failure reason and suggestions so the
     * frontend can present helpful guidance to users when a run fails.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('runs')) {
            return;
        }

        Schema::table('runs', function (Blueprint $table) {
            if (!Schema::hasColumn('runs', 'failure_reason')) {
                $table->string('failure_reason')->nullable()->after('status');
            }

            if (!Schema::hasColumn('runs', 'failure_suggestions')) {
                // JSON column to store an array of suggested fixes/next steps
                $table->json('failure_suggestions')->nullable()->after('failure_reason');
            }

            if (!Schema::hasColumn('runs', 'last_failed_at')) {
                $table->timestamp('last_failed_at')->nullable()->after('failure_suggestions');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('runs')) {
            return;
        }

        Schema::table('runs', function (Blueprint $table) {
            if (Schema::hasColumn('runs', 'failure_reason')) {
                $table->dropColumn('failure_reason');
            }

            if (Schema::hasColumn('runs', 'failure_suggestions')) {
                $table->dropColumn('failure_suggestions');
            }

            if (Schema::hasColumn('runs', 'last_failed_at')) {
                $table->dropColumn('last_failed_at');
            }
        });
    }
}
