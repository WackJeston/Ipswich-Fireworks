<?php
namespace App\Http\Controllers;

Use DB;
use Illuminate\Http\Request;
use App\Models\Enquiry;


class FeedbackController extends Controller
{
  public function show()
  {
    return view('public/feedback');
  }


	public function createEnquiry(Request $request) 
	{
		$request->validate([
      'name' => 'required|max:255',
      'email' => 'required|max:255',
      'phone' => 'max:20',
			'subject' => 'max:255',
			'message' => 'required|max:1000',
    ]);

    Enquiry::create([
			'type' => 'feedback',
      'name' => $request->name,
			'email' => $request->email,
			'phone' => $request->phone,
			'subject' => $request->subject,
			'message' => $request->message,
    ]);

    return redirect('/feedback')->with('message', 'Feedback successfully sent.');
	}
}
