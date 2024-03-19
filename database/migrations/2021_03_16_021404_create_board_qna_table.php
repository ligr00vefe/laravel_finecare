<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoardQnaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('board_qna', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained();
            $table->string("category");
            $table->string("subject");
            $table->string("content")->nullable();
            $table->string("file1name")->nullable();
            $table->string("file1path")->nullable();
            $table->string("file2name")->nullable();
            $table->string("file2path")->nullable();
            $table->tinyInteger("answerCheck")->nullable();
            $table->tinyInteger("deleteCheck")->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('board_qna');
    }
}
