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
        Schema::create('type_of_subscriptions', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('subscription_type', 64)->unique();
            $table->decimal('price', 10, 3, true)->default(0);
            $table->boolean('valid')->default(true);
            $table->text('subscription_details');

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
        Schema::dropIfExists('type_of_subscriptions');
    }
};
