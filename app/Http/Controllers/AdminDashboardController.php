<?php

namespace App\Http\Controllers;


class AdminDashboardController extends Controller
{
  public function show()
  {
    return view('admin/dashboard');
  }
}
