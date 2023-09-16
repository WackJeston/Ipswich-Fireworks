<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\DataTable;
use App\DataForm;
use App\Models\Settings;

class AdminSettingsController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

		$settings = Settings::all();

		$form = new DataForm(request(), '/settingsUpdate');

		foreach ($settings as $i => $setting) {
			if ($setting->text != null) {
				$form->addInput('text', $setting->name, ucfirst($setting->name), $setting->text, 255, 1);
			} elseif ($setting->int != null) {
				$form->addInput('num', $setting->name, ucfirst($setting->name), $setting->int, null, null);
			} elseif ($setting->float != null) {
				$form->addInput('num', $setting->name, ucfirst($setting->name), $setting->float, null, null);
			} elseif ($setting->date != null) {
				$form->addInput('text', $setting->name, ucfirst($setting->name), $setting->date, null, null);
			} elseif ($setting->dateTime != null) {
				$form->addInput('text', $setting->name, ucfirst($setting->name), $setting->dateTime, null, null);
			} else {
				$form->addInput('checkbox', $setting->name, ucfirst($setting->name), $setting->active, null, null);
			}
		}

		$form = $form->render();
		
    return view('admin/settings', compact(
      'sessionUser',
			'form',
    ));
  }
}
