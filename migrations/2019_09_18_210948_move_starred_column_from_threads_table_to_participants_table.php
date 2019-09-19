<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Lexx\ChatMessenger\Models\Models;

class MoveStarredColumnFromThreadsTableToParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn(Models::table('threads'), 'starred'))
        {
            Schema::table(Models::table('threads'), function (Blueprint $table) {
                $table->dropColumn('starred');
            });
        }

        if(! Schema::hasColumn(Models::table('participants'), 'starred'))
        {
            Schema::table(Models::table('participants'), function (Blueprint $table) {
                $table->boolean('starred')->default(false)->after('last_read');
            });
        }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(! Schema::hasColumn(Models::table('threads'), 'starred'))
        {
            Schema::table(Models::table('threads'), function (Blueprint $table) {
                $table->boolean('starred')->default(false)->after('id');
            });
        }
    
        if(Schema::hasColumn(Models::table('participants'), 'starred'))
        {
            Schema::table(Models::table('participants'), function (Blueprint $table) {
                $table->dropColumn('starred');
            });
        }
    }
}
