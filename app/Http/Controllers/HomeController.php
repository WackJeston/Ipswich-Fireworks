<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

    $landingZoneBanners = DB::select('SELECT
			b.*
			FROM banners AS b
			WHERE b.page = "home"
			AND b.position = "landingZone"
			AND b.active = 1
		');

		$content1 = DB::select('SELECT * FROM content WHERE page = "home" AND position = "1"');
		if (!empty($content1)) {
			$content1 = $content1[0];
		}

    return view('home', compact(
      'sessionUser',
      'landingZoneBanners',
			'content1',
    ));
  }
}
