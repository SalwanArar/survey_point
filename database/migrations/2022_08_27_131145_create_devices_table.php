<?php

use App\Models\DeviceCode;
use App\Models\Registration;
use App\Models\Survey;
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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->tinyText('device_name');

            $deviceCode = new DeviceCode();
            $user = new User();
            $survey = new Survey();
            $registration = new Registration();
            $table->unsignedInteger($deviceCode->getForeignKey())->unique();
            $table->unsignedInteger($user->getForeignKey())->index();
            $table->unsignedInteger($survey->getForeignKey())->nullable();
            $table->unsignedInteger($registration->getForeignKey())->nullable();

            $table->timestamps();

            $table->foreign($deviceCode->getForeignKey())
                ->references('id')
                ->on($deviceCode->getTable())
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign($user->getForeignKey())
                ->references('id')
                ->on($user->getTable())
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign($survey->getForeignKey())
                ->references('id')
                ->on($survey->getTable())
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreign($registration->getForeignKey())
                ->references('id')
                ->on($registration->getTable())
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
        Schema::dropIfExists('devices');
    }
};
