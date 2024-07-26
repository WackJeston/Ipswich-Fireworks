<?php
namespace App\Http\Controllers\Public;

Use DB;
use Illuminate\Support\Facades\Storage;

class ProgrammeController extends PublicController
{
  public function show()
  {
		$standard = DB::select('SELECT * FROM programme WHERE type = "standard" AND active = 1');
		$music = DB::select('SELECT * FROM programme WHERE type = "music" AND active = 1');
		$images = DB::select('SELECT * FROM programme WHERE fileName IS NOT NULL AND active = 1');

		$images = getS3Url($images);

    return view('public/programme', compact(
			'standard',
			'music',
			'images',
    ));
  }
}
