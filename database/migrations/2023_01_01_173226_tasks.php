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
        // TODO: Add user id on relationship.
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string( 'name' );
            $table->text( 'description' );
            $table->bigInteger( 'project_id' )
                ->unsigned();
            $table->timestamp( 'limit_date' )
                ->nullable();
            $table->timestamps();

            $table->foreign( 'project_id' )
                ->references( 'id' )
                ->on( 'projects' )
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
        Schema::dropIfExists('tasks');
    }
};
