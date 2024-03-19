<?php
//
//use Illuminate\Database\Migrations\Migration;
//use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Support\Facades\Schema;
//
//class CreatePaymentsTable extends Migration
//{
//    /**
//     * Run the migrations.
//     *
//     * @return void
//     */
//    public function up()
//    {
//        Schema::create('payments', function (Blueprint $table) {
//            $table->id();
//            $table->foreignId("user_id")->constrained("users");
//            $table->foreignId("worker_id")->constrained("workers");
//            $table->string("nation_day_count")->comment("바우처국비근무일수")->nullable();
//            $table->string("nation_confirm_payment")->comment("바우처국비승인금액")->nullable();
//            $table->string("nation_add_payment")->comment("바우처국비가산금")->nullable();
//            $table->string("nation_total_time")->comment("바우처국비 총 시간")->nullable();
//            $table->string("nation_holiday_time")->comment("바우처국비휴일시간")->nullable();
//            $table->string("nation_night_time")->comment("바우처국비야간시간")->nullable();
//            $table->string("city_day_count")->comment("바우처시도비근무일수")->nullable();
//            $table->string("city_confirm_payment")->comment("바우처시도비승인금액")->nullable();
//            $table->string("city_add_payment")->comment("바우처시도비가산금")->nullable();
//            $table->string("city_total_time")->comment("바우처시도비총시간")->nullable();
//            $table->string("city_holiday_time")->comment("바우처시도비휴일시간")->nullable();
//            $table->string("city_night_time")->comment("바우처시도비야간시간")->nullable();
//            $table->string("voucher_total_day_count")->comment("바우처합계근무일수")->nullable();
//            $table->string("voucher_total_confirm_payment")->comment("바우처합계승인금액")->nullable();
//            $table->string("voucher_total_confirm_payment_add")->comment("바우처합계가산금")->nullable();
//            $table->string("voucher_total_time")->comment("바우처합계총시간")->nullable();
//            $table->string("voucher_total_time_holiday")->comment("바우처합계휴일시간")->nullable();
//            $table->string("voucher_total_time_night")->comment("바우처합계야간시간")->nullable();
//            $table->string("voucher_detach_payment_basic")->comment("바우처금액분리기본급")->nullable();
//            $table->string("voucher_detach_payment_holiday")->comment("바우처금액분리휴일금액")->nullable();
//            $table->string("voucher_detach_payment_night")->comment("바우처금액분리야간금액")->nullable();
//            $table->string("voucher_detach_payment_difference")->comment("바우처금액분리승인금액차")->nullable();
//
//            $table->string("voucher_etc_charge_city_time")->comment("기타청구시군구비시간")->nullable();
//            $table->string("voucher_etc_charge_city_pay")->comment("기타청구시군구비총금액")->nullable();
//            $table->string("voucher_etc_charge_except_time")->comment("기타청구예외청구시간")->nullable();
//            $table->string("voucher_etc_charge_except_pay")->comment("기타청구예외청구총금액")->nullable();
//            $table->string("voucher_etc_charge_total_time")->comment("기타청구합계시간")->nullable();
//            $table->string("voucher_etc_charge_total_pay")->comment("기타청구합계총금액")->nullable();
//
//            $table->string("voucher_return_nation_day_count")->comment("바우처국비반납근무일수")->nullable();
//            $table->string("voucher_return_nation_pay")->comment("바우처국비반납승인금액")->nullable();
//            $table->string("voucher_return_nation_time")->comment("바우처국비반납총시간")->nullable();
//            $table->string("voucher_return_city_day_count")->comment("바우처시도비반납근무일수")->nullable();
//            $table->string("voucher_return_city_pay")->comment("바우처반납시도비승인금액")->nullable();
//            $table->string("voucher_return_city_time")->comment("바우처시도비반납총시간")->nullable();
//            $table->string("voucher_return_total_day_count")->comment("바우처합계반납근무일수")->nullable();
//            $table->string("voucher_return_total_pay")->comment("바우처반납합계승인금액")->nullable();
//            $table->string("voucher_return_total_time")->comment("바우처합계반납총시간")->nullable();
//
//            $table->string("voucher_payment")->comment("바우처상지급합계")->nullable();
//
//            $table->string("standard_basic_time")->comment("근로기준기본급시간")->nullable();
//            $table->string("standard_basic_payment")->comment("근로기준기본급금액")->nullable();
//            $table->string("standard_over_time")->comment("근로기준연장시간")->nullable();
//            $table->string("standard_over_payment")->comment("근로기준연장금액")->nullable();
//            $table->string("standard_holiday_time")->comment("근로기준휴일시간")->nullable();
//            $table->string("standard_holiday_payment")->comment("근로기준휴일금액")->nullable();
//            $table->string("standard_night_time")->comment("근로기준야간시간")->nullable();
//            $table->string("standard_night_payment")->comment("근로기준야간금액")->nullable();
//            $table->string("standard_weekly_time")->comment("근로기준주휴수당시간")->nullable();
//            $table->string("standard_weekly_payment")->comment("근로기준주휴수당금액")->nullable();
//            $table->string("standard_yearly_time")->comment("근로기준연차수당시간")->nullable();
//            $table->string("standard_yearly_payment")->comment("근로기준연차수당금액")->nullable();
//            $table->string("standard_workers_day_time")->comment("근로기준근로자의날수당시간")->nullable();
//            $table->string("standard_workers_day_payment")->comment("근로기준근로자의날수당금액")->nullable();
//            $table->string("standard_public_day_time")->comment("근로기준공휴일유급휴일수당시간")->nullable();
//            $table->string("standard_public_day_payment")->comment("근로기준공휴일유급휴일수당금액")->nullable();
//            $table->string("standard_payment")->comment("근로기준적용합계")->nullable();
//
//            $table->string("voucher_sub_standard_payment")->comment("바우처지급합계-근로기준적용합계=법정제수당(차액)")->nullable();
//            $table->string("standard_bojeon")->comment("보전수당(수기입력)")->nullable();
//            $table->string("standard_jaboodam")->comment("자부담급여(수기입력)")->nullable();
//            $table->string("standard_jaesoodang")->comment("법정제수당(수기입력)")->nullable();
//            $table->string("standard_bannap")->comment("반납추가(수기입력)")->nullable();
//            $table->string("voucher_payment2")->comment("지급총액(바우처상지급합계. 안헷깔리게 한번 더 넣음)")->nullable();
//            $table->string("tax_nation_pension")->comment("제공자 급여공제내역 국민연금")->nullable();
//            $table->string("tax_health")->comment("제공자 급여공제내역 건강보험")->nullable();
//            $table->string("tax_employ")->comment("제공자 급여공제내역 고용보험")->nullable();
//            $table->string("tax_gabgeunse")->comment("제공자 급여공제내역 갑근세")->nullable();
//            $table->string("tax_joominse")->comment("제공자 급여공제내역 주민세")->nullable();
//            $table->string("tax_gunbo")->comment("제공자 급여공제내역 건보정산")->nullable();
//            $table->string("tax_yearly")->comment("제공자 급여공제내역 연말정산")->nullable();
//            $table->string("tax_bad_income")->comment("제공자 급여공제내역 부정수급환수")->nullable();
//            $table->string("tax_etc_1")->comment("제공자 급여공제내역 기타공제1")->nullable();
//            $table->string("tax_etc_2")->comment("제공자 급여공제내역 기타공제2")->nullable();
//            $table->string("tax_total")->comment("제공자 급여공제내역 공제합계")->nullable();
//            $table->string("tax_sub_payment")->comment("차인지급액(세후. 바우처상지급합계-공제합계)")->nullable();
//            $table->string("bank_name")->comment("은행명")->nullable();
//            $table->string("bank_number")->comment("계좌번호")->nullable();
//            $table->string("company_income")->comment("사업주 사업수입")->nullable();
//            $table->string("tax_company_nation")->comment("사업주 국민연금")->nullable();
//            $table->string("tax_company_health")->comment("사업주 건강보험")->nullable();
//            $table->string("tax_company_employ")->comment("사업주 고용보험")->nullable();
//            $table->string("tax_company_industry")->comment("사업주 산재보험")->nullable();
//            $table->string("tax_company_retirement")->comment("사업주 퇴직연금")->nullable();
//            $table->string("tax_company_return_confirm")->comment("사업주 반납승인(사업주)")->nullable();
//            $table->string("tax_company_tax_total")->comment("사업주 부담합계")->nullable();
//            $table->string("company_payment_result")->comment("사업주 차감사업주 수익")->nullable();
//            $table->timestamp('created_at')->useCurrent();
//            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
//        });
//    }
//
//    /**
//     * Reverse the migrations.
//     *
//     * @return void
//     */
//    public function down()
//    {
//        Schema::dropIfExists('payments');
//    }
//}
