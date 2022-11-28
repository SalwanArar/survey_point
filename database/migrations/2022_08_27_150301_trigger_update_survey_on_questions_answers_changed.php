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
            CREATE OR REPLACE TRIGGER `trigger_update_survey_question_update`
            AFTER UPDATE ON `Questions`
            FOR EACH ROW
            BEGIN
                UPDATE `Surveys`
                    SET `Surveys`.`updated_at` = CURRENT_TIMESTAMP()
                    WHERE `Surveys`.`id` = NEW.`survey_id`;
            END;

            CREATE OR REPLACE TRIGGER `trigger_update_survey_question_insert`
            AFTER INSERT ON `Questions`
            FOR EACH ROW
            BEGIN
                UPDATE `Surveys`
                    SET `Surveys`.`updated_at` = CURRENT_TIMESTAMP()
                    WHERE `Surveys`.`id` = NEW.`survey_id`;
            END;

            CREATE OR REPLACE TRIGGER `trigger_update_questions_answer_update`
            AFTER UPDATE ON `Answers`
            FOR EACH ROW
            BEGIN
                UPDATE `Questions`
                    SET `Questions`.`updated_at` = CURRENT_TIMESTAMP()
                    WHERE `Questions`.`id` = NEW.`question_id`;
            END;

            CREATE OR REPLACE TRIGGER `trigger_update_questions_answer_insert`
            AFTER INSERT ON `Answers`
            FOR EACH ROW
            BEGIN
                UPDATE `Questions`
                    SET `Questions`.`updated_at` = CURRENT_TIMESTAMP()
                    WHERE `Questions`.`id` = NEW.`question_id`;
            END;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS `Questions`.`trigger_update_survey_question_update`");
        DB::unprepared("DROP TRIGGER IF EXISTS `Questions`.`trigger_update_survey_question_insert`");
        DB::unprepared("DROP TRIGGER IF EXISTS `Answers`.`trigger_update_questions_answer_update`");
        DB::unprepared("DROP TRIGGER IF EXISTS `Answers`.`trigger_update_questions_answer_insert`");
    }
};
