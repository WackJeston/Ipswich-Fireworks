<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programme', function (Blueprint $table) {
            $table->id();
						$table->string('type', 255)->default('standard');
						$table->string('label', 255)->nullable();
						$table->string('value', 1000);
						$table->string('stage', 255)->nullable();
						$table->string('time', 255)->nullable();
						$table->string('link', 255)->nullable();
						$table->string('fileName', 255)->nullable();
						$table->boolean('active')->default(0);
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
        Schema::dropIfExists('contact');
    }
};
