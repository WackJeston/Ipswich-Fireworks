<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Enquiry;


class AdminEnquiryProfileController extends Controller
{
  public function show($id)
  {
    $sessionUser = auth()->user();

    $enquiry = Enquiry::find($id);

    return view('admin/enquiry-profile', compact(
      'sessionUser',
      'enquiry',
    ));
  }
}
