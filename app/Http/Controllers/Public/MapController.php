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

		$map = ImageCommon::cacheImages($map)[0];
		$map->images = json_decode($map->images, true);

    return view('public/map', compact(
			'map',
    ));
  }
}
