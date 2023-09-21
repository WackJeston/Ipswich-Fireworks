<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\DataTable;
use App\DataForm;
use App\Models\Content;


class AdminFindUsController extends Controller
{
  public function show()
  {
		$gateForm = new DataForm(request(), '/admin-find-usAddGate');
		$gateForm->setTitle('Add Gate');
		$gateForm->addInput('text', 'street', 'Street', null, 100, 1);
		$gateForm->addInput('text', 'description', 'Description', null, 255, 1);
		$gateForm->addInput('text', 'what3words', 'What 3 Words', null, 255, 1);
		$gateForm = $gateForm->render();

		$gateTable = new DataTable('content');
		$gateTable->setQuery('SELECT * FROM content WHERE page = "find-us" AND position = "gate"');
		$gateTable->addColumn('id', '#');
		$gateTable->addColumn('title', 'Street', 3);
		$gateTable->addColumn('description', 'Description', 4);
		// $gateTable->addColumn('active', 'Open', 1, false, 'toggle');
		$gateTable->addColumn('subtitle', 'What 3 Words', 2, true);
		$gateTable->addJsButton('showDeleteWarning', ['string:Gate', 'record:id', 'url:/admin-find-usDeleteGate/?'], 'fa-solid fa-trash-can', 'Delete Gate');
		$gateTable = $gateTable->render();

    return view('admin/find-us', compact(
			'gateForm',
			'gateTable',
    ));
  }

	public function addGate(Request $request)
  {
    $request->validate([
			'street' => 'max:100',
			'description' => 'max:255',
			'what3words' => 'max:255',
    ]);

    Content::create([
			'page' => 'find-us',
			'position' => 'gate',
			'title' => $request->street,
			'subtitle' => $request->what3words,
			'description' => $request->description,
			'active' => false,
    ]);

    return redirect("/admin/find-us")->with('message', 'Gate added successfully.');
  }

	public function deleteGate($id)
	{
		Content::where('id', $id)->delete();

		return redirect("/admin/find-us")->with('message', 'Gate #' . $id . ' deleted successfully.');
	}
}