<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class VoucherLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("voucher_logs", function (Blueprint $table) {
            $table->id();

            $table->foreignId("user_id")->constrained("users");
            $table->string("target_key");
            $table->string("target_name");
            $table->string("target_birth")->nullable()->nullable();
            $table->string("grade")->comment("등급")->nullable();
            $table->string("provider_key")->comment("제공인력키")->nullable();
            $table->string("provider_name")->comment("제공인력명")->nullable();
            $table->string("provider_birth")->comment("제공인력생년월일")->nullable();
            $table->string("address")->comment("시/군/구")->nullable();
            $table->string("business_type_id")->comment("사업유형ID")->nullable();
            $table->string("business_type")->comment("사업유형")->nullable();
            $table->string("service_type")->comment("서비스유형")->nullable();
            $table->date("confirm_date")->comment("승인일시")->nullable();
            $table->string("confirm_number")->comment("승인번호")->nullable();
            $table->date("target_ym")->comment("대상년월")->nullable();
            $table->string("confirm_pay")->comment("승인금액")->nullable();
            $table->string("government_support_pay_total")->comment("정부지원금합계")->nullable();
            $table->string("personal_charge_total")->comment("본인부담금합계")->nullable();
            $table->string("basic_payment_government_support")->comment("기본급여 정부지원금")->nullable();
            $table->string("basic_payment_personal_charge")->comment("기본급여 본인부담금")->nullable();
            $table->string("add_payment_government_support")->comment("추가급여 정부지원금")->nullable();
            $table->string("add_payment_personal_charge")->comment("추가급여 본인부담금")->nullable();
            $table->datetime("service_start_date_time")->comment("서비스 시작시간")->nullable();
            $table->datetime("service_end_date_time")->comment("서비스 종료시간")->nullable();
            $table->string("payment_time")->comment("결제시간")->nullable();
            $table->string("payment_personnel")->comment("결제인원")->nullable();
            $table->string("payment_type")->comment("결제구분")->nullable();
            $table->string("retroactive_reason")->comment("소급결제사유")->nullable();
            $table->string("payment_way")->comment("결제방식")->nullable();
            $table->datetime("payment_date")->comment("지급일자")->nullable();
            $table->string("return_sort")->comment("반납구분")->nullable();
            $table->datetime("return_confirm_date")->comment("반납승인일자")->nullable();
            $table->string("direct_confirm_check")->comment("직접반납여부")->nullable();
            $table->time("social_activity_support")->comment("사회활동지원")->nullable();
            $table->time("physical_activity_support")->comment("신체활동지원")->nullable();
            $table->time("housekeeping_activity_support")->comment("가사활동지원")->nullable();
            $table->time("etc_service")->comment("기타서비스")->nullable();
            $table->string("car_bath")->comment("차량내입욕")->nullable();
            $table->string("home_bath")->comment("가정내입욕")->nullable();
            $table->string("basic_care")->comment("기본간호")->nullable();
            $table->string("cure_care")->comment("치료간호")->nullable();
            $table->string("edu_counseling")->comment("교육상담")->nullable();
            $table->string("visit_care_order")->comment("방문간호지시서")->nullable();
            $table->integer("add_price")->comment("가산금액")->nullable();
            $table->string("provision_hold_log")->comment("지급보류내역")->nullable();

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
