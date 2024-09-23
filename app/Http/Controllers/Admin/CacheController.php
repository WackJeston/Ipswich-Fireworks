<?php
namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Classes\AccessLevelCommon;

class CacheController extends AdminController
{
  public function show()
  {		
		if (!AccessLevelCommon::authorise()) {
			return back()->withErrors(['1' => 'Not Authorised']);
		}
		
    return view('admin/cache');
  }

	public function clearCache(string $key) {
		Cache::forget($key);
		
		return redirect("/admin/settings")->with('message', 'Recached successfully.');
	}
}
