<?php
namespace App\Http\Controllers\Public;

Use DB;
use App\Classes\Image;

class ProgrammeController extends PublicController
{
  public function show()
  {
		$standard = DB::select('SELECT * FROM programme WHERE type = "standard" AND active = 1 ORDER BY sequence ASC');
		$music = DB::select('SELECT * FROM programme WHERE type = "music" AND active = 1 ORDER BY sequence ASC');
		$images = DB::select('SELECT 
			p.*,
			a.fileName
			FROM programme AS p
			INNER JOIN asset AS a ON a.id = p.assetId
			AND p.active = 1
			ORDER BY p.sequence ASC
		');

		$images = ImageCommon::cacheImages($images, 800, 800);

    return view('public/programme', compact(
			'standard',
			'music',
			'images',
    ));
  }
}
