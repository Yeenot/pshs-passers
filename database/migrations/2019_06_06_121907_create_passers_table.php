<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePassersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Passers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('surname');
            $table->string('firstname');
            $table->string('campus_eligibility');
            $table->string('school');
            $table->string('division');
        });

        DB::statement('ALTER TABLE Passers ADD FULLTEXT search(surname, firstname, school, division)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Passers', function($table) {
            $table->dropIndex('search');
        });
            
        Schema::dropIfExists('Passers');
    }
}
