<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('google_id')->nullable();
            $table->string('username')->nullable()->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('wallet_address')->nullable();
            $table->string('avatar_url')->nullable();
            $table->string('avatar_name')->nullable();
            $table->text('about')->nullable();
            $table->unsignedBigInteger('page_views')->nullable();
            $table->string('website')->nullable();
            $table->string('location')->nullable();
            $table->string('passionate_about')->nullable();
            $table->string('twitter')->nullable();
            $table->string('github')->nullable();
            $table->string('youtube')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
