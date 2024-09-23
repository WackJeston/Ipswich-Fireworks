<?php
namespace App\Http\Controllers\Admin;

use DB;
use App\Classes\DataTable;


class DashboardController extends AdminController
{
  public function show()
  {
		$enquiriesTable = new DataTable('enquiry');
		$enquiriesTable->setQuery('SELECT 
			*,
			DATE_FORMAT(created_at, "%%d/%%m/%%Y %%H:%%i:%%s") AS `date`
			FROM enquiry', 
			[], 
			'id', 
			'DESC'
		);
		$enquiriesTable->setTitle('New Enquiries');
		$enquiriesTable->addColumn('id', '#');
		$enquiriesTable->addColumn('type', 'Type', 2);
		$enquiriesTable->addColumn('name', 'Name', 2, true);
		// $enquiriesTable->addColumn('email', 'Email', 3);
		$enquiriesTable->addColumn('subject', 'Subject', 3);
		$enquiriesTable->addColumn('date', 'Date', 3, true);
		$enquiriesTable->addLinkButton('enquiry-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$enquiriesTable = $enquiriesTable->render();

		return view('admin/dashboard', compact(
      'enquiriesTable',
    ));
  }
}
