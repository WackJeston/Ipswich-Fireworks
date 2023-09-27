<?php
namespace App\Http\Controllers;

Use DB;
use Illuminate\Http\Request;
use App\Models\Enquiry;

class SupportersController extends Controller
{
  public function show()
  {
		$records = DB::select('SELECT * FROM supporters WHERE active = 1');

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
