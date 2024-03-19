<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHelperDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('helper_details', function (Blueprint $table) {
            $table->id();
            $table->string("register_check")->comment("등록구분");
            $table->string("name")->comment("제공인력명");
            $table->string("birth")->comment("생년월일");
            $table->string("target_key")->comment("활동지원사 키 (엑셀양식엔 없음)");
            $table->string("business_division")->comment("사업구분");
            $table->string("business_type")->comment("사업유형");
            $table->string("payment_price")->comment("현재결제금액");
            $table->string("moment_payment_price")->comment("등록시점결제금액");
            $table->string("work_time")->comment("실근무시간");
            $table->string("add_basic_pay")->comment("기본급여(A)");
            $table->string("add_week_pay")->comment("주휴수당(B)");
            $table->string("add_year_pay")->comment("연차수당(C)");
            $table->string("etc_pay")->comment("기타(D)");
            $table->string("ins_business_assign")->comment("4대 보험료 사업자 분담금");
            $table->string("retire_plus_price")->comment("퇴직충당금");
            $table->string("monthly_payment")->comment("월급여(A+B+C+D)");
            $table->string("work_time_day")->comment("근무시간(일수)");
            $table->string("time_per_price")->comment("시간(일)단가");
            $table->string("ins_check")->comment("4대보험미가입");
            $table->string("national_ins_check")->comment("국민연금");
            $table->string("health_ins_check")->comment("건강보험");
            $table->string("employ_ins_check")->comment("고용보험");
            $table->string("industry_ins_check")->comment("산재보험");
            $table->string("baesang_ins_check")->comment("배상보험");
            $table->string("retire_added_check")->comment("퇴직적립");
            $table->string("qualification_status")->comment("자격상태");
            $table->string("target_id")->comment("제공인력ID");
            $table->string("business_division_code")->comment("사업자구분코드");
            $table->string("business_type_code")->comment("사업자유형코드");
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
        Schema::dropIfExists('helper_details');
    }
}
