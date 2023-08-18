<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\DataTable;
use App\DataForm;
use App\Models\Sponsors;

class AdminSponsorsController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

		$form = new DataForm(request(), '/sponsorsCreate', 'Add');
		$form->setTitle('Add Sponsor');
		$form->addInput('text', 'name', 'Name', null, 255, 1, true);
		$form->addInput('text', 'link', 'Link', null, 255, 0);
		$form->addInput('file', 'fileName', 'Logo');
		$form = $form->render();

		$table = new DataTable('sponsors');
		$table->setQuery('SELECT * FROM sponsors');
		$table->addColumn('id', '#');
		$table->addColumn('name', 'Name', 2);
		$table->addColumn('link', 'Link', 2);
		$table->addColumn('active', 'Active', 1, false, 'toggle');
		$table->addJsButton('showImage', ['record:fileName'], 'fa-solid fa-eye', 'View Image');
		$table->addJsButton('showDeleteWarning', ['string:Sponsor', 'record:id', 'url:/sponsorsDelete/?'], 'fa-solid fa-trash-can', 'Delete Sponsor');

    return view('admin/itinerary', compact(
      'sessionUser',
			'standardForm',
			'standardTable',
			'musicForm',
			'musicTable',
    ));
  }

  // public function create(Request $request)
  // {
  //   $request->validate([
	// 		'value' => 'required|string|max:1000',
  //     'label' => 'max:255',
  //   ]);

  //   Itinerary::create([
	// 		'value' => $request['value'],
	// 		'label' => $request['label'],
	// 	]);

	// 	return redirect("/admin/itinerary")->with('message', 'Standard item created successfully.');
  // }

  // public function delete($id)
  // {
  //   Itinerary::find($id)->delete();

	// 	return redirect("/admin/itinerary")->with('message', "Itinerary item $id created successfully.");
  // }
}
