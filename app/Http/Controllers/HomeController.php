<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Settings;

class HomeController extends Controller
{
  public function show()
  {
		$ticketDate = date("D jS F", strtotime(Settings::select('date')->where('id', 1)->first()->date));

    $landingZoneBanners = DB::select('SELECT
			b.*
			FROM banners AS b
			WHERE b.page = "home"
			AND b.position = "landingZone"
			AND b.active = 1
		');

		$primaryInfo = DB::select('SELECT * FROM content WHERE active = 1 AND page = "home" AND position = "primaryInfo"')[0];

    $bottomBanners = DB::select('SELECT
			b.*
			FROM banners AS b
			WHERE b.page = "home"
			AND b.position = "bottom"
			AND b.active = 1
		');

    return view('home', compact(
      'landingZoneBanners',
			'primaryInfo',
      'bottomBanners',
			'ticketDate',
    ));
  }
}
