<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Support\Facades\Mail;
// use App\Mail\EnquiryStandard;
// use App\Models\Enquiry;


class AdminEnquiryProfileController extends Controller
{
  public function show($id)
  {
    $sessionUser = auth()->user();

		$type = 'standard';

    $enquiry = Enquiry::find($id);

		// Mail::to('33kcaj33@gmail.com')->send(new EnquiryStandard());

    return view('admin/enquiry-profile', compact(
      'sessionUser',
      'enquiry',
			'type',
    ));
  }
}
