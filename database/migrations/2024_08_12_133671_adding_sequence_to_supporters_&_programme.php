<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
			DB::statement('ALTER TABLE `supporters`
					ADD COLUMN `sequence` int(11) NOT NULL AFTER `active`;
			');

			DB::statement('ALTER TABLE `programme`
					ADD COLUMN `sequence` int(11) NOT NULL AFTER `active`;
			');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            //
        });
    }
};
