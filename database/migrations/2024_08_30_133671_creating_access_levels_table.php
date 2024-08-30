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
			DB::statement('CREATE TABLE `access_levels` (
					`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
					`name` varchar(255) NOT NULL,
					`default` bool DEFAULT 0,
					`master` bool DEFAULT 0,
					`created_at` timestamp NULL DEFAULT NULL,
					`updated_at` timestamp NULL DEFAULT NULL,
					PRIMARY KEY (`id`)
				) ENGINE=InnoDB AUTO_INCREMENT=154 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
			');

			DB::statement('ALTER TABLE `users`
					ADD COLUMN `accessLevelId` bigint(20) unsigned DEFAULT NULL AFTER `password`;
			');

			DB::statement('ALTER TABLE `users`
					ADD FOREIGN KEY (`accessLevelId`) REFERENCES `access_levels` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
			');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
