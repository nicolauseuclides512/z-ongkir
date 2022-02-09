<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetLionParcelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_lion_parcels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("area_code")->nullable();
            $table->string("city")->nullable();
            $table->string("booking_route")->nullable();
            $table->string("cost_route")->nullable();
            $table->boolean("is_city")->nullable();
            $table->boolean("status")->nullable()->default(true);

            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset_lion_parcels');
    }
}
