<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserActivitiesTable extends Migration
{
    public function up()
    {
        Schema::create('user_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('activity'); // e.g., 'Logged In', 'Submitted Quiz', etc.
            $table->timestamps(); // To track when the activity occurred
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_activities');
    }
}
