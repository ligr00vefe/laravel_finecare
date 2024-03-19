<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEdiHealthInsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edi_health_ins_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->date("target_ym")->comment("고지년월");
            $table->string("business_license")->comment("사업장관리번호");
            $table->string("unit_office")->comment("단위사업장(단위기관)");
            $table->string("high_order_number")->comment("고지차수");
            $table->string("accounting")->comment("회계");
            $table->string("proof_number")->comment("증번호");
            $table->string("name")->comment("성명");
            $table->string("rsNo")->comment("주민등록번호");
            $table->string("target_id")->comment("타겟아이디");
            $table->string("reduction_reason")->comment("감면사유");
            $table->string("job_type")->comment("직종");
            $table->string("grade")->comment("등급");
            $table->string("monthly_bosu_price")->comment("보수월액");
            $table->string("cal_ins_price")->comment("산출보험료");
            $table->string("account_reason")->comment("정산사유");
            $table->date("start_date")->comment("시작월");
            $table->date("end_date")->comment("종료월");
            $table->string("account_price")->comment("정산금액");
            $table->string("notice_price")->comment("고지금액");
            $table->string("year_end_tax")->comment("연말정산");
            $table->string("gave_date")->comment("취득일");
            $table->string("lose_date")->comment("상실일");
            $table->string("recup_cal_ins_price")->comment("요양산출보험료");
            $table->string("recup_acc_reason_code")->comment("요양정산사유코드");
            $table->string("recup_start_date")->comment("요양시작월");
            $table->string("recup_end_date")->comment("요양종료월");
            $table->string("recup_acc_ins_price")->comment("요양정산보험료");
            $table->string("recup_notice_ins_price")->comment("요양고지보험료");
            $table->string("recup_year_end_tax_ins_price")->comment("요양연말정산보험료");
            $table->string("total_cal_ins_price")->comment("산출보험료계");
            $table->string("total_acc_ins_price")->comment("정산보험료계");
            $table->string("total_notice_ins_price")->comment("고지보험료계");
            $table->string("total_year_end_ins_price")->comment("연말정산보험료계");
            $table->string("health_return_price_interest")->comment("건강환급금이자");
            $table->string("recup_return_price_interest")->comment("요양환급금이자");
            $table->string("user_total_ins_price")->comment("가입자총납부할보험료");
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
        Schema::dropIfExists('edi_health_ins_logs');
    }
}
