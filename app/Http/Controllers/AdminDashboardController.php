<?php

namespace App\Http\Controllers;

use DB;
use App\DataTable;


class AdminDashboardController extends Controller
{
  public function show()
  {
		$enquiriesTable = new DataTable();
		$enquiriesTable->setQuery('SELECT * FROM enquiry', [], 'created_at', 'DESC');
		$enquiriesTable->setTitle('New Enquiries');
		$enquiriesTable->addColumn('id', '#');
		$enquiriesTable->addColumn('type', 'Type', 2);
		$enquiriesTable->addColumn('subject', 'Subject', 3);
		$enquiriesTable->addColumn('email', 'Email', 4);
		$enquiriesTable->addLinkButton('enquiry-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$enquiriesTable = $enquiriesTable->render();

		return view('admin/dashboard', compact(
      'enquiriesTable',
    ));
  }
}
