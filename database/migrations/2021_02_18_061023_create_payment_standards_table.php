<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentStandardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_standards', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->date("target_ym")->nullable();
            $table->string("provider_key");
            $table->foreignId("condition_id")->constrained("payment_conditions");

            $table->string("standard_basic_time")->comment("근로기준기본급시간")->nullable();
            $table->string("standard_basic_payment")->comment("근로기준기본급금액")->nullable();
            $table->string("standard_over_time")->comment("근로기준연장시간")->nullable();
            $table->string("standard_over_payment")->comment("근로기준연장금액")->nullable();
            $table->string("standard_holiday_time")->comment("근로기준휴일시간")->nullable();
            $table->string("standard_holiday_payment")->comment("근로기준휴일금액")->nullable();
            $table->string("standard_night_time")->comment("근로기준야간시간")->nullable();
            $table->string("standard_night_payment")->comment("근로기준야간금액")->nullable();
            $table->string("standard_weekly_time")->comment("근로기준주휴수당시간")->nullable();
            $table->string("standard_weekly_payment")->comment("근로기준주휴수당금액")->nullable();
            $table->string("standard_yearly_time")->comment("근로기준연차수당시간")->nullable();
            $table->string("standard_yearly_payment")->comment("근로기준연차수당금액")->nullable();
            $table->string("standard_workers_day_time")->comment("근로기준근로자의날수당시간")->nullable();
            $table->string("standard_workers_day_payment")->comment("근로기준근로자의날수당금액")->nullable();
            $table->string("standard_public_day_time")->comment("근로기준공휴일유급휴일수당시간")->nullable();
            $table->string("standard_public_day_payment")->comment("근로기준공휴일유급휴일수당금액")->nullable();
            $table->string("standard_payment")->comment("근로기준적용합계")->nullable();

            $table->string("voucher_sub_standard_payment")->comment("바우처지급합계-근로기준적용합계=법정제수당(차액)")->nullable();
            $table->string("standard_bojeon")->comment("보전수당(수기입력)")->nullable();
            $table->string("standard_jaboodam")->comment("자부담급여(수기입력)")->nullable();
            $table->string("standard_jaesoodang")->comment("법정제수당(수기입력)")->nullable();
            $table->string("standard_bannap")->comment("반납추가(수기입력)")->nullable();
            $table->string("voucher_payment")->comment("지급총액(바우처상지급합계)")->nullable();

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
        Schema::dropIfExists('payment_standards');
    }
}
