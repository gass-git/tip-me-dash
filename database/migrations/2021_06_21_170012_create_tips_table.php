<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recipient_id');
            $table->integer('sender_id')->nullable();
            $table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade');  
            $table->float('usd_equivalent',10,8)->nullable();
            $table->float('dash_amount',10,8)->nullable();
            $table->integer('dash_usd')->nullable();
            $table->string('sent_by')->nullable();
            $table->text('message')->nullable();
            $table->string('praise')->nullable();
            $table->string('stamp')->nullable();
            $table->string('status');
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
        Schema::dropIfExists('tips');
    }
}
