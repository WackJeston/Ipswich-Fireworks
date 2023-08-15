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

		$ticketNotice = DB::select('SELECT * FROM content WHERE active = 1 AND page = "home" AND position = "ticketNotice"')[0];

    return view('home', compact(
      'sessionUser',
      'landingZoneBanners',
			'primaryInfo',
			'ticketNotice',
    ));
  }
}
