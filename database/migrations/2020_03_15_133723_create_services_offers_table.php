<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services_offers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('offer_id')->nullable()->unsigned();
            $table->bigInteger('service_id')->nullable()->unsigned();
            $table->string('details')->nullable();
            $table->timestamps();
        });
        Schema::table('services_offers', function (Blueprint $table) {
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services_offers');
    }
}
