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
        Schema::create('infobox_category_properties', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('infobox_id')->unsigned();
            $table->foreign('infobox_id')->references('id')->on('infoboxes')->onDelete('restrict');
            $table->bigInteger('data_type_id')->unsigned();
            $table->foreign('data_type_id')->references('id')->on('data_types')->onDelete('restrict');
            $table->string('code')->unique();
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('multiple')->default(0);
            $table->json('options')->nullable();
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
        Schema::dropIfExists('infobox_category_properties');
    }
};
