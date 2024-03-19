<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNationalPensionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('national_pension_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->string("target_id")->comment("이름+주민번호");
            $table->string("np_no")->comment("국민연금번호");
            $table->string("rsNo")->comment("주민번호");
            $table->string("name")->comment("가입자명");
            $table->string("reason")->comment("정산사유")->nullable();
            $table->date("period")->comment("정산적용기간")->nullable();
            $table->string("insurance_fee")->comment("결정보험료");
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
        Schema::dropIfExists('national_pension_logs');
    }
}
