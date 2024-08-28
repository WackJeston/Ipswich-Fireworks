<?php
namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use App\Classes\DataTable;
use App\Classes\DataForm;

class CronsController extends AdminController
{
  public function show()
  {
		$cronJobs = new DataTable('cron_jobs');
		$cronJobs->setQuery('SELECT * FROM cron_jobs', [], 'id', 'ASC');
		$cronJobs->addColumn('id', '#');
		$cronJobs->addColumn('command', 'Command', 2);
		$cronJobs->addColumn('schedule', 'Schedule', 2);
		$cronJobs->addColumn('lastRunOn', 'Last Run On', 2, true);
		$cronJobs->addColumn('lastRunTime', 'Last Run Time', 2, true);
		$cronJobs->addColumn('active', 'Active', 1, false, 'toggle');
		// $cronJobs->addLinkButton('enquiry-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$cronJobs = $cronJobs->render();
		
    return view('admin/crons', compact(
			'cronJobs',
    ));
  }
}
