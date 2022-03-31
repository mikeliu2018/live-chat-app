<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePersonalAccessTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->uuid('id')->change();
            $table->dropMorphs('tokenable');            
        });

        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->after('id', function(Blueprint $table){
                $table->uuidMorphs('tokenable');
            });
            
        });
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
