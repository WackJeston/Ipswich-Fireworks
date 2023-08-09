<?php

namespace App\Http\Controllers;

use DB;
use App\Models\LandingZoneCarousels;
use App\Models\LandingZones;
use Illuminate\Http\Request;

class HomeController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

    // $landingZoneCarouselPre = LandingZoneCarousels::where('landingZoneId', 1)
    //   ->orderBy('primary', 'desc')
    // ->get();

    // $landingZoneCarousel = $landingZoneCarouselPre->toJson();
    // $landingZoneCarouselShow = LandingZones::where('id', 1)->pluck('show')->first();

    return view('home', compact(
      'sessionUser',
      // 'landingZoneCarousel',
      // 'landingZoneCarouselShow',
    ));
  }
}
