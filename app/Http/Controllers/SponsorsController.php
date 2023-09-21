<?php
namespace App\Http\Controllers;

Use DB;
use Illuminate\Http\Request;
use App\Models\Enquiry;

class SponsorsController extends Controller
{
  public function show()
  {
		$records = DB::select('SELECT * FROM supporters WHERE type = "sponsor" AND active = 1');

    return view('public/sponsors', compact(
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
			'type' => 'sponsor',
      'name' => $request->name,
			'email' => $request->email,
			'phone' => $request->phone,
			'message' => $request->message,
    ]);

    return redirect('/sponsors')->with('message', 'Enquiry successfully sent.');
	}
}
