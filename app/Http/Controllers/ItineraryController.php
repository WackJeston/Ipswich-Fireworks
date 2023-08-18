<?php
namespace App\Http\Controllers;

Use DB;

class ItineraryController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

		$standard = DB::select('SELECT * FROM itinerary WHERE type = "standard" AND active = 1');
		$music = DB::select('SELECT * FROM itinerary WHERE type = "music" AND active = 1');

    return view('public/itinerary', compact(
      'sessionUser',
			'standard',
			'music',
    ));
  }
}
