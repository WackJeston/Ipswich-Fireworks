<?php
namespace App\Http\Controllers;

Use DB;

class SupportersController extends Controller
{
  public function show()
  {
		$records = DB::select('SELECT * FROM supporters WHERE type = "supporter" AND active = 1');

    return view('public/supporters', compact(
			'records',
    ));
  }
}
