<?php

namespace App\Http\Controllers;

use DB;
use App\DataTable;
use App\Models\Enquiry;


class FeedbackController extends AdminController
{
  public function show()
  {
		$type = 'feedback';

    $enquiriesTable = new DataTable();
		$enquiriesTable->setQuery('SELECT * FROM enquiry WHERE `type` = "feedback"', [], 'id', 'DESC');
		$enquiriesTable->addColumn('id', '#');
		$enquiriesTable->addColumn('name', 'Name', 1, true);
		$enquiriesTable->addColumn('email', 'Email', 2);
		$enquiriesTable->addColumn('subject', 'Subject', 2, true);
		$enquiriesTable->addColumn('created_at', 'Date', 2);
		$enquiriesTable->addLinkButton('feedback-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$enquiriesTable = $enquiriesTable->render();

    return view('admin/enquiries', compact(
			'type',
      'enquiriesTable',
    ));
  }
}
