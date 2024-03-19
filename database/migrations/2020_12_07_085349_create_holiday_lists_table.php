<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHolidayListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('holiday_lists', function (Blueprint $table) {
            $table->id();
            $table->date("full_date")->comment("날짜");
            $table->integer("year")->comment("년");
            $table->integer("month")->comment("월");
            $table->integer("day")->comment("일");
            $table->string("comment")->comment("설명");
            $table->integer("admin_id")->nullable()->comment("업로드한 관리자번호(필수로바꿔야함)");
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
        Schema::dropIfExists('holiday_lists');
    }
}
