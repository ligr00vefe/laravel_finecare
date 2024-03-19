<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string("account_id")->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer("level");
            $table->string('company_name')->comment("업체명")->nullable();
            $table->string('tel')->comment("대표전화")->nullable();
            $table->string('fax')->comment("팩스번호")->nullable();
            $table->string('phone')->comment("관리자 연락처")->nullable();
            $table->string('license')->comment("사업자등록번호")->nullable();
            $table->string('address')->comment("주소")->nullable();
            $table->string('memo')->comment("메모")->nullable();
            $table->string('ip')->comment("아이피")->nullable();
            $table->string('resign')->comment("탈퇴일자")->nullable();
            $table->tinyInteger('deleteCheck')->comment("계정삭제체크")->default(0);
            $table->date("delete_date")->comment("계정삭제일자")->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
