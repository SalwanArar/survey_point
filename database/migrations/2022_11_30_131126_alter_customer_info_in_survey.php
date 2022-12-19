<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropColumns('surveys', ['customer_info']);
        Schema::table('surveys', function (Blueprint $table) {
            $table->json('customer_info')->default('{
                "name": "none",
                "comment": "none",
                "birthday": "none",
                "contact": "none"
            }');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('survey', function (Blueprint $table) {
            //
        });
    }
};
