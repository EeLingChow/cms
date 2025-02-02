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
        Schema::create('category_assignment', function (Blueprint $table) {
            $table->integer('shop_id')->unsigned();
            $table->integer('category_id')->unsigned();

            $table->unique(['shop_id', 'category_id'], 'shop_category');
            $table->index('shop_id');
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_assignment');
    }
};
