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
        Schema::create('tasks_timers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger( 'task_id' )
                ->unsigned();
            $table->bigInteger( 'total_time' )
                ->nullable();
            $table->timestamp( 'task_start' );
            $table->timestamp( 'task_end' );
            $table->foreign( 'task_id' )
                ->references( 'id' )
                ->on( 'tasks' )
                ->onDelete( 'cascade' )
                ->onUpdate( 'cascade' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks_timers');
    }
};
