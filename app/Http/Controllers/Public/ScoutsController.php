<?php

namespace App\Http\Controllers\Public;

use DB;
use Illuminate\Http\Request;

class ScoutsController extends PublicController
{
  public function show()
  {
		$aboutUs = [];

		$aboutUs[0] = DB::select('SELECT * FROM content WHERE active = 1 AND page = "scouts" AND position = "aboutUs_1"')[0];
		$aboutUs[1] = DB::select('SELECT * FROM content WHERE active = 1 AND page = "scouts" AND position = "aboutUs_2"')[0];
		$aboutUs[2] = DB::select('SELECT * FROM content WHERE active = 1 AND page = "scouts" AND position = "aboutUs_3"')[0];

    return view('public/scouts', compact(
			'aboutUs',
    ));
  }
}
