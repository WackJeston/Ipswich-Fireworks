<?php
namespace App\Http\Controllers;

Use DB;
use Illuminate\Http\Request;
use App\DataForm;
use App\Models\Enquiry;


class ContactController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

    $contact = [];

    $contact['email'] = DB::select('SELECT 
      c.type, 
      c.value,
			c.label
      FROM contact AS c
      WHERE c.type = "email"'
    );

    $contact['phone'] = DB::select('SELECT 
      c.type, 
      c.value,
			c.label
      FROM contact AS c
      WHERE c.type = "phone"'
    );

    $contact['url'] = DB::select('SELECT 
      c.type, 
      c.value,
			c.label
      FROM contact AS c
      WHERE c.type = "url"'
    );

		$enquiryForm = new DataForm(request(), '/contactCreateEnquiry', 'Send');
		$enquiryForm->addInput('text', 'name', 'Name', null, 255, 1, true);
		$enquiryForm->addInput('email', 'email', 'Email', null, 255, 1, true);
		$enquiryForm->addInput('tel', 'phone', 'Phone', null, 20);
		$enquiryForm->addInput('text', 'subject', 'Subject', null, 255);
		$enquiryForm->addInput('textarea', 'message', 'Message', null, 1000, 1, true);
		$enquiryForm = $enquiryForm->render();


    return view('public/contact', compact(
      'sessionUser',
      'contact',
    ));
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
      'name' => $request->name,
			'email' => $request->email,
			'phone' => $request->phone,
			'subject' => $request->subject,
			'message' => $request->message,
    ]);

    return redirect('/contact')->with('message', 'Enquiry successfully sent.');
	}
}
