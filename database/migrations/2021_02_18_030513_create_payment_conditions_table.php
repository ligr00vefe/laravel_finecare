<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_conditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->date("target_ym")->comment("급여기준연월");
            $table->string("public_officers_holiday_check")->comment("관공서공휴일적용여뷰");
            $table->string("pay_per_hour")->comment("시간당인건비단가");
            $table->string("pay_hour")->comment("기본시급");
            $table->string("pay_over_time")->comment("연장수당(%)");
            $table->string("pay_holiday")->comment("휴일수당(%)");
            $table->string("pay_holiday_over_time")->comment("휴일연장수당(%)");
            $table->string("pay_night")->comment("야간수당(%)");
            $table->string("pay_annual")->comment("연차수당(%)");
            $table->string("pay_one_year_less_annual")->comment("1년 미만자 연차수당(%)");
            $table->string("pay_public_holiday")->comment("공휴일의 유급휴일임금(%)");
            $table->string("pay_workers_day")->comment("근로자의 날 수당(%)");

            $table->string("week_pay_apply_check")->comment("주휴수당체크여부");
            $table->string("week_pay_apply_type")->comment("주휴수당적용");
            $table->string("week_pay_selector")->comment("주휴수당주설정");

            $table->string("year_pay_apply_check")->comment("연차수당체크여부");
            $table->string("year_pay_apply_type")->comment("연차수당적용");
            $table->string("year_pay_selector")->comment("연차수당주설정");

            $table->string("one_year_less_annual_pay_check")->comment("1년미만자연차수당체크여부");
            $table->string("one_year_less_annual_pay_type")->comment("1년미만자연차수당적용");
            $table->string("one_year_less_annual_pay_selector")->comment("1년미만자연차수당주설정");

            $table->string("public_allowance_check")->comment("공휴일의 유급휴일임금 계산");
            $table->string("public_allowance_selector")->comment("공휴일의 유급휴일임금 수당적용");
            $table->string("public_allowance_day_selector")->comment("공휴일의 유급휴일임금 수당 주 설정");

            $table->string("workers_day_allowance_check")->comment("근로자의 날 수당 계산");
            $table->string("workers_day_allowance_day_selector")->comment("근로자의 날 수당 계산 주 설정");

            $table->string("voucher_pay_total")->comment("바우처상 지급합계선택");
            $table->string("voucher_holiday_pay_fixing")->comment("휴일수당 고정값 입력");
            $table->string("voucher_holiday_pay_hour_per_price")->comment("휴일수당 비례값 시간당 인건비 단가 1.5배");

            $table->string("retirement_saving_pay_type")->comment("퇴직적립금 적립방식");
            $table->string("retirement_saving_pay_company_percentage")->comment("퇴직적립금 적립방식 사업장적립요율선택했을때 값");

            $table->string("tax_nation_selector")->comment("사회보험계산방식 비율, 금액");
            $table->string("tax_health_selector")->comment("사회보험계산방식 비율, 금액");
            $table->string("tax_employ_selector")->comment("사회보험계산방식 비율, 금액");
            $table->string("tax_industry_selector")->comment("사회보험계산방식 비율, 금액");
            $table->string("tax_gabgeunse_selector")->comment("사회보험계산방식 비율, 금액");

            $table->string("employ_tax_selector")->comment("고용보험료율");
            $table->string("industry_tax_percentage")->comment("산재요율");


            $table->tinyInteger("timetable_1")->default(1)->comment("공휴일지급적용1");
            $table->tinyInteger("timetable_2")->default(1)->comment("공휴일지급적용2");
            $table->tinyInteger("timetable_3")->default(1)->comment("공휴일지급적용3");
            $table->tinyInteger("timetable_4")->default(1)->comment("공휴일지급적용4");


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
        Schema::dropIfExists('payment_conditions');
    }
}
