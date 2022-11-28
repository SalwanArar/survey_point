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
            CREATE OR REPLACE TRIGGER `trigger_prevent_delete_or_update_invoices`
            BEFORE DELETE ON `Invoices`
            FOR EACH ROW
            BEGIN
                SIGNAL SQLSTATE '40800' SET MESSAGE_TEXT = 'can\'t update or delete invoice row';
            END;

            CREATE OR REPLACE TRIGGER `trigger_prevent_delete_or_update_invoices`
            BEFORE UPDATE ON `Invoices`
            FOR EACH ROW
            BEGIN
                SIGNAL SQLSTATE '40800' SET MESSAGE_TEXT = 'can\'t update or delete invoice row';
            END;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS `Invoices`.`trigger_prevent_delete_or_update_invoices`');
        DB::unprepared('DROP TRIGGER IF EXISTS `Invoices`.`trigger_prevent_delete_or_update_invoices`');
    }
};
