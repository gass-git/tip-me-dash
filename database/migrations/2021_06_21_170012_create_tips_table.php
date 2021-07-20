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

    /** @abstract
     * 
     * IMPORTANT:
     * If a user deletes his account all the tips that where sent to him
     * will be deleted, but not the ones he sent.
     * 
     */

    public function up()
    {
        Schema::create('tips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recipient_id');
            $table->integer('sender_id')->nullable();
            $table->string('sender_ip');
            $table->string('sender_email')->nullable();
            $table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade');  
            $table->string('recipient_email');
            $table->float('usd_equivalent',10,8)->nullable();
            $table->float('dash_amount',10,8)->nullable();
            $table->integer('dash_usd')->nullable();
            $table->string('sent_by')->nullable();
            $table->text('message')->nullable();
            $table->string('private_msg')->nullable();
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
