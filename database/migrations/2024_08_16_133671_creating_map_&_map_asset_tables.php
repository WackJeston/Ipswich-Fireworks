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
			DB::statement('CREATE TABLE `map_asset` (
					`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
					`name` varchar(255) DEFAULT NULL,
					`sequence` int(11) NOT NULL,
					`assetId` bigint(20) unsigned DEFAULT NULL,
					`created_at` timestamp NULL DEFAULT NULL,
					`updated_at` timestamp NULL DEFAULT NULL,
					PRIMARY KEY (`id`),
					KEY `assetId` (`assetId`),
					CONSTRAINT `map_asset_ibfk_1` FOREIGN KEY (`assetId`) REFERENCES `asset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
				);
			');

			DB::statement('CREATE TABLE `map` (
					`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
					`assetId` bigint(20) unsigned DEFAULT NULL,
					`finalAssetId` bigint(20) unsigned DEFAULT NULL,
					`canvasHeight` int(10) unsigned DEFAULT NULL,
					`canvasWidth` int(10) unsigned DEFAULT NULL,
					`images` varchar(5000) DEFAULT NULL,
  				`active` tinyint(1) NOT NULL DEFAULT 0,
					`created_at` timestamp NULL DEFAULT NULL,
					`updated_at` timestamp NULL DEFAULT NULL,
					PRIMARY KEY (`id`)
				);
			');

			DB::statement('ALTER TABLE `map` 
					ADD FOREIGN KEY (`assetId`) REFERENCES `asset` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
					ADD FOREIGN KEY (`finalAssetId`) REFERENCES `asset` (`id`) ON UPDATE CASCADE ON DELETE CASCADE;
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
