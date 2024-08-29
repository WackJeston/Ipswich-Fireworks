<?php
namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CacheController extends AdminController
{
  public function show()
  {		
    return view('admin/cache');
  }

	public function clearCache(string $key) {
		Cache::forget($key);
		
		return redirect("/admin/settings")->with('message', 'Recached successfully.');
	}
}
