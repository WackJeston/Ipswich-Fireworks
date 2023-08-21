<?php
namespace App\Http\Controllers;

Use DB;

class SupportersController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

		$records = DB::select('SELECT * FROM supporters WHERE active = 1');

    return view('public/supporters', compact(
      'sessionUser',
			'records',
    ));
  }
}
