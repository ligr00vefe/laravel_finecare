<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEdiIndustryInsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edi_industry_ins_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->string("target_id");
            $table->string("worker_type")->comment("근로자구분");
            $table->string("name")->comment("근로자명");
            $table->string("birth")->comment("생년월일");
            $table->string("worker_original_number")->comment("근로자원부번호");
            $table->date("employ_start_date")->comment("고용일");
            $table->date("employ_end_date")->comment("고용종료일");
            $table->string("leave_worker_average")->comment("휴직자월평");
            $table->string("monthly_bosu_average_price")->comment("월평균보수액");
            $table->string("cal_ins_price")->comment("산정보험료");
            $table->string("re_cal_ins_price")->comment("재산정보험료");
            $table->string("acc_bosu_total_price")->comment("정산보수총액");
            $table->string("acc_ins_price")->comment("정산보험료");
            $table->string("total_ins_price")->comment("보험료합계");
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
        Schema::dropIfExists('edi_industry_ins_logs');
    }
}
