<?php
namespace App\Http\Controllers;

Use DB;
use Illuminate\Support\Facades\Storage;

class ProgrammeController extends Controller
{
  public function show()
  {
		$standard = DB::select('SELECT * FROM programme WHERE type = "standard" AND active = 1');
		$music = DB::select('SELECT * FROM programme WHERE type = "music" AND active = 1');
		$images = DB::select('SELECT * FROM programme WHERE fileName IS NOT NULL AND active = 1');

		foreach ($images as $i => $image) {
			$image->fileName = Storage::disk('s3')->temporaryUrl(
				$image->fileName, now()->addMinutes(10)
			);
		}

    return view('public/programme', compact(
			'standard',
			'music',
			'images',
    ));
  }
}
