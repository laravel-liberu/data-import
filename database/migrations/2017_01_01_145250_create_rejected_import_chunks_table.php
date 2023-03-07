<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('rejected_import_chunks', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('import_id')->unsigned();
            $table->foreign('import_id')->references('id')->on('data_imports')
                ->onDelete('cascade');

            $table->string('sheet');
            $table->json('header');
            $table->json('rows');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rejected_import_chunks');
    }
};
