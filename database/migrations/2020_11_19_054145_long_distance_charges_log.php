<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LongDistanceChargesLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('long_distance_charges_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            // worker_id 안필요한지 물어보기
            $table->date("target_ym")->nullable()->comment("대상년월");
            $table->string("business_division")->nullable()->comment("사업구분");
            $table->string("business_class")->nullable()->comment("사업유형");
            $table->string("provide_agency")->nullable()->comment("제공기관명");
            $table->string("business_license")->nullable()->comment("사업자번호");
            $table->string("provider_name")->nullable()->comment("제공인력명");
            $table->string("provider_birth")->nullable()->comment("제공인력생년월일");
            $table->string("service_count")->nullable()->comment("서비스제공횟수");
            $table->string("return_count")->nullable()->comment("반납횟수");
            $table->string("total_payment")->nullable()->comment("지급액합계");
            $table->string("provide_date")->nullable()->comment("지급일자");
            
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
        //
    }
}
