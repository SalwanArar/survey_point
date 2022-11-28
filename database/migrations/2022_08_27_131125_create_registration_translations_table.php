<?php

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
        Schema::create('registration_translations', function (Blueprint $table) {
            $table->id();
            $table->tinyText('registration_details');
            $table->char('registration_local', 2);

            $registration = new Registration();
            $table->unsignedInteger($registration->getForeignKey())->index();

            $table->timestamps();

            $table->foreign($registration->getForeignKey())
                ->references('id')
                ->on($registration->getTable())
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_translations');
    }
};
