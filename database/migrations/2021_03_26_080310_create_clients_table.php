<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained();
            $table->string("name")->comment("대상자명");
            $table->string("rsNo")->comment("생년월일(주민번호)");
            $table->string("target_id")->comment("대상자ID (바우처id인듯? 사이트에선 사용안함)");
            $table->string("business_type")->comment("국비, 시도비 타입");
            $table->string("grade")->comment("등급명");
            $table->string("sigungu_name")->comment("대상자시군구명");
            $table->string("tel")->comment("연락처");
            $table->string("phone")->comment("휴대전화");
            $table->string("contract_status")->comment("계약상태");
            $table->string("contract_start_date")->comment("계약시작일자");
            $table->string("contract_end_date")->comment("계약종료일자");
            $table->string("contract_resign_reason")->comment("계약해지사유");
            $table->string("service_status")->comment("서비스상태");
            $table->string("service_start_date")->comment("서비스시작일자");
            $table->string("service_end_date")->comment("서비스종료일자");
            $table->string("service_resign_date")->comment("서비스해지일자");
            $table->string("target_helper")->comment("지정제공인력");
            $table->string("help_price_total")->comment("지원금합계");
            $table->string("government_help_price")->comment("정부지원금");
            $table->string("deductible")->comment("본인부담금");
            $table->string("childbirth_date")->comment("자녀출산일자");
            $table->string("leave_hospital_date")->comment("퇴원일자");
            $table->string("zip_code")->comment("제공자 우편번호");
            $table->string("service_address")->comment("서비스제공지");
            $table->string("address")->comment("대상자주소");
            $table->string("address_detail")->comment("대상자상세주소");
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
        Schema::dropIfExists('clients');
    }
}
