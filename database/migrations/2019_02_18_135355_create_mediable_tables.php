<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediableTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->increments('id');
            $table->string('disk', 32)->nullable();
            $table->string('directory')->nullable();
            $table->string('filename');
            $table->string('extension', 32);
            $table->string('mime_type', 128)->nullable();
            $table->json('attributes')->nullable();
            $table->string('video_id')->nullable();
            $table->string('video_url')->nullable();
            $table->string('video_thumbnail')->nullable();
            $table->string('aggregate_type', 32);
            $table->integer('size')->unsigned();
            $table->boolean('active')->default(1);
            $table->timestamps();

            $table->index(['disk', 'directory']);
            $table->unique(['disk', 'directory', 'filename', 'extension']);
            $table->index('aggregate_type');
        });

        Schema::create('mediables', function (Blueprint $table) {
            $table->integer('media_id')->unsigned();
            $table->string('mediable_type');
            $table->integer('mediable_id')->unsigned();
            $table->string('tag');
            $table->integer('order')->unsigned();

            $table->primary(['media_id', 'mediable_type', 'mediable_id', 'tag']);
            $table->index(['mediable_id', 'mediable_type']);
            $table->index('tag');
            $table->index('order');
            $table->foreign('media_id')->references('id')->on('media')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('mediables');
        Schema::drop('media');
    }
}
