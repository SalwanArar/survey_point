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
        CREATE OR REPLACE TRIGGER `trigger_generate_device_code`
        BEFORE INSERT ON `Device_Codes`
        FOR EACH ROW
        BEGIN
            DECLARE d_code CHAR(6) COLLATE 'latin1_general_ci';
            DECLARE temp INT;
            SET temp =
                EXISTS(
                    SELECT `Device_Codes`.`device_code`
                    FROM `Device_Codes`
                    WHERE `Device_Codes`.`device_code` = d_code
                    );
            SET d_code = SUBSTR(RAND(), 3, 6);
            WHILE
                temp = 1
            DO
                SET d_code = SUBSTR(RAND(), 3, 6);
                SET temp =
                    EXISTS(
                        SELECT `Device_Codes`.`device_code`
                        FROM `Device_Codes`
                        WHERE `Device_Codes`.`device_code` = d_code
                    );
            END WHILE;
            SET NEW.`device_code` = d_code;
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
        DB::unprepared('DROP TRIGGER IF EXISTS `Device_Codes`.`trigger_generate_device_code`');
    }
};
