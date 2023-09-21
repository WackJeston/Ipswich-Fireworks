<?php
namespace App\Http\Controllers;

Use DB;

class ProgrammeController extends Controller
{
  public function show()
  {
		$standard = DB::select('SELECT * FROM programme WHERE type = "standard" AND active = 1');
		$music = DB::select('SELECT * FROM programme WHERE type = "music" AND active = 1');

    return view('public/programme', compact(
			'standard',
			'music',
    ));
  }
}
