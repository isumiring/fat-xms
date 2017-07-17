<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->default('0');
            $table->string('title');
            $table->text('teaser')->nullable();
            $table->longText('description')->nullable();
            $table->enum('type', ['static_page', 'module', 'external_link'])->default('static_page');
            $table->string('slug_url')->nullable();
            $table->string('module')->nullable();
            $table->string('ext_link')->nullable();
            $table->string('primary_image')->nullable();
            $table->string('thumbnail_image')->nullable();
            $table->string('background_image')->nullable();
            $table->string('icon_image')->nullable();
            $table->smallInteger('position')->default('1');
            $table->tinyInteger('is_published')->default('0');
            $table->tinyInteger('is_featured')->default('0');
            $table->tinyInteger('is_header')->default('0');
            $table->tinyInteger('is_footer')->default('0');
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
        Schema::dropIfExists('pages');
    }
}

