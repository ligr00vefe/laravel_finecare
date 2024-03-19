<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHelperDraftSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('helper_draft_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->string("worker_id");
            $table->enum("work_type", [ "사회", "신체", "가사", "휴게", "비번" ])->comment("사회, 신체, 가사, 휴게, 비번");
            $table->date("date")->comment("비번일떈 날짜만 적기");
            $table->datetime("start_date_time")->comment("서비스시작시간")->nullable();
            $table->datetime("end_date_time")->comment("서비스종료시간")->nullable();
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
        Schema::dropIfExists('helper_draft_schedules');
    }
}
