<?php

namespace App\Http\Controllers\Public;

use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Settings;

class HomeController extends PublicController
{
  public function show()
  {
		$ticketDate = date("D jS F", strtotime(Settings::select('date')->where('id', 1)->first()->date));

    $landingZoneBanners = DB::select('SELECT
			b.*,
			a.fileName
			FROM banners AS b
			LEFT JOIN asset AS a ON a.id = b.assetId
			WHERE b.page = "home"
			AND b.position = "landingZone"
			AND b.active = 1
		');

		$landingZoneBanners = cacheImages($landingZoneBanners, 2400, 2400);
		if (!empty($landingZoneBanners)) {
			preloadImage($landingZoneBanners[0]->fileName);
		}

		$primaryInfo = DB::select('SELECT * FROM content WHERE active = 1 AND page = "home" AND position = "primaryInfo"')[0];

    $bottomBanners = DB::select('SELECT
			b.*
			FROM banners AS b
			WHERE b.page = "home"
			AND b.position = "bottom"
			AND b.active = 1
		');

		$bottomBanners = cacheImages($bottomBanners, 2400, 2400);

    return view('public/home', compact(
      'landingZoneBanners',
			'primaryInfo',
      'bottomBanners',
			'ticketDate',
    ));
  }
}
