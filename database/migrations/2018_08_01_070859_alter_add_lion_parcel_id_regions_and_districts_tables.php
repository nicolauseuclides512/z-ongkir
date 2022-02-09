<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddLionParcelIdRegionsAndDistrictsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset_districts', function (Blueprint $table) {
            $table->bigInteger("lion_parcel_id")->nullable()->unsigned();
        });

        Schema::table('asset_regions', function (Blueprint $table) {
            $table->bigInteger("lion_parcel_id")->nullable()->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asset_districts', function (Blueprint $table) {
            $table->dropColumn("lion_parcel_id");
        });

        Schema::table('asset_regions', function (Blueprint $table) {
            $table->dropColumn("lion_parcel_id");
        });
    }
}
