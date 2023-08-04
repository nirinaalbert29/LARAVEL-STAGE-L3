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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->integer('num_tic')->nullable();
            $table->string('type_projet')->nullable();
            $table->string('statut')->default('en cours');
            $table->string('observation')->nullable();
            $table->dateTime('dateHeure_fin')->nullable();
            $table->time('delai')->nullable();
            $table->foreignId('intervenants_id');
            $table->foreignId('categories_id')->nullable();
            $table->foreignId('actions_id')->nullable();
            $table->string('nom_pompe')->nullable();
            $table->string('lien_pompe')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
