<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Enquiry;


class AdminFeedbackProfileController extends Controller
{
  public function show($id)
  {
    $sessionUser = auth()->user();

		$type = 'feedback';

    $enquiry = Enquiry::find($id);

    return view('admin/enquiry-profile', compact(
      'sessionUser',
      'enquiry',
			'type',
    ));
  }
}
