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
		$images = DB::select('SELECT 
			p.*,
			a.fileName
			FROM programme AS p
			INNER JOIN asset AS a ON a.id = p.assetId
			AND p.active = 1
		');

		$images = cacheImages($images, 800, 800);

    return view('public/programme', compact(
			'standard',
			'music',
			'images',
    ));
  }
}
