<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('type_of_questions', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->enum(
                'question_type',
                [
                    'yes_no',
                    'rating',
                    'satisfaction',
                    'mcq',
                    'mcq_pic',
                    'checkbox',
                    'checkbox_pic',
                    'custom_rating',
                    'custom_satisfaction',
                ]);
            $table->tinyText('question_type_details')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('type_of_questions');
    }
};
