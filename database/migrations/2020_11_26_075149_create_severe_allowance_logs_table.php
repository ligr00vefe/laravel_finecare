<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSevereAllowanceLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('severe_allowance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->string("target_id")->comment("대상자 구분값");
            $table->string("return_check")->nullable()->comment("반납여부");
            $table->string("target_name")->comment("대상자명");
            $table->string("target_rsNo")->comment("대상자주민번호");
            $table->string("provider_name")->comment("제공인력명");
            $table->string("provider_rsNo")->comment("제공인력주민번호");
            $table->string("provider_id")->comment("제공자 구분값");
            $table->string("provider_agency")->comment("제공기관명");
            $table->string("business_license")->comment("사업자번호");
            $table->string("sido")->comment("사도");
            $table->string("sigungu")->comment("시군구");
            $table->string("business_division")->comment("사업구분");
            $table->string("business_class")->comment("사업유형");
            $table->string("confirm_number")->comment("승인번호");
            $table->string("confirm_date")->comment("승인일시");
            $table->dateTime("service_start_date_time")->comment("서비스시작시간");
            $table->dateTime("service_end_date_time")->comment("서비스종료시간");
            $table->string("general_payment_time")->comment("일반결제시간");
            $table->string("add_payment_time")->comment("추가결제시간");
            $table->string("payment_amount")->comment("지급액");
            $table->date("amount_date")->comment("지급일자");
            $table->date("return_date")->comment("반납일자");
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
        Schema::dropIfExists('severe_allowance_logs');
    }
}
