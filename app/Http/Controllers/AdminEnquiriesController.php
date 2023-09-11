<?php

namespace App\Http\Controllers;

use DB;
use App\DataTable;
use App\Models\Enquiry;


class AdminEnquiriesController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

		$type = 'standard';

    $enquiriesTable = new DataTable();
		$enquiriesTable->setQuery('SELECT * FROM enquiry WHERE `type` = "standard" ORDER BY id DESC');
		$enquiriesTable->addColumn('id', '#');
		$enquiriesTable->addColumn('name', 'Name', 1, true);
		$enquiriesTable->addColumn('email', 'Email', 2);
		$enquiriesTable->addColumn('subject', 'Subject', 2, true);
		$enquiriesTable->addColumn('created_at', 'Date', 2);
		$enquiriesTable->addLinkButton('enquiry-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$enquiriesTable = $enquiriesTable->render();

    return view('admin/enquiries', compact(
      'sessionUser',
			'type',
      'enquiriesTable',
    ));
  }
}
