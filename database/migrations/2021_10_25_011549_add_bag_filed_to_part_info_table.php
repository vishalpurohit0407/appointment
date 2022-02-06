<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBagFiledToPartInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('htl_part_infos', function (Blueprint $table) {
            $table->decimal('pkg_in_bag', 12,2)->nullable()->after('pkg_inner');
            $table->decimal('pkg_in_box', 12,2)->nullable()->after('pkg_in_bag');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('htl_part_infos', function (Blueprint $table) {
            //
        });
    }
}
