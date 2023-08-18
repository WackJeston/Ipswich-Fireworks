<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\DataTable;
use App\DataForm;
use App\Models\Itinerary;

class AdminItineraryController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

    $standardForm = new DataForm(request(), '/itineraryCreateStandard', 'Add');
		$standardForm->setTitle('Add Standard Item');
		$standardForm->addInput('text', 'value', 'Value', null, 1000, 1, true);
		$standardForm->addInput('text', 'label', 'Label', null, 255, 0);
		$standardForm = $standardForm->render();

		$standardTable = new DataTable('itinerary_REF_1');
		$standardTable->setQuery('SELECT * FROM itinerary WHERE type = "standard"');
		$standardTable->addColumn('id', '#');
		$standardTable->addColumn('value', 'Value', 2);
		$standardTable->addColumn('label', 'Label', true);
		$standardTable->addColumn('active', 'Active', 1, false, 'toggle');
		$standardTable->addJsButton('showDeleteWarning', ['string:Itinerary', 'record:id', 'url:/itineraryDelete/?'], 'fa-solid fa-trash-can', 'Delete Item');
		$standardTable = $standardTable->render();

		$musicForm = new DataForm(request(), '/itineraryCreateMusic', 'Add');
		$musicForm->setTitle('Add Music Item');
		$musicForm->addInput('text', 'value', 'Value', null, 1000, 1, true);
		$musicForm->addInput('text', 'label', 'Label', null, 255, 0);
		$musicForm = $musicForm->render();

		$musicTable = new DataTable('itinerary_REF_2');
		$musicTable->setQuery('SELECT * FROM itinerary WHERE type = "music"');
		$musicTable->addColumn('id', '#');
		$musicTable->addColumn('value', 'Value', 2);
		$musicTable->addColumn('label', 'Label', true);
		$musicTable->addColumn('active', 'Active', 1, false, 'toggle');
		$musicTable->addJsButton('showDeleteWarning', ['string:Itinerary', 'record:id', 'url:/itineraryDelete/?'], 'fa-solid fa-trash-can', 'Delete Item');
		$musicTable = $musicTable->render();

    return view('admin/itinerary', compact(
      'sessionUser',
			'standardForm',
			'standardTable',
			'musicForm',
			'musicTable',
    ));
  }

  public function createStandard(Request $request)
  {
    $request->validate([
			'value' => 'required|string|max:1000',
      'label' => 'max:255',
    ]);

    Itinerary::create([
			'value' => $request['value'],
			'label' => $request['label'],
		]);

		return redirect("/admin/itinerary")->with('message', 'Standard item created successfully.');
  }

  public function createMusic(Request $request)
  {
    $request->validate([
			'value' => 'required|string|max:1000',
      'label' => 'max:255',
    ]);

    Itinerary::create([
			'type' => 'music',
			'value' => $request['value'],
			'label' => $request['label'],
		]);

		return redirect("/admin/itinerary")->with('message', 'Music item created successfully.');
  }

  public function delete($id)
  {
    Itinerary::find($id)->delete();

		return redirect("/admin/itinerary")->with('message', "Itinerary item $id created successfully.");
  }
}
