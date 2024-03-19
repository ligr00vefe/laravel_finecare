<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MembersDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained("users");
            $table->foreignId('member_id')->constrained("members");
            $table->string("gender")->nullable();
            $table->string("physical_service_check")->nullable();
            $table->string("housekeeping_service_check")->nullable();
            $table->string("social_service_check")->nullable();
            $table->string("age_group")->nullable();
            $table->string("school_name")->nullable();
            $table->string("school_week")->nullable();
            $table->time("school_start_time")->nullable();
            $table->time("school_end_time")->nullable();
            $table->string("short_term_care_center_name")->nullable();
            $table->string("short_term_care_center_week")->nullable();
            $table->time("short_term_care_center_start_time")->nullable();
            $table->time("short_term_care_center_end_time")->nullable();
            $table->string("additional_benefit_target_status")->nullable();
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
