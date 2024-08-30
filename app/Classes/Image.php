<?php
namespace App\Classes;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use ImageOptimizer;

use App\Models\Asset;


class ImageCommon {
	public static function cacheImage(string $fileName, int $width = 0, int $height = 0, bool $trim = false, string $background = null, bool $webp = true):string {
		$publicFileName = sprintf('images/%s%s%s.%s', 
			explode('.', $fileName)[0], 
			($width > 0 || $height > 0) ? sprintf('-%d-%d', $width, $height) : '',
			$trim ? '-trim' : '',
			$webp ? 'webp' : explode('.', $fileName)[1]
		);
	
		if (!Storage::disk('public')->exists($publicFileName)) {
			$data = Storage::get($fileName);
			$mimeType = Storage::mimeType($fileName);
	
			if (!empty($data)) {
				$manager = new ImageManager(['driver' => 'imagick']);
	
				if ($mimeType == 'image/svg+xml') {
					$image = $manager->make($data);
				} else {
					$image = $manager->make($data);
				}
	
				if($trim) {
					$image->trim();
				}
	
				if ($width > 0 || $height > 0) {
					$width = $width > 0 ? $width : null;
					$height = $height > 0 ? $height : null;
	
					$image->resize($width, $height, function($constraint) {
						$constraint->aspectRatio();
						// $constraint->upsize();
					});
	
					if (!is_null($background)) {
						$image->resizeCanvas($width, $height, 'center', false, $background);
					}
				}
				
				if($webp) {
					$image->encode('webp');
				}
				
				Storage::disk('public')->put($publicFileName, $image);
				ImageOptimizer::optimize(env('ASSET_PATH_SERVER') . $publicFileName);
			}
		}
			
		return Storage::disk('public')->url($publicFileName);
	}
	
	public static function cacheImages($records, int $width = 0, int $height = 0, bool $trim = false, string $background = null, bool $webp = true) {
		foreach ($records as $i => $record) {
			if (property_exists($record, 'fileName')) {
				if (is_array($record)) {
					if (!empty($record['fileName'])) {
						$record['fileName'] = Image::cacheImage($record['fileName'], $width, $height, $trim, $background, $webp);
					}
				} else {
					if (!empty($record->fileName)) {
						$record->fileName = Image::cacheImage($record->fileName, $width, $height, $trim, $background, $webp);
					}
				}
			}
			
		}
	
		return $records;
	}
	
	public static function preloadImage(string $url, bool $first = false) {
		if (session()->has('preloaded-images')) {
			if ($first) {
				$records = [];
			} else {
				$records = session()->get('preloaded-images');
			}
	
			if (!in_array($url, $records)) {
				$records[] = $url;
				session()->put('preloaded-images', $records);
			}
	
		} else {
			$records = [$url];
			session()->put('preloaded-images', $records);
		}
	}
	
	public static function storeImages(Request $request, $id, string $type):array {
		$array = [];
		$fileNames = [];
	
		foreach ($request->file() as $i => $file) {
			if (str_starts_with($i, 'image')) {
				$array[] = $file;
			}
		}
	
		foreach ($array as $i => $file) {
			$mimeType = explode('/', $file->getMimeType())[1];
	
			if ($mimeType == 'jpeg') {
				$mimeType = 'jpg';
			}
	
			$fileName = sprintf('images/%s-%s-%s-%s.%s',
				$type,
				$id,
				$_SERVER['REQUEST_TIME'],
				rtrim(explode('.', str_replace([' ', '(', ')'], '-', $file->getClientOriginalName()))[0], '-'),
				$mimeType
			);
	
			Storage::put($fileName, file_get_contents($file));
	
			$asset = Asset::create([
				'name' => $file->getClientOriginalName(),
				'fileName' => $fileName,
			]);
			
			$fileNames[] = [
				'id' => $asset->id,
				'new' => $asset->fileName,
				'old' => $asset->name,
			];
		}
	
		return $fileNames;
	}
	
	public static function storeImageFromString(string $fileName, string $data) {
		Storage::put('images/' . $fileName, $data);
	
		$asset = Asset::create([
			'name' => $fileName,
			'fileName' => $fileName,
		]);
	
		return $asset->id;
	}
}