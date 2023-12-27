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
        Schema::table('infobox_item_property_values', function (Blueprint $table) {
            $table->time('time_value')->nullable()->after('date_value');
            $table->json('list_value')->nullable()->after('datetime_value');
            $table->json('json_value')->nullable()->after('datetime_value');
            $table->string('color_value')->nullable()->after('text_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('infobox_item_property_values', function (Blueprint $table) {
            $table->dropColumn('time_value');
            $table->dropColumn('list_value');
            $table->dropColumn('json_value');
            $table->dropColumn('color_value');
        });
    }
};
