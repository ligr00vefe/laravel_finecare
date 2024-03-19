<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSimplifiedTaxTableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('simplified_tax_table', function (Blueprint $table) {
            $table->id();
            $table->integer("moreThan")->comment("이상");
            $table->integer("under")->comment("미만");
            $table->integer("dependents1")->comment("부양가족1일때");
            $table->integer("dependents2")->comment("부양가족2일때")->nullable();
            $table->integer("dependents3")->comment("부양가족3일때")->nullable();
            $table->integer("dependents4")->comment("부양가족4일때")->nullable();
            $table->integer("dependents5")->comment("부양가족5일때")->nullable();
            $table->integer("dependents6")->comment("부양가족6일때")->nullable();
            $table->integer("dependents7")->comment("부양가족7일때")->nullable();
            $table->integer("dependents8")->comment("부양가족8일때")->nullable();
            $table->integer("dependents9")->comment("부양가족9일때")->nullable();
            $table->integer("dependents10")->comment("부양가족10일때")->nullable();
            $table->integer("dependents11")->comment("부양가족11일때")->nullable();
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
        Schema::dropIfExists('simplified_tax_table');
    }
}
