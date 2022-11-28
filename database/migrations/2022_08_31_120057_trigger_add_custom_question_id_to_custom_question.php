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
            CREATE OR REPLACE TRIGGER `trigger_add_custom_question_id_to_custom_question`
            BEFORE INSERT ON `Custom_Questions`
            FOR EACH ROW
            BEGIN
                DECLARE `id` INT;
                IF ISNULL(NEW.`custom_question_id`) THEN
                    SELECT `AUTO_INCREMENT`
                        INTO `id`
                        FROM  INFORMATION_SCHEMA.TABLES
                        WHERE TABLE_SCHEMA = 'survey_you_api_v_09'
                        AND TABLE_NAME   = 'Custom_Questions';
                    SET NEW.`custom_question_id` = `id`;
                END IF;
            END;"
        );
    }

    /**
     *
    BEGIN
    DECLARE `id` INT;
    SELECT `AUTO_INCREMENT`
    INTO `id`
    FROM INFORMATION_SCHEMA.TABLES
    WHERE TABLE_SCHEMA = 'survey_you_api_v_09'
    AND TABLE_NAME = 'Custom_Questions';
    SET NEW.`test` = NEW.`custom_question_id`;
    IF ISNULL(NEW.`custom_question_id`) THEN
    SELECT `AUTO_INCREMENT`
    INTO `id`
    FROM INFORMATION_SCHEMA.TABLES
    WHERE TABLE_SCHEMA = 'survey_you_api_v_09'
    AND TABLE_NAME = 'Custom_Questions';
    SET NEW.`custom_question_id` = `id`;
    END IF;
    END;
    */

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS `Custom_Questions`.`trigger_add_custom_question_id_to_custom_question`");
    }
};
