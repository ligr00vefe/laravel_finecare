<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentWorkersInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_workers_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->foreignId("payment_id")->constrained("payment_vouchers");
            $table->foreignId("condition_id")->constrained("payment_conditions");
            $table->string("provider_name")->nullable();
            $table->date("target_ym")->nullable();
            $table->string("provider_key");
            $table->string("provider_reg_check")->nullable()->comment("제공자 미등록");
            $table->string("join_date")->nullable()->comment("입사일");
            $table->string("resign_date")->nullable()->comment("퇴사일");
            $table->string("nation_ins")->nullable()->comment("국민연금가입여부");
            $table->string("health_ins")->nullable()->comment("건강보험가입여부");
            $table->string("employ_ins")->nullable()->comment("고용보험가입여부");
            $table->string("retirement")->nullable()->comment("퇴직연금가입여부");
            $table->string("year_rest_count")->nullable()->comment("연차갯수");
            $table->string("dependents")->nullable()->comment("부양가족");
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
        Schema::dropIfExists('payment_workers_info');
    }
}
