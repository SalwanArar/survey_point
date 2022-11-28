<?php

use App\Models\Device;
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
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $modelDevice = new Device();
            $table->unsignedInteger($modelDevice->getForeignKey())->unique();

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
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            //
        });
    }
};
