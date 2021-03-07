<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTweetedColumToUniqueWordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('unique_words', function (Blueprint $table) {
            $table->boolean('should_be_tweeted')->default(0)->after('occurrences');
            $table->boolean('tweeted')->default(0)->after('should_be_tweeted');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('unique_words', function (Blueprint $table) {
            $table->dropColumn(['should_be_tweeted', 'tweeted']);
        });
    }
}
