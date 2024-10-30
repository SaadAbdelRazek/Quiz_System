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
        Schema::table('quizzes', function (Blueprint $table) {
            $table->text('description')->nullable()->after('subject');  // وصف الكويز (اختياري)
            $table->foreignId('quizzer_id')->constrained()->onDelete('cascade')->after('id');
            $table->integer('duration')->default(0)->after('description');  // مدة الكويز بالدقائق
            $table->integer('attempts')->default(1)->after('duration');  // عدد المحاولات المسموحة
            $table->boolean('show_answers_after_submission')->default(false)->after('attempts');  // عرض الإجابات بعد الانتهاء
            $table->enum('visibility', ['public', 'private'])->default('public')->after('show_answers_after_submission');
            $table->string('password')->nullable()->after('visibility');  // Password for restricted quizzes
            $table->string('access_token')->unique()->nullable()->after('password');  // Unique link for private quizzes
            $table->boolean('is_published')->default(false)->after('target_audience')->after('access_token');  // حالة نشر الكويز
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'duration',
                'attempts',
                'show_answers_after_submission',
                'visibility',
                'password',
                'access_token',
                'is_published'
            ]);
        });
    }
};
