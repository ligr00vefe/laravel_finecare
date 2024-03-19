<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class WorkersDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workers_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained("users");
            $table->foreignId('worker_id')->constrained("workers");
            $table->string("gender")->nullable();
            $table->integer("year_rest_count")->nullable()->comment("연차갯수")->default(1);
            $table->integer("dependents")->nullable()->comment("부양가족수")->default(1);
            $table->string("family_certificate_submit")->nullable()->comment("가족관계증명서 제출여부");
            $table->string("basic_certificate_submit")->nullable()->comment("기본증명서 제출여부");
            $table->string("identification_certificate_submit")->nullable()->comment("주민등록증 제출여부");
            $table->string("retirement_pay_contract")->nullable()->comment("퇴직적립금 관련 계약");
            $table->string("retirement_pay_fixed")->nullable()->comment("퇴직적립금 월고정액");
            $table->string("gabgeunse_price")->nullable()->comment("갑근세금액");
            $table->string("gabgeunse_percentage")->nullable()->comment("갑근세요율");
            $table->string("device_info")->nullable()->comment("단말기정보");
            $table->string("device_number")->nullable()->comment("단말기번호");
            $table->string("academic_career")->nullable()->comment("학력");
            $table->string("marriage_check")->nullable()->comment("결혼여부");
            $table->string("physical_condition")->nullable()->comment("건강상태");
            $table->string("main_disable_name")->nullable()->comment("주장애명");
            $table->string("main_disable_grade")->nullable()->comment("주장애등급");
            $table->string("main_disable_degree")->nullable()->comment("주장애정도");
            $table->string("sub_disable_name")->nullable()->comment("부장애명");
            $table->string("sub_disable_grade")->nullable()->comment("부장애등급");
            $table->string("sub_disable_degree")->nullable()->comment("부장애정도");
            $table->string("religion")->nullable()->comment("종교");
            $table->string("desired_working_hours")->nullable()->comment("희망근로시간");
            $table->tinyInteger("has_car")->nullable()->comment("차량소지")->nullable();
            $table->tinyInteger("physical_service_check")->nullable()->comment("신체서비스유무")->nullable();
            $table->tinyInteger("housekeeping_service_check")->nullable()->comment("가사서비스유무")->nullable();
            $table->tinyInteger("social_service_check")->nullable()->comment("사회활동서비스")->nullable();
            $table->tinyInteger("service_age_group1")->nullable()->comment("서비스연령대아동")->nullable();
            $table->tinyInteger("service_age_group2")->nullable()->comment("서비스연령대청소년")->nullable();
            $table->tinyInteger("service_age_group3")->nullable()->comment("서비스연령대성인")->nullable();
            $table->tinyInteger("service_age_group4")->nullable()->comment("서비스연령대노인")->nullable();
            $table->text("related_service_experience")->nullable()->comment("관련근무경력");
            $table->text("etc")->nullable()->comment("기타사항");
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
