<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDayOffRecalculateRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('day_off_recalculate_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->string("provider_key");
            $table->date("join_date")->comment("가입일");
            $table->date("resign_date")->comment("퇴사일");
            $table->year("year")->comment("연도");
            $table->date("target_ym")->comment("년 검색");
            $table->string("standard")->comment("계산기준. 입사일기준 or 회계연도 기준");
            $table->date("year_standard_date")->comment("회계연도기준일때 회계일자");
            $table->integer("use_off_day")->comment("사용 연차 수");
            $table->integer("total_off_day")->comment("총 연차 수");
            $table->integer("off_day_price_daily")->comment("연차수당 하루치");
            $table->integer("off_day_price_total")->comment("연차수당");
            $table->tinyInteger("less_than_one_year")->comment("1년미만인지체크")->default(0);
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
        Schema::dropIfExists('day_off_recalculate_records');
    }
}
