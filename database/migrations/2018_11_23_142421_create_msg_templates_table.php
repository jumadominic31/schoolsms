<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMsgTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('msg_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('school_id');
            $table->string('clockinmsg');
            $table->string('clockoutmsg');
            $table->string('negclockinmsg');
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
        Schema::dropIfExists('msg_templates');
    }
}
