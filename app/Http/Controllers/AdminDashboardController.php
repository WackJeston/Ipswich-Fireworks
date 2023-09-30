<?php

namespace App\Http\Controllers;

use DB;
use App\DataTable;


class AdminDashboardController extends Controller
{
  public function show()
  {
		$enquiriesTable = new DataTable();
		$enquiriesTable->setQuery('SELECT * FROM enquiry ORDER BY created_at DESC');
		$enquiriesTable->setTitle('New Enquiries');
		$enquiriesTable->addColumn('id', '#');
		$enquiriesTable->addColumn('email', 'Email', 3);
		$enquiriesTable->addColumn('subject', 'Subject', 2);
		$enquiriesTable->addLinkButton('enquiry-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$enquiriesTable = $enquiriesTable->render();

		return view('admin/dashboard', compact(
      'enquiriesTable',
    ));
  }
}
