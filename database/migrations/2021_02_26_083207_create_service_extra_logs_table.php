<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceExtraLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_extra_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->date("target_ym")->comment("대상연월")->nullable();
            $table->string("target_name")->comment("대상자 이름(필수)")->nullable();
            $table->string("target_birth")->comment("대상자 생년월일(필수)")->nullable();
            $table->string("provider_name")->comment("제공인력 이름")->nullable();
            $table->string("provider_birth")->comment("제공인력 생년월일")->nullable();
            $table->string("service_start_date_time")->comment("서비스 시작시간")->nullable();
            $table->string("service_end_date_time")->comment("서비스 종료시간")->nullable();
            $table->string("payment_time")->comment("결제시간")->nullable();
            $table->string("confirm_pay")->comment("총 결제금액")->nullable();
            $table->string("add_price")->comment("가산금액")->nullable();
            $table->string("local_government_name")->comment("지원지자체구분(이름)")->nullable();
            $table->string("organization")->comment("지원기관(국가가아니면 다 시도비라고 함)")->nullable();
            $table->text("etc")->comment("비고")->nullable();

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
        Schema::dropIfExists('service_extra_logs');
    }
}
