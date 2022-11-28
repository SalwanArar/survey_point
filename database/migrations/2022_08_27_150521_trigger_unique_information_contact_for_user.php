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
            CREATE OR REPLACE TRIGGER `trigger_unique_information_contact_for_user_update`
            BEFORE UPDATE ON `Contacts`
            FOR EACH ROW
            BEGIN
                IF
                    EXISTS (
                        SELECT *
                            FROM `Contacts`
                            WHERE
                                `Contacts`.`user_id` = NEW.`user_id`
                                AND
                                (
                                    (
                                        `Contacts`.`mobile_number` = NEW.`mobile_number`
                                        AND NEW.`mobile_number` != NULL
                                    )
                                    OR
                                    (
                                        `Contacts`.`email` = NEW.`email`
                                        AND NEW.`email` != NULL
                                    )
                                )
                    )
                THEN
                    SIGNAL SQLSTATE '40100' SET MESSAGE_TEXT = 'Contacts already attached to this user';
                END IF;
            END;

            CREATE OR REPLACE TRIGGER `trigger_unique_information_contact_for_user_insert`
            BEFORE INSERT ON `Contacts`
            FOR EACH ROW
            BEGIN
                IF
                    EXISTS (
                        SELECT *
                            FROM `Contacts`
                            WHERE
                                `Contacts`.`user_id` = NEW.`user_id`
                                AND
                                (
                                    (
                                        `Contacts`.`mobile_number` = NEW.`mobile_number`
                                        AND NEW.`mobile_number` != NULL
                                    )
                                    OR
                                    (
                                        `Contacts`.`email` = NEW.`email`
                                        AND NEW.`email` != NULL
                                    )
                                )
                    )
                THEN
                    SIGNAL SQLSTATE '40100' SET MESSAGE_TEXT = 'Contacts already attached to this user';
                END IF;
            END;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS `Contacts`.`trigger_unique_information_contact_for_user_update`");
        DB::unprepared("DROP TRIGGER IF EXISTS `Contacts`.`trigger_unique_information_contact_for_user_insert`");
    }
};
