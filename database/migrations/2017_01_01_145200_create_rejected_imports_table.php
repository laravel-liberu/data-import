<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rejected_imports', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('file_id')->nullable()->unique();
            $table->foreign('file_id')->references('id')->on('files')
                ->onUpdate('restrict')->onDelete('restrict');

            $table->unsignedBigInteger('import_id');
            $table->foreign('import_id')->references('id')->on('data_imports')
                ->onDelete('restrict');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rejected_imports');
    }
};
