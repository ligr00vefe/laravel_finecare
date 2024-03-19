<?php
//
//use Illuminate\Database\Migrations\Migration;
//use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Support\Facades\Schema;
//
//class CreatePaymentReturnsTable extends Migration
//{
//    /**
//     * Run the migrations.
//     *
//     * @return void
//     */
//    public function up()
//    {   // payment_vouchers에 포함되어 있으므로 쓰지말자
//        Schema::create('payment_returns', function (Blueprint $table) {
//            $table->id();
//            $table->foreignId("user_id")->constrained("users");
//            $table->foreignId("payment_id")->constrained("payment_vouchers");
//            $table->foreignId("condition_id")->constrained("payment_conditions");
//            $table->date("target_ym")->nullable();
//            $table->string("provider_key");
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
//        Schema::dropIfExists('payment_returns');
//    }
//}
