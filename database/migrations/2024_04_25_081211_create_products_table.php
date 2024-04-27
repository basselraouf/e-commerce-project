<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if(!Schema::hasTable('products')){
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('imageURL');
            $table->double('price');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->double('rating')->default(0);
            $table->timestamps();

            $table->foreign('category_id')
            ->references('id')
            ->on('categories')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
