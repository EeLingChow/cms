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
        Schema::create('module', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('master_id')->unsigned()->default(0);
            $table->string('name', 255);
            $table->string('modulekey', 100)->unique();
            $table->decimal('sequence', 5, 2)->default(0.00);
            $table->string('route', 100)->nullable();
            $table->boolean('is_superadmin')->default(false);
            $table->boolean('is_master')->default(false);
            $table->boolean('is_hidden')->default(false);
            $table->string('updated_by', 50)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module');
    }
};
