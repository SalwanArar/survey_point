<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        DB::unprepared("
        CREATE OR REPLACE TRIGGER `trigger_add_subscription`
        AFTER INSERT ON `Businesses`
        FOR EACH ROW
        BEGIN
            INSERT INTO
                `Subscriptions` (
                    `business_id`,
                    `type_of_subscription_id`)
                VALUES (
                    NEW.`id`,
                    1);
        END;"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS `Businesses`.`trigger_add_subscription`');
    }
};
