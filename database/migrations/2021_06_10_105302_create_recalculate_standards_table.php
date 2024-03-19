<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecalculateStandardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recalculate_standards', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->date("target_ym")->nullable();
            $table->string("provider_key");
            $table->foreignId("condition_id")->constrained("payment_conditions");

            $table->string("standard_basic_time")->comment("�ٷα��ر⺻�޽ð�")->nullable();
            $table->string("standard_basic_payment")->comment("�ٷα��ر⺻�ޱݾ�")->nullable();
            $table->string("standard_over_time")->comment("�ٷα��ؿ���ð�")->nullable();
            $table->string("standard_over_payment")->comment("�ٷα��ؿ���ݾ�")->nullable();
            $table->string("standard_holiday_time")->comment("�ٷα������Ͻð�")->nullable();
            $table->string("standard_holiday_payment")->comment("�ٷα������ϱݾ�")->nullable();
            $table->string("standard_night_time")->comment("�ٷα��ؾ߰��ð�")->nullable();
            $table->string("standard_night_payment")->comment("�ٷα��ؾ߰��ݾ�")->nullable();
            $table->string("standard_weekly_time")->comment("�ٷα������޼���ð�")->nullable();
            $table->string("standard_weekly_payment")->comment("�ٷα������޼���ݾ�")->nullable();
            $table->string("standard_yearly_time")->comment("�ٷα��ؿ�������ð�")->nullable();
            $table->string("standard_yearly_payment")->comment("�ٷα��ؿ�������ݾ�")->nullable();
            $table->string("standard_workers_day_time")->comment("�ٷα��رٷ����ǳ�����ð�")->nullable();
            $table->string("standard_workers_day_payment")->comment("�ٷα��رٷ����ǳ�����ݾ�")->nullable();
            $table->string("standard_public_day_time")->comment("�ٷα��ذ������������ϼ���ð�")->nullable();
            $table->string("standard_public_day_payment")->comment("�ٷα��ذ������������ϼ���ݾ�")->nullable();
            $table->string("standard_payment")->comment("�ٷα��������հ�")->nullable();

            $table->string("voucher_sub_standard_payment")->comment("�ٿ�ó�����հ�-�ٷα��������հ�=����������(����)")->nullable();
            $table->string("standard_bojeon")->comment("��������(�����Է�)")->nullable();
            $table->string("standard_jaboodam")->comment("�ںδ�޿�(�����Է�)")->nullable();
            $table->string("standard_jaesoodang")->comment("����������(�����Է�)")->nullable();
            $table->string("standard_bannap")->comment("�ݳ��߰�(�����Է�)")->nullable();
            $table->string("voucher_payment")->comment("�����Ѿ�(�ٿ�ó�������հ�)")->nullable();

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
        Schema::dropIfExists('recalculate_standards');
    }
}
