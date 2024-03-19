<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTaxsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_taxs', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->foreignId("worker_id")->constrained("workers");
            $table->foreignId("payment_id")->constrained("payment_vouchers");
            $table->foreignId("condition_id")->constrained("payment_conditions");
            $table->date("target_ym")->nullable();
            $table->string("provider_key");
            $table->string("save_selector");
            $table->string("selected_payment");
            $table->string("tax_nation_pension")->comment("제공자 급여공제내역 국민연금")->nullable();
            $table->string("tax_health")->comment("제공자 급여공제내역 건강보험")->nullable();
            $table->string("tax_employ")->comment("제공자 급여공제내역 고용보험")->nullable();
            $table->string("tax_gabgeunse")->comment("제공자 급여공제내역 갑근세")->nullable();
            $table->string("tax_joominse")->comment("제공자 급여공제내역 주민세")->nullable();
            $table->string("tax_gunbo")->comment("제공자 급여공제내역 건보정산")->nullable();
            $table->string("tax_yearly")->comment("제공자 급여공제내역 연말정산")->nullable();
            $table->string("tax_bad_income")->comment("제공자 급여공제내역 부정수급환수")->nullable();
            $table->string("tax_etc_1")->comment("제공자 급여공제내역 기타공제1")->nullable();
            $table->string("tax_etc_2")->comment("제공자 급여공제내역 기타공제2")->nullable();
            $table->string("tax_total")->comment("제공자 급여공제내역 공제합계")->nullable();
            $table->string("tax_sub_payment")->comment("차인지급액(세후. 바우처상지급합계-공제합계)")->nullable();
            $table->string("bank_name")->comment("은행명")->nullable();
            $table->string("bank_number")->comment("계좌번호")->nullable();
            $table->string("company_income")->comment("사업주 사업수입")->nullable();
            $table->string("tax_company_nation")->comment("사업주 국민연금")->nullable();
            $table->string("tax_company_health")->comment("사업주 건강보험")->nullable();
            $table->string("tax_company_employ")->comment("사업주 고용보험")->nullable();
            $table->string("tax_company_industry")->comment("사업주 산재보험")->nullable();
            $table->string("tax_company_retirement")->comment("사업주 퇴직연금")->nullable();
            $table->string("tax_company_return_confirm")->comment("사업주 반납승인(사업주)")->nullable();
            $table->string("tax_company_tax_total")->comment("사업주 부담합계")->nullable();
            $table->string("company_payment_result")->comment("사업주 차감사업주 수익")->nullable();

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
        Schema::dropIfExists('payment_taxs');
    }
}
