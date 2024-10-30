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
        Schema::table('questions', function (Blueprint $table) {
            $table->float('points')->default(1)->after('is_true');
            $table->text('hints')->nullable()->after('points');
            $table->boolean('is_randomized')->default(false)->after('hints');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn([
                'points',
                'hints',
                'is_randomized'
            ]);
        });
    }
};
