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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->tinyText('organization_name');
            $table->char('mobile_number', 20)->unique();
            $table->char('phone_number', 20)->nullable();
            $table->char('trn', 15)->unique();
            $table->tinyText('location');

            $user = new User();
            $city = new City();
            $table->unsignedInteger($user->getForeignKey())->index();
            $table->unsignedInteger($city->getForeignKey());

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
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
