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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
						$table->string('name', 255)->nullable()->default(null);
						$table->string('text', 255)->nullable()->default(null);
						$table->integer('int')->nullable()->default(null);
						$table->float('float')->nullable()->default(null);
						$table->date('date')->nullable()->default(null);
						$table->dateTime('datetime')->nullable()->default(null);
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
        Schema::dropIfExists('settings');
    }
};
