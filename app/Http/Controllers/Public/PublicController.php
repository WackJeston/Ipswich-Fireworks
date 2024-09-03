<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\View;
use App\Classes\Application;

class PublicController extends Controller
{
  public function __construct()
	{
		Application::initiatePublic();
	}
}
