<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Enquiry;


class AdminNewSponsorProfileController extends Controller
{
  public function show($id)
  {
		$type = 'sponsor';

    $enquiry = Enquiry::find($id);

    return view('admin/enquiry-profile', compact(
      'enquiry',
			'type',
    ));
  }
}
