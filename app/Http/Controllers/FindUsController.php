<?php
namespace App\Http\Controllers;

Use DB;


class FindUsController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

		$contactPre = DB::select('SELECT 
      c.type, 
      c.value
      FROM contact AS c
      WHERE c.type IN
      (
        "line1",
        "line2",
        "city",
        "region",
        "postcode",
        "lat",
        "lng"
      )'
    );

    $contact = [];

    foreach ($contactPre as $i => $value) {
      $contact[$value->type] = $value->value;
    }

    $coordinatesPre = DB::select('SELECT
			c.type, 
			c.value
			FROM contact AS c
			WHERE c.type IN ("lat", "lng")
		');

		$coordinates = [];

		if (isset($coordinatesPre[0]->value)) {
			$coordinates['lat'] = (float) $coordinatesPre[0]->value;
		}

		if (isset($coordinatesPre[1]->value)) {
			$coordinates['lng'] = (float) $coordinatesPre[1]->value;
		}

		$gates = DB::select('SELECT * FROM content WHERE `page` = "find-us" AND `position` = "gate"');

    return view('public/find-us', compact(
      'sessionUser',
			'contact',
			'coordinates',
			'gates'
    ));
  }
}
