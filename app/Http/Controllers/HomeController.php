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
			WHERE b.page = "homepage"
			AND b.position = "landingZone"
			AND b.active = 1
		');

    return view('home', compact(
      'sessionUser',
      'landingZoneBanners',
    ));
  }
}
