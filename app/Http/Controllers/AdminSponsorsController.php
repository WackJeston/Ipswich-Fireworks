<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\DataTable;
use App\DataForm;
use App\Models\Supporters;

class AdminSponsorsController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

		$form = new DataForm(request(), '/sponsorsCreate', 'Add');
		$form->setTitle('Add Sponsor');
		$form->addInput('text', 'name', 'Name', null, 255, 1, true);
		$form->addInput('text', 'link', 'Link', null, 255, 0);
		$form->addInput('file', 'fileName', 'Logo', null, null, null, true);
		$form = $form->render();

		$table = new DataTable('sponsors');
		$table->setQuery('SELECT * FROM supporters WHERE type = "sponsor"');
		$table->addColumn('id', '#');
		$table->addColumn('name', 'Name', 2);
		$table->addColumn('link', 'Link', 2);
		$table->addColumn('active', 'Active', 1, false, 'toggle');
		$table->addJsButton('showImage', ['record:fileName'], 'fa-solid fa-eye', 'View Image');
		$table->addJsButton('showDeleteWarning', ['string:Sponsors', 'record:id', 'url:/sponsorsDelete/?'], 'fa-solid fa-trash-can', 'Delete Sponsors');
		$table = $table->render();
		
    return view('admin/sponsors', compact(
      'sessionUser',
			'form',
			'table',
    ));
  }

  public function create(Request $request)
  {
    $request->validate([
			'name' => 'required|max:255',
			'link' => 'max:255',
			'fileName' => 'required|image|mimes:jpg,jpeg,png,svg,webp',
    ]);

		$fileNames = storeImages($request, 'homePageLZ', 'carousel');

		foreach ($fileNames as $fileName) {
			Supporters::create([
				'type' => 'sponsor',
				'name' => $request['name'],
				'link' => $request['link'],
				'fileName' => $fileName['new'],
			]);
		}

		return redirect("/admin/sponsors")->with('message', 'Sponsor created successfully.');
  }

  public function delete($id)
  {
    Supporters::find($id)->delete();

		return redirect("/admin/sponsors")->with('message', "Sponsor #$id deleted successfully.");
  }
}
