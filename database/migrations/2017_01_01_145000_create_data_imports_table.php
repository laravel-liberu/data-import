<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('data_imports', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('file_id')->nullable()->unique();
            $table->foreign('file_id')->references('id')->on('files')
                ->onUpdate('restrict')->onDelete('restrict');

            $table->string('batch')->nullable();
            $table->foreign('batch')->references('id')->on('job_batches')
                ->onUpdate('restrict')->onDelete('restrict');

            $table->string('type')->index();

            $table->json('params')->nullable();

            $table->integer('successful');
            $table->integer('failed');

            $table->tinyInteger('status');

            $table->foreignId('created_by')->nullable()->constrained('users')->index()->name('data_imports_created_by_foreign');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('data_imports');
    }
};
