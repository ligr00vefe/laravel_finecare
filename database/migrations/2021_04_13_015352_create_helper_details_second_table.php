<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHelperDetailsSecondTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('helper_details_second', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->string("target_id");
            $table->string("name");
            $table->string("rsNo");
            $table->string("regNo")->nullable();
            $table->date("regdate");
            $table->date("regEndDate")->nullable();
            $table->date("join_date")->nullable();
            $table->date("resign_date")->nullable();
            $table->string("phone")->nullable();
            $table->string("tel")->nullable();
            $table->string("address")->nullable();
            $table->string("bank_name")->nullable();
            $table->string("bank_account_number")->nullable();
            $table->string("depositary_stock")->nullable();
            $table->string("license_info")->nullable();
            $table->string("email")->nullable();
            $table->string("crime_check")->nullable();
            $table->string("national_pension")->nullable();
            $table->string("national_pension_monthly")->nullable();
            $table->string("health_insurance")->nullable();
            $table->string("health_insurance_monthly")->nullable();
            $table->string("long_term_care_insurance_reduction")->nullable();
            $table->string("employment_insurance")->nullable();
            $table->string("employment_insurance_monthly")->nullable();
            $table->string("employment_insurance_after_65age")->nullable();
            $table->string("industrial_accident_insurance")->nullable();
            $table->string("industrial_accident_insurance_monthly")->nullable();
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
        Schema::dropIfExists('helper_details_second');
    }
}
