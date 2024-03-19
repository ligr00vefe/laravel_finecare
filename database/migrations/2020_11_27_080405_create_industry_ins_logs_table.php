<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndustryInsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('industry_ins_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->string("target_id")->comment("고유번호");
            $table->string("original_number")->comment("원부번호")->nullable();
            $table->string("rsNo")->comment("주민번호");
            $table->string("name")->comment("가입자명");
            $table->string("insurance_fee")->comment("결정보험료");
            $table->string("monthly_bosu_price")->comment("월평균보수월액")->nullable();
            $table->text("remarks")->comment("비고")->nullable();
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
        Schema::dropIfExists('industry_ins_logs');
    }
}
