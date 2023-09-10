<?php
namespace App\Http\Controllers;

Use DB;

class SponsorsController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

		$records = DB::select('SELECT * FROM supporters WHERE type = "sponsor" AND active = 1');

    return view('public/sponsors', compact(
      'sessionUser',
			'records',
    ));
  }
}
