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
        Schema::create('infobox_category_property_values', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('property_id')->unsigned();
            $table->foreign('property_id')->references('id')->on('infobox_category_properties')->onDelete('cascade');
            $table->bigInteger('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('infobox_categories')->onDelete('cascade');
            $table->boolean('bool_value')->nullable();
            $table->bigInteger('int_value')->nullable();
            $table->float('float_value')->nullable();
            $table->string('string_value')->nullable();
            $table->text('text_value')->nullable();
            $table->date('date_value')->nullable();
            $table->dateTime('datetime_value')->nullable();
            $table->string('file_value')->nullable();
            $table->string('image_value')->nullable();
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
        Schema::dropIfExists('infobox_category_property_values');
    }
};
