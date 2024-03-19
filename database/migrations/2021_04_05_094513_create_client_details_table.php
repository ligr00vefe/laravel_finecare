<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained();
            $table->string("name")->comment("이름");
            $table->string("rsNo")->comment("주민등록번호");
            $table->string("client_key")->comment("이용자 키");
            $table->string("client_number")->comment("이용자 관리번호");
            $table->string("regdate")->comment("접수일");
            $table->string("contract_start_date")->comment("계약시작일자");
            $table->string("contract_end_date")->comment("계약종료일자");
            $table->string("phone")->comment("휴대전화번호");
            $table->string("tel")->comment("자택전화번호");
            $table->string("address")->comment("주소");
            $table->string("email")->comment("이메일");
            $table->string("company")->comment("직장");
            $table->string("bogun_time")->comment("보건복지부 판정시간");
            $table->string("jijache_time")->comment("지자체 추가 판정시간");
            $table->string("etc_time")->comment("기타 판정시간");
            $table->string("other_experience")->comment("타기관 이용경험");
            $table->string("income_check")->comment("수급여부");
            $table->string("activity_grade")->comment("활동지원등급(신규)");
            $table->string("activity_grade_old")->comment("활동지원등급(기존)");
            $table->string("activity_grade_type")->comment("활동지원등급유형");
            $table->string("income_decision_date")->comment("수급결정시기");
            $table->string("self_charge_price")->comment("본인부담금");
            $table->string("main_disable_name")->comment("주장애명");
            $table->string("main_disable_level")->comment("주장애정도");
            $table->string("main_disable_grade")->comment("주장애등급");
            $table->string("sub_disable_name")->comment("부장애명");
            $table->string("sub_disable_level")->comment("부장애정도");
            $table->string("sub_disable_grade")->comment("부장애등급");

            $table->string("disease_name")->comment("보유질환명");
            $table->string("drug_info")->comment("투약정보");
            $table->string("wasang_check")->comment("와상장애여부");
            $table->string("marriage_check")->comment("결혼여부");
            $table->string("family_info")->comment("가족사항");
            $table->string("protector_name")->comment("보호자명");
            $table->string("protector_relation")->comment("보호자관계");
            $table->string("protector_phone")->comment("보호자휴대전화번호");
            $table->string("protector_tel")->comment("보호자자택전화번호");
            $table->string("protector_address")->comment("보호자주소");
            $table->string("etc")->comment("특이사항");
            $table->string("comment")->comment("종합소견");


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
        Schema::dropIfExists('client_details');
    }
}
