<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER IF NOT EXISTS `tirgger_add_order_to_question`
            BEFORE INSERT ON `questions`
            FOR EACH ROW
                SET NEW.`question_order` = (
                    SELECT MAX(`question_order`)
                        FROM `questions`
                        WHERE `survey_id` = NEW.`survey_id`
                    ) + 1;'
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        DB::unprepared('DROP TRIGGER IF EXISTS `tirgger_add_order_to_question`');
    }
};
