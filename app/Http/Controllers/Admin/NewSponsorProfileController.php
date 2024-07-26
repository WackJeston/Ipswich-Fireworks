<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Enquiry;


class NewSponsorProfileController extends AdminController
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
