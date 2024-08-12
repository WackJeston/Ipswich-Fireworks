<?php
namespace App\Http\Controllers\Public;

Use DB;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Enquiry;


class ContactController extends PublicController
{
  public function show()
  {
		$addressRecords = Contact::select('id', 'type', 'value', 'label')->get();

    $address = [];

    foreach ($addressRecords as $record) {
      if($record->type != 'email' && $record->type != 'phone' && $record->type != 'url') {
        $address[$record->type] = $record->value;
      } else {
        $address[$record->type][] = [
					'value' => $record->value,
					'label' => $record->label,
				];
      }
    }

    $contact = [
			'email' => DB::select('SELECT 
				c.type, 
				c.value,
				c.label
				FROM contact AS c
				WHERE c.type = "email"'
			),
			'phone' => DB::select('SELECT 
				c.type, 
				c.value,
				c.label
				FROM contact AS c
				WHERE c.type = "phone"'
			),
			'url' => DB::select('SELECT 
				c.type, 
				c.value,
				c.label
				FROM contact AS c
				WHERE c.type = "url"'
			),
		];

    return view('public/contact', compact(
			'address',
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
			'type' => 'standard',
      'name' => $request->name,
			'email' => $request->email,
			'phone' => $request->phone,
			'subject' => $request->subject,
			'message' => $request->message,
    ]);

    return redirect('/contact')->with('message', 'Enquiry successfully sent.');
	}
}
