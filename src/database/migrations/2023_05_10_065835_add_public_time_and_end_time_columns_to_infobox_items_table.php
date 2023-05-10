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
        Schema::table('infobox_items', function (Blueprint $table) {
            $table->timestamp('end_time')->nullable()->after('active');
            $table->timestamp('public_time')->nullable()->after('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('infobox_items', function (Blueprint $table) {
            $table->dropColumn('public_time');
            $table->dropColumn('end_time');
        });
    }
};
