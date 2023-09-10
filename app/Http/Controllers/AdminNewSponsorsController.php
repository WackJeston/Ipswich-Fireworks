<?php

namespace App\Http\Controllers;

use DB;
use App\DataTable;
use App\Models\Enquiry;


class AdminNewSponsorsController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

		$type = 'sponsors';

    $enquiriesTable = new DataTable();
		$enquiriesTable->setQuery('SELECT * FROM enquiry WHERE `type` = "sponsor" ORDER BY id DESC');
		$enquiriesTable->addColumn('id', '#');
		$enquiriesTable->addColumn('name', 'Name', 1, true);
		$enquiriesTable->addColumn('email', 'Email', 2);
		$enquiriesTable->addColumn('created_at', 'Date', 2);
		$enquiriesTable->addLinkButton('new-sponsor-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$enquiriesTable = $enquiriesTable->render();

    return view('admin/enquiries', compact(
      'sessionUser',
			'type',
      'enquiriesTable',
    ));
  }
}
