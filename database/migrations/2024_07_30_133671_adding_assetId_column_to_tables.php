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
			DB::statement('
				ALTER TABLE `programme`
					ADD COLUMN `assetId` bigint(20) unsigned DEFAULT NULL AFTER fileName;
					
				ALTER TABLE `programme`
					ADD FOREIGN KEY (`assetId`) REFERENCES `asset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
						
				ALTER TABLE `programme`
					DROP COLUMN `fileName`;

				ALTER TABLE `supporters`
					ADD COLUMN `assetId` bigint(20) unsigned DEFAULT NULL AFTER fileName;
						
				ALTER TABLE `supporters`
					ADD FOREIGN KEY (`assetId`) REFERENCES `asset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
						
				ALTER TABLE `supporters`
					DROP COLUMN `fileName`;
			');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
