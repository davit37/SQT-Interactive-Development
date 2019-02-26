<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatgoryContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catgory_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('project_category_id')->unsigned();
            $table->timestamps();

            $table->foreign('project_category_id')->references('id')->on('project_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catgory_contents');
    }
}
