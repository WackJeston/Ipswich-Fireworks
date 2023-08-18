<?php

namespace App\Http\Controllers;

use DB;
use App\DataTable;
use App\Models\Enquiry;


class AdminFeedbackController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

		$type = 'feedback';

    $enquiriesTable = new DataTable();
		$enquiriesTable->setQuery('SELECT * FROM enquiry WHERE `type` = "feedback" ORDER BY id DESC');
		$enquiriesTable->addColumn('id', '#');
		$enquiriesTable->addColumn('name', 'Name', 1, true);
		$enquiriesTable->addColumn('email', 'Email', 2);
		$enquiriesTable->addColumn('subject', 'Subject', 2);
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
