<?php

use App\Models\City;
use App\Models\Registration;
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
        Schema::create('reviewed_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64)->nullable();
            $table->string('contact_number', 20)->nullable();
            $table->enum('nationality', ['Emarati-an', 'Syrian', 'Jordanian', 'Palestinian', 'Indian', 'Pakistani-an']);
            $table->enum('gender', ['Male', 'Female']);
            $table->enum('residency', ['Citizen', 'Residence', 'Visit']);

            $registration = new Registration();
            $city = new City();
            $table->unsignedInteger($registration->getForeignKey())->index();
            $table->unsignedInteger($city->getForeignKey())->nullable();

            $table->timestamps();

            $table->foreign($registration->getForeignKey())
                ->references('id')
                ->on($registration->getTable())
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
        Schema::dropIfExists('reviewed_registrations');
    }
};
