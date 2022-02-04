<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_campaigns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('campaign_id')->nullable()->unsigned();
            $table->bigInteger('restaurant_id')->nullable()->unsigned();
            $table->timestamps();
        });
        Schema::table('restaurant_campaigns', function (Blueprint $table) {
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
          });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurant_campaigns');
    }
}
