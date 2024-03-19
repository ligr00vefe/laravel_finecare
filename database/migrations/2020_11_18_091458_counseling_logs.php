<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CounselingLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('counseling_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->string("type")->comment("member인지 worker인지");
            $table->bigInteger("target_id")->comment("대상id");
            $table->string("writer")->comment("작성자");
            $table->string("category")->comment("분류");
            $table->string("way")->comment("상담방법");
            $table->date("from_date")->comment("시작일시날짜");
            $table->time("from_date_time")->comment("시작일시시간");
            $table->date("to_date")->comment("종료일시날짜");
            $table->time("to_date_time")->comment("종료일시시간");
            $table->string("title")->comment("제목");
            $table->text("content")->comment("내용");
            $table->text("result")->comment("상담결과");

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
        //
    }
}
