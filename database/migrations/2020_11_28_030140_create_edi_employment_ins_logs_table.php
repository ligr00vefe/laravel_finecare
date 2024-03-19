<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEdiEmploymentInsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edi_employment_ins_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->string("target_id");
            $table->string("worker_type")->comment("근로자구분");
            $table->string("name")->comment("근로자명");
            $table->string("birth")->comment("생년월일");
            $table->string("worker_original_number")->comment("근로자원부번호")->nullable();
            $table->date("employ_start_date")->comment("고용일")->nullable();
            $table->date("employ_end_date")->comment("고용종료일")->nullable();
            $table->string("leave_worker_average")->comment("휴직자월평")->nullable();
            $table->string("monthly_bosu_average_price")->comment("월평균보수액")->nullable();
            $table->string("cal_worker_unemploy_benefit")->comment("산정보험료근로자실업급여보험료")->nullable();
            $table->string("cal_owner_unemploy_benefit")->comment("산정보험료사업주실업급여보험료")->nullable();
            $table->string("cal_owner_goan_ins_price")->comment("산정보험료사업주고안직능보험료")->nullable();
            $table->string("re_cal_worker_unemploy_benefit")->comment("재산정보험료근로자실업급여보험료")->nullable();
            $table->string("re_cal_owner_unemploy_benefit")->comment("재산정보험료사업주실업급여보험료")->nullable();
            $table->string("re_cal_owner_goan_ins_price")->comment("재산정보험료사업주고안직능보험료")->nullable();
            $table->string("acc_bosu_total_price")->comment("정산보수총액")->nullable();
            $table->string("acc_worker_unemploy_benefit")->comment("정산보험료근로자실업급여보험료")->nullable();
            $table->string("acc_owner_unemploy_benefit")->comment("정산보험료사업주실업급여보험료")->nullable();
            $table->string("acc_owner_goan_ins_price")->comment("정산보험료사업주고안직능보험료")->nullable();
            $table->string("total_worker_unemploy_benefit")->comment("보험료합계근로자실업급여보험료")->nullable();
            $table->string("total_owner_unemploy_benefit")->comment("보험료합계사업주실업급여보험료")->nullable();
            $table->string("total_owner_goan_ins_price")->comment("보험료합계사업주고안직능보험료")->nullable();

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
        Schema::dropIfExists('edi_employment_ins_logs');
    }
}
