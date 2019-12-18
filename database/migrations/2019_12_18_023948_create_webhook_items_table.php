<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebhookItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webhook_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('conversation_id', 36)->nullable()->index()->comment('会话ID');
            $table->string('access_token', 64)->nullable()->index()->comment('Access Token');
            $table->unsignedBigInteger('count')->default(0)->comment('发消息次数');
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
        Schema::dropIfExists('webhook_items');
    }
}
