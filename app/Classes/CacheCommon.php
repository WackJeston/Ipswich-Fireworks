<?php
namespace App\Classes;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class CacheCommon {
	function getCachedRecords(string $key) {
		if (Cache::has($key)) {
			return Cache::get($key);
		}
	
		return false;
	}
	
	function cacheRecords(string $key, array $records, int $seconds = null) {
		if (Cache::has($key)) {
			$records = Cache::get($key);
	
		} else {
			if ($seconds == null) {
				$seconds = strtotime(date("Y-m-d 02:00", strtotime('tomorrow'))) - strtotime(now());
				// $seconds = 300;
			}
	
			Cache::put($key, $records, $seconds);
		}
	
		return $records;
	}
	
	function cachePdf(string $fileName, bool $overWrite = false) {
		$publicFileName = sprintf('pdfs/%s.pdf', explode('.', $fileName)[0]);
	
		if (!Storage::disk('public')->exists($publicFileName) || $overWrite) {
			$data = Storage::get('pdfs/' . $fileName);
	
			if (!empty($data)) {
				Storage::disk('public')->put($publicFileName, $data);
			}
		}
			
		return Storage::disk('public')->url($publicFileName);
	}
}