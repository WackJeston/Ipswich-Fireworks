<?php
namespace App\Http\Controllers\Public;

Use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Enquiry;

class SupportersController extends PublicController
{
  public function show()
  {
		$records = DB::select('SELECT 
			s.*,
			a.fileName
			FROM supporters AS s
			INNER JOIN asset AS a ON a.id = s.assetId
			WHERE s.active = 1
		');

		$records = cacheImages($records, 800, 800);

    return view('public/supporters', compact(
			'records',
    ));
  }

	public function createEnquiry(Request $request) 
	{
		$request->validate([
      'name' => 'required|max:255',
      'email' => 'required|max:255',
      'phone' => 'max:20',
			'message' => 'max:1000',
    ]);

    Enquiry::create([
			'type' => 'new sponsor',
      'name' => $request->name,
			'email' => $request->email,
			'phone' => $request->phone,
			'message' => $request->message,
    ]);

    return redirect('/supporters')->with('message', 'Enquiry successfully sent.');
	}
}
