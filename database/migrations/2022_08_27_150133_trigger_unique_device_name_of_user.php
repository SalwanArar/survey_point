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
            CREATE OR REPLACE TRIGGER `trigger_unique_device_name_of_user_insert`
            BEFORE INSERT ON `Devices`
            FOR EACH ROW
            BEGIN
	            DECLARE `business_name` TINYTEXT COLLATE 'utf8mb4_unicode_ci';
	            DECLARE `devices_count` INT DEFAULT 1;
	            IF NEW.`device_name` = '' THEN
                    SELECT `Businesses`.`organization_name`
                        INTO `business_name`
                        FROM `Businesses`
                        WHERE `Businesses`.`user_id` = NEW.`user_id`;
                    WHILE EXISTS(
                        SELECT *
                            FROM `Devices`
                            WHERE
                                `Devices`.`user_id` = NEW.`user_id`
                                AND `Devices`.`device_name` = CONCAT(`business_name`, '_', `devices_count`)
                        ) = 1
                        DO SET `devices_count` = `devices_count` + 1;
                    END WHILE;
                    SET NEW.`device_name` = CONCAT(`business_name`, '_', `devices_count`);
                ELSEIF EXISTS(
                    SELECT *
                        FROM `Devices`
                        WHERE
                            `Devices`.`user_id` = NEW.`user_id`
                            AND `Devices`.`device_name` = NEW.`device_name`
                    ) = 1
                    THEN SIGNAL SQLSTATE '40100' SET MESSAGE_TEXT = 'Device name already attached to this user';
                END IF;
            END;

            CREATE OR REPLACE TRIGGER `trigger_unique_device_name_of_user_update`
            BEFORE UPDATE ON `devices`
            FOR EACH ROW
            BEGIN
                IF NEW.`device_name` <> OLD.`device_name` THEN
                    IF EXISTS(
                        SELECT `Devices`.`device_name`
                            FROM `Devices`
                            WHERE `Devices`.`user_id` = NEW.`user_id`
                                AND `Devices`.`device_name` = NEW.`device_name`
                        ) = 1 THEN
                        SIGNAL SQLSTATE '40100' SET MESSAGE_TEXT = 'Device name already attached to this user';
                    END IF;
                END IF;
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
        DB::unprepared("DROP TRIGGER IF EXISTS `Devices`.`trigger_unique_device_name_of_user_insert`");
        DB::unprepared("DROP TRIGGER IF EXISTS `Devices`.`trigger_unique_device_name_of_user_update`");
    }
};
