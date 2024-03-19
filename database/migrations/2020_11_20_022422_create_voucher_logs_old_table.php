<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoucherLogsOldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voucher_logs_old', function (Blueprint $table) {

            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->string("target_key");
            $table->string("target_name")->comment("대상자명");
            $table->string("target_birth")->comment("생년월일")->nullable();
            $table->string("target_id")->comment("대상자ID")->nullable();
            $table->string("city_code")->comment("시/도코드")->nullable();
            $table->string("city_name")->comment("시/도명")->nullable();
            $table->string("sigungu_code")->comment("시/군/구코드")->nullable();
            $table->string("sigungu_name")->comment("시/군/구명")->nullable();
            $table->string("target_grade")->comment("대상자등급")->nullable();
            $table->string("provider_key")->comment("제공인력키")->nullable();
            $table->string("provider_name")->comment("제공인력명")->nullable();
            $table->string("provider_birth")->comment("제공인력 생년월일인듯")->nullable();
            $table->string("payment_type")->comment("결제유형")->nullable();
            $table->string("device_model")->comment("단말기모델")->nullable();
            $table->string("cat_id")->comment("cat_id라는게 엑셀에 있음")->nullable();
            $table->string("payment_phone_info")->comment("결제폰정보")->nullable();
            $table->string("serial_number")->comment("시리얼번호")->nullable();
            $table->datetime("payment_datetime")->comment("결제일시")->nullable();
            $table->string("confirm_number")->comment("승인번호")->nullable();
            $table->string("target_ym")->comment("대상년월")->nullable();
            $table->string("confirm_price")->comment("승인금액")->nullable();
            $table->string("government_support_total")->comment("정부지원금 합계")->nullable();
            $table->string("personal_charge_total")->comment("본인부담금 합계")->nullable();
            $table->string("basic_pay_government_support")->comment("기본급여 정부지원금")->nullable();
            $table->string("basic_pay_personal_charge")->comment("기본급여 본인부담금")->nullable();
            $table->string("add_pay_government_support")->comment("추가급여 정부지원금")->nullable();
            $table->string("add_pay_personal_charge")->comment("추가급여 본인부담금")->nullable();
            $table->string("payment_division")->comment("결제구분")->nullable();
            $table->string("payment_personnel")->comment("결제인원")->nullable();
            $table->string("business_division")->comment("사업구분")->nullable();
            $table->string("business_type")->comment("사업유형")->nullable();
            $table->string("service_type")->comment("서비스 유형")->nullable();
            $table->string("start_date_mdhi")->comment("시작시간 월/일/분")->nullable();
            $table->string("end_date_mdhi")->comment("종료시간 월/일/분")->nullable();
            $table->string("service_provide_time_total")->comment("서비스 제공시간 합계")->nullable();
            $table->string("social_activity_support")->comment("사회활동지원")->nullable();
            $table->string("physical_activity_support")->comment("신체활동지원")->nullable();
            $table->string("housekeeping_activity_support")->comment("가사활동지원")->nullable();
            $table->string("etc_service")->comment("기타서비스")->nullable();
            $table->string("car_bath")->comment("차량내입욕")->nullable();
            $table->string("home_bath")->comment("가정내입욕")->nullable();
            $table->string("basic_care")->comment("기본간호")->nullable();
            $table->string("cure_care")->comment("치료간호")->nullable();
            $table->string("edu_counseling")->comment("교육상담")->nullable();
            $table->string("visit_care_order")->comment("방문간호지시서")->nullable();
            $table->string("batch_payment_reason")->comment("일괄결제(사유)")->nullable();
            $table->string("payment_date")->comment("지급일자")->nullable();
            $table->string("return_sort")->comment("반납구분")->nullable();
            $table->string("return_confirm_date")->comment("반납승인일자")->nullable();
            $table->string("addition_check")->comment("가산여부")->nullable();
            $table->string("add_price")->comment("가산금액")->nullable();
            $table->string("payment_hold_log")->comment("지급보류내역")->nullable();

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
        Schema::dropIfExists('voucher_logs_old');
    }
}
