<?php

use App\Models\Device;
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
        Schema::create('device_languages', function (Blueprint $table) {
            $table->id();
            $table->char('device_local', 2);

            $modelDevice = new Device();
            $table->unsignedInteger($modelDevice->getForeignKey())->index();

            $table->timestamps();

            $table->foreign($modelDevice->getForeignKey())
                ->references('id')
                ->on($modelDevice->getTable())
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
        Schema::dropIfExists('device_languages');
    }
};
