<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Enquiry;


class AdminFeedbackProfileController extends Controller
{
  public function show($id)
  {
		$type = 'feedback';

    $enquiry = Enquiry::find($id);

    return view('admin/enquiry-profile', compact(
      'enquiry',
			'type',
    ));
  }
}
