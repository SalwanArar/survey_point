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
            CREATE OR REPLACE TRIGGER `trigger_create_location_invoices`
            BEFORE INSERT ON `Invoices`
            FOR EACH ROW
            BEGIN
                DECLARE `city` VARCHAR(64);
                DECLARE `country_name` TINYTEXT;
                SELECT `Cities`.`city_name`, `Cities`.`country`
                    INTO `city`, `country_name`
                    FROM `Cities`
                    WHERE `Cities`.`id` = (
                        SELECT `Businesses`.`city_id`
                            FROM `Businesses`
                            WHERE `Businesses`.`user_id` = NEW.`user_id`);
                SET NEW.`location` = `country_name` + \"\\n\" + `city` + \"\\n\" + NEW.`location`;
            END;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS `Invoices`.`trigger_create_location_invoices`");
    }
};
