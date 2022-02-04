<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_offers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('offer_id')->nullable()->unsigned();
            $table->bigInteger('campaign_id')->nullable()->unsigned();
            $table->text('details')->nullable();
            $table->timestamps();
        });
        Schema::table('campaign_offers', function (Blueprint $table) {
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade');
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_offers');
    }
}
