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
            $table->id();
            $table->string('sku');
            $table->integer('price')->unsigned();
            $table->string('status')->default('active');
            $table->string('premoderation_status')->default('approved');
            $table->integer('amount')->default(0);
            $table->integer('has_amount_split_by_stock')->unsigned()->default(0);
            $table->json('usergroup_ids');
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
