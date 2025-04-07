<?php

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
    public function up()
    {
        Schema::create('profile_module_assignment', function (Blueprint $table) {
            $table->integer('profile_id')->unsigned();
            $table->integer('module_id')->unsigned();
            $table->tinyInteger('permission')->unsigned()->default(0);
            $table->unique(['profile_id', 'module_id'], 'profile_module');
            $table->index('profile_id');
            $table->index('module_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile_module_assignment');
    }
};
