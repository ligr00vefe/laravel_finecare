<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->string("member_id");
            $table->string("name");
            $table->string("rsNo");
            $table->string("regNo")->nullable();
            $table->date("regdate")->nullable();
            $table->date("regEndDate")->nullable();
            $table->date("contract_start_date")->nullable();
            $table->date("contract_end_date")->nullable();
            $table->string("phone")->nullable();
            $table->string("tel")->nullable();
            $table->string("address")->nullable();
            $table->string("email")->nullable();
            $table->string("office")->nullable();
            $table->integer("MHW_decision_time")->nullable();
            $table->integer("local_government_decision_time")->nullable();
            $table->integer("etc_decision_time")->nullable();
            $table->text("other_organiz_exp")->nullable();
            $table->string("income_check")->nullable();
            $table->string("activity_support_grade_new")->nullable();
            $table->string("activity_support_grade_exist")->nullable();
            $table->string("activity_support_grade_type")->nullable();
            $table->string("income_decision_date")->nullable();
            $table->integer("personal_charge")->nullable();
            $table->string("main_disable_name")->nullable();
            $table->string("main_disable_degree")->nullable();
            $table->string("main_disable_grade")->nullable();
            $table->string("sub_disable_name")->nullable();
            $table->string("sub_disable_degree")->nullable();
            $table->string("sub_disable_grade")->nullable();
            $table->string("disease_name")->nullable();
            $table->string("dosing_info")->nullable();
            $table->string("bed_disable_check")->nullable();
            $table->string("marriage_check")->nullable();
            $table->string("family_details")->nullable();
            $table->string("guardian_name")->nullable();
            $table->string("guardian_relationship")->nullable();
            $table->string("guardian_phone")->nullable();
            $table->string("guardian_tel")->nullable();
            $table->string("guardian_address")->nullable();
            $table->text("note")->nullable();
            $table->text("overall")->nullable();
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
        Schema::dropIfExists('members');
    }
}
