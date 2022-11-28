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
            CREATE OR REPLACE TRIGGER  `trigger_generate_invoice_number`
            BEFORE INSERT ON `Invoices` FOR EACH ROW
            BEGIN
                DECLARE num VARCHAR(6);
                SELECT `AUTO_INCREMENT`
                    INTO num
                    FROM  INFORMATION_SCHEMA.TABLES
                    WHERE TABLE_SCHEMA = 'survey_you_api_v_09'
                        AND TABLE_NAME   = 'Invoices';
                WHILE LENGTH(num) < 6 DO
                    SET num = CONCAT('0', num);
                END WHILE;
                SET NEW.`invoice_no` = CONCAT('CSS-', DATE_FORMAT(CURRENT_DATE, '%m%y'), '-', num);
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
        DB::unprepared("DROP TRIGGER IF EXISTS `Invoices`.`trigger_generate_invoice_number`");
    }
};
