<?php

namespace App\Http\Controllers\Public;

use DB;
use Illuminate\Http\Request;
use App\Classes\ImageCommon;
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
			INNER JOIN banners AS b2 ON b2.id = b.parentId
			INNER JOIN asset AS a ON a.id = b.assetId
			WHERE b2.page = "home" 
			AND b2.position = "landingZone"
			AND b2.active = 1
			AND b.active = 1
			ORDER BY b.sequence ASC
		');

		$landingZoneBanners = ImageCommon::cacheImages($landingZoneBanners, 2400, 2400);
		if (!empty($landingZoneBanners)) {
			ImageCommon::preloadImage($landingZoneBanners[0]->fileName);
		}

		$primaryInfo = DB::select('SELECT * FROM content WHERE active = 1 AND page = "home" AND position = "primaryInfo"')[0];

    $bottomBanners = DB::select('SELECT 
			b.*,
			a.fileName
			FROM banners AS b
			INNER JOIN banners AS b2 ON b2.id = b.parentId
			INNER JOIN asset AS a ON a.id = b.assetId
			WHERE b2.page = "home" 
			AND b2.position = "bottom"
			AND b2.active = 1
			AND b.active = 1
			ORDER BY b.sequence ASC
		');

		$bottomBanners = ImageCommon::cacheImages($bottomBanners, 2400, 2400);

    return view('public/home', compact(
      'landingZoneBanners',
			'primaryInfo',
      'bottomBanners',
			'ticketDate',
    ));
  }
}
