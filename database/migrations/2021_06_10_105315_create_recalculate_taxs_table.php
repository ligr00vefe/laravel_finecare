<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecalculateTaxsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recalculate_taxs', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->foreignId("worker_id")->constrained("workers");
            $table->foreignId("payment_id")->constrained("payment_vouchers");
            $table->foreignId("condition_id")->constrained("payment_conditions");
            $table->date("target_ym")->nullable();
            $table->string("provider_key");
            $table->string("save_selector");
            $table->string("selected_payment");
            $table->string("tax_nation_pension")->comment("������ �޿��������� ���ο���")->nullable();
            $table->string("tax_health")->comment("������ �޿��������� �ǰ�����")->nullable();
            $table->string("tax_employ")->comment("������ �޿��������� ��뺸��")->nullable();
            $table->string("tax_gabgeunse")->comment("������ �޿��������� ���ټ�")->nullable();
            $table->string("tax_joominse")->comment("������ �޿��������� �ֹμ�")->nullable();
            $table->string("tax_gunbo")->comment("������ �޿��������� �Ǻ�����")->nullable();
            $table->string("tax_yearly")->comment("������ �޿��������� ��������")->nullable();
            $table->string("tax_bad_income")->comment("������ �޿��������� ��������ȯ��")->nullable();
            $table->string("tax_etc_1")->comment("������ �޿��������� ��Ÿ����1")->nullable();
            $table->string("tax_etc_2")->comment("������ �޿��������� ��Ÿ����2")->nullable();
            $table->string("tax_total")->comment("������ �޿��������� �����հ�")->nullable();
            $table->string("tax_sub_payment")->comment("�������޾�(����. �ٿ�ó�������հ�-�����հ�)")->nullable();
            $table->string("bank_name")->comment("�����")->nullable();
            $table->string("bank_number")->comment("���¹�ȣ")->nullable();
            $table->string("company_income")->comment("����� �������")->nullable();
            $table->string("tax_company_nation")->comment("����� ���ο���")->nullable();
            $table->string("tax_company_health")->comment("����� �ǰ�����")->nullable();
            $table->string("tax_company_employ")->comment("����� ��뺸��")->nullable();
            $table->string("tax_company_industry")->comment("����� ���纸��")->nullable();
            $table->string("tax_company_retirement")->comment("����� ��������")->nullable();
            $table->string("tax_company_return_confirm")->comment("����� �ݳ�����(�����)")->nullable();
            $table->string("tax_company_tax_total")->comment("����� �δ��հ�")->nullable();
            $table->string("company_payment_result")->comment("����� ��������� ����")->nullable();

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
        Schema::dropIfExists('recalculate_taxs');
    }
}
