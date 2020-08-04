<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('title');
            $table->string('url');
            $table->text('description')->nullable();
            $table->enum('is_premium', ['yes', 'no']);
            $table->longText('content');
            $table->string('meta_title');
            $table->string('meta_description')->nullable();
            $table->string('banner')->nullable();
            $table->string('banner_url')->nullable();
            $table->string('thumb')->nullable();
            $table->string('thumb_url')->nullable();
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
        Schema::dropIfExists('articles');
    }
}
