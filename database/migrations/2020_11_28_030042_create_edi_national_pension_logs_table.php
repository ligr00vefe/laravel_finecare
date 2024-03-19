<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEdiNationalPensionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edi_national_pension_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->string("target_id")->comment("유저구분값");
            $table->string("name")->comment("성명");
            $table->string("rsNo")->comment("주민(외국인)등록번호");
            $table->string("monthly_base_income")->comment("기준소득월액");
            $table->string("monthly_ins_price")->comment("월보험료(계)");
            $table->string("personal_charge")->comment("(사용자부담금)");
            $table->string("personal_contribute_price")->comment("(본인기여금)");
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
        Schema::dropIfExists('edi_national_pension_logs');
    }
}
