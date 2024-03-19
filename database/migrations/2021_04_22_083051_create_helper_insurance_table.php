<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHelperInsuranceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('helper_insurance', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users")->cascadeOnDelete();
            $table->string("target_key");
            $table->string("national_ins_check")->comment("국민연금가입")->default("미가입");
            $table->string("national_ins_bosu_price")->comment("국민연급보수월액")->default("0");
            $table->string("health_ins_check")->comment("건강보험 가입여부")->default("미가입");
            $table->string("health_ins_bosu_price")->comment("건강보험 보수월액")->default("0");
            $table->integer("long_rest_subtract")->comment("장기요양 경감 0:체크안함, 1: 체크")->default(0);
            $table->string("employ_ins_check")->comment("고용보험 가입")->default("미가입");
            $table->string("employ_ins_bosu_price")->comment("고용보험 보수월액")->default(0);
            $table->integer("employ_65age_after")->comment("고용보험 65세 이후 0:체크안함, 1: 체크")->default(0);
            $table->string("industry_ins_check")->comment("산재보험 가입여부")->default("미가입");
            $table->string("industry_ins_bosu_price")->comment("산재보험 보수월액")->default(0);
            $table->integer("percentage_apply")->comment("비율계산적용")->default(0);
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
        Schema::dropIfExists('helper_insurance');
    }
}
