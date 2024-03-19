<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHelpersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('helpers', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained();
            $table->string("name")->comment("제공인력명");
            $table->string("birth")->comment("생년월일");
            $table->string("target_key")->comment("제공인력명+생년월일. ");
            $table->string("target_id")->comment("제공인력ID");
            $table->string("target_payment_id")->comment("제공인력결제id");
            $table->string("card_number")->comment("카드번호");
            $table->string("agency_name")->comment("제공기관명");
            $table->string("business_number")->comment("사업자번호")->nullable();
            $table->string("sido")->comment("시도")->nullable();
            $table->string("sigungu")->comment("시군구")->nullable();
            $table->string("business_division")->comment("사업구분")->nullable();
            $table->string("business_types")->comment("사업유형")->nullable();
            $table->string("tel")->comment("제공인력연락처")->nullable();
            $table->string("phone")->comment("제공인력핸드폰번호")->nullable();
            $table->string("address")->comment("제공인력주소")->nullable();
            $table->string("etc")->comment("비고")->nullable();
            $table->string("contract")->comment("계약여부")->nullable();
            $table->dateTime("contract_start_date")->comment("계약일")->nullable();
            $table->dateTime("contract_end_date")->comment("계약 종료일")->nullable();
            $table->string("contract_date")->comment("계약기간")->nullable();
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
        Schema::dropIfExists('helpers');
    }
}
