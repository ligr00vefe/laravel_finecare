<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecalculateWorkersInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recalculate_workers_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->foreignId("payment_id")->constrained("payment_vouchers");
            $table->foreignId("condition_id")->constrained("payment_conditions");
            $table->string("provider_name")->nullable();
            $table->date("target_ym")->nullable();
            $table->string("provider_key");
            $table->string("provider_reg_check")->nullable()->comment("������ �̵��");
            $table->string("join_date")->nullable()->comment("�Ի���");
            $table->string("resign_date")->nullable()->comment("�����");
            $table->string("nation_ins")->nullable()->comment("���ο��ݰ��Կ���");
            $table->string("health_ins")->nullable()->comment("�ǰ����谡�Կ���");
            $table->string("employ_ins")->nullable()->comment("��뺸�谡�Կ���");
            $table->string("retirement")->nullable()->comment("�������ݰ��Կ���");
            $table->string("year_rest_count")->nullable()->comment("��������");
            $table->string("dependents")->nullable()->comment("�ξ簡��");
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
        Schema::dropIfExists('recalculate_workers_info');
    }
}
