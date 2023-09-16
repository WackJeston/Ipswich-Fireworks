<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

    return view('public/site-map', compact(
      'sessionUser',
    ));
  }
}
