<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecalculateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recalculate_vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->foreignId("condition_id")->constrained("payment_conditions");
            $table->date("target_ym")->nullable();
            $table->string("provider_key");
            $table->integer("is_register")->comment("Ȱ��������� ��ϵǾ� �ִ���?")->nullable();
            $table->string("nation_day_count")->comment("�ٿ�ó����ٹ��ϼ�")->nullable();
            $table->string("nation_confirm_payment")->comment("�ٿ�ó������αݾ�")->nullable();
            $table->string("nation_add_payment")->comment("�ٿ�ó���񰡻��")->nullable();
            $table->string("nation_total_time")->comment("�ٿ�ó���� �� �ð�")->nullable();
            $table->string("nation_holiday_time")->comment("�ٿ�ó�������Ͻð�")->nullable();
            $table->string("nation_night_time")->comment("�ٿ�ó����߰��ð�")->nullable();
            $table->string("city_day_count")->comment("�ٿ�ó�õ���ٹ��ϼ�")->nullable();
            $table->string("city_confirm_payment")->comment("�ٿ�ó�õ�����αݾ�")->nullable();
            $table->string("city_add_payment")->comment("�ٿ�ó�õ��񰡻��")->nullable();
            $table->string("city_total_time")->comment("�ٿ�ó�õ����ѽð�")->nullable();
            $table->string("city_holiday_time")->comment("�ٿ�ó�õ������Ͻð�")->nullable();
            $table->string("city_night_time")->comment("�ٿ�ó�õ���߰��ð�")->nullable();
            $table->string("voucher_total_day_count")->comment("�ٿ�ó�հ�ٹ��ϼ�")->nullable();
            $table->string("voucher_total_confirm_payment")->comment("�ٿ�ó�հ���αݾ�")->nullable();
            $table->string("voucher_total_confirm_payment_add")->comment("�ٿ�ó�հ谡���")->nullable();
            $table->string("voucher_total_time")->comment("�ٿ�ó�հ��ѽð�")->nullable();
            $table->string("voucher_total_time_holiday")->comment("�ٿ�ó�հ����Ͻð�")->nullable();
            $table->string("voucher_total_time_night")->comment("�ٿ�ó�հ�߰��ð�")->nullable();
            $table->string("voucher_detach_payment_basic")->comment("�ٿ�ó�ݾ׺и��⺻��")->nullable();
            $table->string("voucher_detach_payment_holiday")->comment("�ٿ�ó�ݾ׺и����ϱݾ�")->nullable();
            $table->string("voucher_detach_payment_night")->comment("�ٿ�ó�ݾ׺и��߰��ݾ�")->nullable();
            $table->string("voucher_detach_payment_difference")->comment("�ٿ�ó�ݾ׺и����αݾ���")->nullable();

            $table->string("voucher_etc_charge_city_time")->comment("��Ÿû���ñ�����ð�")->nullable();
            $table->string("voucher_etc_charge_city_pay")->comment("��Ÿû���ñ������ѱݾ�")->nullable();
            $table->string("voucher_etc_charge_except_time")->comment("��Ÿû������û���ð�")->nullable();
            $table->string("voucher_etc_charge_except_pay")->comment("��Ÿû������û���ѱݾ�")->nullable();
            $table->string("voucher_etc_charge_total_time")->comment("��Ÿû���հ�ð�")->nullable();
            $table->string("voucher_etc_charge_total_pay")->comment("��Ÿû���հ��ѱݾ�")->nullable();

            $table->string("voucher_return_nation_day_count")->comment("�ٿ�ó����ݳ��ٹ��ϼ�")->nullable();
            $table->string("voucher_return_nation_pay")->comment("�ٿ�ó����ݳ����αݾ�")->nullable();
            $table->string("voucher_return_nation_time")->comment("�ٿ�ó����ݳ��ѽð�")->nullable();
            $table->string("voucher_return_city_day_count")->comment("�ٿ�ó�õ���ݳ��ٹ��ϼ�")->nullable();
            $table->string("voucher_return_city_pay")->comment("�ٿ�ó�ݳ��õ�����αݾ�")->nullable();
            $table->string("voucher_return_city_time")->comment("�ٿ�ó�õ���ݳ��ѽð�")->nullable();
            $table->string("voucher_return_total_day_count")->comment("�ٿ�ó�հ�ݳ��ٹ��ϼ�")->nullable();
            $table->string("voucher_return_total_pay")->comment("�ٿ�ó�ݳ��հ���αݾ�")->nullable();
            $table->string("voucher_return_total_time")->comment("�ٿ�ó�հ�ݳ��ѽð�")->nullable();

            $table->string("voucher_payment")->comment("�ٿ�ó�������հ�")->nullable();


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
        Schema::dropIfExists('recalculate_vouchers');
    }
}
