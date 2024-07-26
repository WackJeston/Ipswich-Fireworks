<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\DataTable;
use App\DataForm;
use App\Models\Supporters;

class SupportersController extends AdminController
{
  public function show()
  {
		$form = new DataForm(request(), '/supportersCreate', 'Add');
		$form->setTitle('Add Supporter / Sponsor');
		$form->addInput('text', 'name', 'Name', null, 255, 1, true);
		$form->addInput('text', 'link', 'Link', null, 255, 0);
		$form->addInput('file', 'fileName', 'Logo', null, null, null, true);
		$form = $form->render();

		$table = new DataTable('supporters');
		$table->setQuery('SELECT * FROM supporters');
		$table->addColumn('id', '#');
		$table->addColumn('name', 'Name', 2);
		$table->addColumn('link', 'Link', 2, true);
		$table->addColumn('active', 'Active', 1, false, 'toggle');
		$table->addJsButton('showImage', ['record:fileName'], 'fa-solid fa-eye', 'View Image');
		$table->addJsButton('showDeleteWarning', ['string:Supporter', 'record:id', 'url:/supportersDelete/?'], 'fa-solid fa-trash-can', 'Delete Supporter');
		$table = $table->render();
		
    return view('admin/supporters', compact(
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
				'name' => $request['name'],
				'link' => $request['link'],
				'fileName' => $fileName['new'],
			]);
		}

		return redirect("/admin/supporters")->with('message', 'Supporter created successfully.');
  }

  public function delete($id)
  {
		$supporter = Supporters::where('id', $id)->first();
    Storage::delete($supporter->fileName);
    Supporters::find($id)->delete();

		return redirect("/admin/supporters")->with('message', "Supporter #$id deleted successfully.");
  }
}
