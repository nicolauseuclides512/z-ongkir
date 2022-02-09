<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCarrierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::table('asset_carriers')
            ->where('id', 1)
            ->update(['logo' => 'https://s3-ap-southeast-1.amazonaws.com/sahitotest/assets/carriers/jne_id_small.png']);

        DB::table('asset_carriers')
            ->where('id', 2)
            ->update(['logo' => 'https://s3-ap-southeast-1.amazonaws.com/sahitotest/assets/carriers/pos_id_small.png']);

        DB::table('asset_carriers')
            ->where('id', 3)
            ->update(['logo' => 'https://s3-ap-southeast-1.amazonaws.com/sahitotest/assets/carriers/tiki_id_small.png']);

        DB::table('asset_carriers')
            ->where('id', 8)
            ->update(['logo' => 'https://s3-ap-southeast-1.amazonaws.com/sahitotest/assets/carriers/wahana_id_small.png']);

        DB::table('asset_carriers')
            ->where('id', 9)
            ->update(['logo' => 'https://s3-ap-southeast-1.amazonaws.com/sahitotest/assets/carriers/sicepat_id_small.png']);

        DB::table('asset_carriers')
            ->where('id', 10)
            ->update(['logo' => 'https://s3-ap-southeast-1.amazonaws.com/sahitotest/assets/carriers/jnt_id_small.png']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
