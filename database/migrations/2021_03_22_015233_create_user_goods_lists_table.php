<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserGoodsListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_goods_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->foreignId("goods_id")->constrained("payment_goods_lists");
            $table->date("start_date")->comment("적용시작일");
            $table->date("end_date")->comment("적용종료일");
            $table->integer("day_count")->comment("며칠짜리 상품인지");
            $table->string("payment_type")->comment("결제종류");
            $table->date("payment_date")->comment("결제일자");
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
        Schema::dropIfExists('user_goods_lists');
    }
}
