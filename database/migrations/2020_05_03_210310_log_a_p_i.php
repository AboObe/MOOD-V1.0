<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LogAPI extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_api', function (Blueprint $table) {
            $table->bigIncrements('id');
             $table->bigInteger('user_id')->nullable()->unsigned();
             $table->text('event_name')->nullable();
             $table->text('event_type')->nullable();
             $table->text('event_details')->nullable();
             $table->text('description')->nullable();
            $table->timestamps();
        });
        Schema::table('log_api', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
          });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_api');
    }
}
