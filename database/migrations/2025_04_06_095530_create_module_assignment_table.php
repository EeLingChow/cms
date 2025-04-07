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
        Schema::create('module_assignment', function (Blueprint $table) {
            $table->integer('admin_id')->unsigned();
            $table->integer('module_id')->unsigned();
            $table->tinyInteger('plusminus')->default(1); //1 = include, -1 = exclude
            $table->tinyInteger('permission')->unsigned()->default(0);
            $table->unique(['admin_id', 'module_id', 'plusminus'], 'admin_module_plusminus');
            $table->index(['admin_id', 'module_id'], 'admin_module');
            $table->index('admin_id');
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
        Schema::dropIfExists('module_assignment');
    }
};
