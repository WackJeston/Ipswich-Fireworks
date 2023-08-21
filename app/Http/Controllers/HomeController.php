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

		$primaryInfo = DB::select('SELECT * FROM content WHERE active = 1 AND page = "home" AND position = "primaryInfo"')[0];

		$aboutUs = [];

		$aboutUs[0] = DB::select('SELECT * FROM content WHERE active = 1 AND page = "home" AND position = "aboutUs_1"')[0];
		$aboutUs[1] = DB::select('SELECT * FROM content WHERE active = 1 AND page = "home" AND position = "aboutUs_2"')[0];
		$aboutUs[2] = DB::select('SELECT * FROM content WHERE active = 1 AND page = "home" AND position = "aboutUs_3"')[0];

    return view('home', compact(
      'sessionUser',
      'landingZoneBanners',
			'primaryInfo',
			'aboutUs',
    ));
  }
}
