<?php

namespace App\Http\Controllers\Public;

use DB;
use Illuminate\Http\Request;
use App\Classes\ImageCommon;
use App\Models\Map;

class MapController extends PublicController
{
  public function show()
  {
		$map = DB::select('SELECT 
			m.*,
			a.fileName
			FROM map AS m
			INNER JOIN asset AS a ON a.id = m.assetId
			WHERE m.id = 1
		');

		if (!$map[0]->active) {
			return redirect('/');
		}

		$map[0]->images = json_decode($map[0]->images, true);

		foreach ($map[0]->images as $i => $image) {
			if ($image['programme'] != 'null') {
				foreach (explode(',', $image['programme']) as $i2 => $programme) {
					// dd($programme);
				}
			}
		}

		$map = ImageCommon::cacheImages($map)[0];

    return view('public/map', compact(
			'map',
    ));
  }
}
