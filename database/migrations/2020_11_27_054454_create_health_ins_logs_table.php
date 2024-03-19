<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHealthInsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('health_ins_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->string("target_id")->comment("고유번호");
            $table->string("proof_number")->comment("증번호")->nullable();
            $table->string("rsNo")->comment("주민등록번호");
            $table->string("name")->comment("성명");
            $table->string("monthly_price")->comment("보수월액")->nullable();
            $table->string("division")->comment("구분");
            $table->string("prod_insurance_price")->comment("산출보험료")->nullable();
            $table->string("cal_insurance_price")->comment("정산보험료")->nullable();
            $table->string("cal_reason")->comment("정산사유")->nullable();
            $table->string("cal_period")->comment("정산적용기간")->nullable();
            $table->string("reduction_reason")->comment("감면사유")->nullable();
            $table->string("year_end_tax")->comment("연말정산")->nullable();
            $table->string("refund_interest")->comment("환급금이자")->nullable();
            $table->string("notice_insurance_price")->comment("고지보험료");
            $table->string("accounting")->comment("회계")->nullable();
            $table->string("business_symbol")->comment("영업소기호")->nullable();
            $table->string("job_type")->comment("직종")->nullable();
            $table->date("gain_date")->comment("취득일/상실일")->nullable();
            $table->string("division2")->comment("구분2");
            $table->string("prod_insurance_price2")->comment("산출보험료2")->nullable();
            $table->string("cal_insurance_price2")->comment("정산보험료2")->nullable();
            $table->string("cal_reason2")->comment("정산사유2")->nullable();
            $table->string("cal_period2")->comment("정산적용기간2")->nullable();
            $table->string("reduction_reason2")->comment("감면사유2")->nullable();
            $table->string("year_end_tax2")->comment("연말정산2")->nullable();
            $table->string("refund_interest2")->comment("환급금이자2")->nullable();
            $table->string("notice_insurance_price2")->comment("고지보험료2");
            $table->string("accounting2")->comment("회계2")->nullable();
            $table->string("business_symbol2")->comment("영업소기호2")->nullable();
            $table->string("job_type2")->comment("직종2")->nullable();
            $table->date("loss_date")->comment("취득일/상실일")->nullable();

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
        Schema::dropIfExists('health_ins_logs');
    }
}
