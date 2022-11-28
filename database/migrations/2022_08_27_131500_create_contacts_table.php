<?php

use App\Models\City;
use App\Models\User;
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
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('f_name', 64);
            $table->string('l_name', 64)->nullable();
            $table->char('mobile_number', 15)->nullable();
            $table->tinyText('email')->nullable();
            $table->enum('gender', ['Male', 'Female']);

            $user = new User();
            $city = new City();
            $table->unsignedInteger($user->getForeignKey())->index();
            $table->unsignedInteger($city->getForeignKey())->nullable();

            $table->timestamps();

            $table->foreign($user->getForeignKey())
                ->references('id')
                ->on($user->getTable())
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign($city->getForeignKey())
                ->references('id')
                ->on($city->getTable())
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
