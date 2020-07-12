<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->integer('product_id');
            $table->string('product_name');
            $table->double('product_price');
            $table->string('product_image');
            $table->double('discount');
            $table->boolean('is_hot_product');
            $table->boolean('is_new_arriaval');
            $table->integer('product_category_id');
            $table->integer('user_id');
            $table->enum('status', array("0", "1"));
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
        Schema::dropIfExists('products');
    }
}
