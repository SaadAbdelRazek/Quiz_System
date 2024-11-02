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
        Schema::table('results', function (Blueprint $table) {
            $table->unsignedBigInteger('quizzer_id')->after('quiz_id');
            $table->foreign('quizzer_id')->references('id')->on('quizzers')->onDelete('cascade');
            $table->float('points')->nullable()->after('total_questions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
{
    Schema::table('results', function (Blueprint $table) {
        $table->dropForeign(['quizzer_id']); // حذف المفتاح الخارجي أولاً
        $table->dropColumn(['quizzer_id', 'points']); // حذف الأعمدة
    });
}

};
