<?php

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
        Schema::create('access_accounts', function (Blueprint $table) {
            $table->unsignedInteger('admin_id')->index();
            $table->unsignedInteger('access_id')->index();

            $table->foreign('admin_id')
                ->references('id')
                ->on((new User())->getTable())
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('access_id')
                ->references('id')
                ->on((new User())->getTable())
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamps();

            $table->primary(
                [
                    'admin_id',
                    'access_id'
                ],
                'id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('access_accounts');
    }
};
