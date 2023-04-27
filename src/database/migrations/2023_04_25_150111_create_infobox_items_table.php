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
        Schema::create('infobox_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('infobox_id')->unsigned();
            $table->foreign('infobox_id')->references('id')->on('infoboxes')->onDelete('restrict');
            $table->bigInteger('category_id')->unsigned()->nullable();
            $table->foreign('category_id')->references('id')->on('infobox_categories')->onDelete('set null');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('meta_description')->nullable();
            $table->boolean('active')->default(1)->nullable();
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
        Schema::dropIfExists('infobox_items');
    }
};
