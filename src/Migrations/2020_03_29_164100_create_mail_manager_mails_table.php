<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailManagerMailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('mail_manager.table_name'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->string('mailable_name')->nullable();
            $table->string('subject')->nullable();
            $table->text('recipients')->nullable();
            $table->text('mailable');
            $table->boolean('is_queued');
            $table->boolean('is_notification')->default(false);
            $table->text('notifiable')->nullable();
            $table->boolean('is_sent')->default(false);
            $table->unsignedInteger('tries')->default(0);
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
        Schema::dropIfExists(config('mail_manager.table_name'));
    }
}
