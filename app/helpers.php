<?php

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Aws\S3\S3Client;

function storeImages($request, $id, string $type):array {
	$fileNames = [];

	foreach ($request->files as $i => $file) {
		$mimeType = str_replace('image/', '', $file->getClientMimeType());
		if ($mimeType == 'svg+xml') { $mimeType = 'svg'; }
		else if ($mimeType == 'jpeg') { $mimeType = 'jpg'; }

		$fileName = sprintf('%s-%s-%s-%s.webp', 
			$type,
			$id,
			$_SERVER['REQUEST_TIME'],
			rtrim(explode('.', str_replace([' ', '(', ')'], '-', $file->getClientOriginalName()))[0], '-')
		);

		$fileName = str_replace(['----', '---', '--'], '-', $fileName);

		if ($mimeType != 'webp') {
			$manager = new ImageManager(['driver' => 'imagick']);
			$data = $manager->make(file_get_contents($file))->encode('webp');
		}

		Storage::put($fileName, $data);
		
		$fileNames[] = [
			'new' => $fileName,
			'old' => $file->getClientOriginalName()
		];
	}

	return $fileNames;
}

