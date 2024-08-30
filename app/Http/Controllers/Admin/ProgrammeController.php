<?php

namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Classes\DataTable;
use App\Classes\DataForm;
use App\Classes\Image;
use App\Models\Programme;

class ProgrammeController extends AdminController
{
  public function show()
  {
    $standardForm = new DataForm(request(), '/programmeCreateStandard', 'Add');
		$standardForm->setTitle('Add Standard Item');
		$standardForm->addInput('text', 'value', 'Name', null, 1000, 1, true);
		$standardForm->addInput('text', 'label', 'Label', null, 255, 0);
		$standardForm = $standardForm->render();

		$standardTable = new DataTable('programme_REF_1');
		$standardTable->sequence('type');
		$standardTable->setQuery('SELECT * FROM programme WHERE type = "standard"');
		$standardTable->addColumn('id', '#');
		$standardTable->addColumn('value', 'Name', 2);
		$standardTable->addColumn('label', 'Label', true);
		$standardTable->addColumn('active', 'Active', 1, false, 'toggle');
		$standardTable->addJsButton('showDeleteWarning', ['string:Programme', 'record:id', 'url:/programmeDelete/?'], 'fa-solid fa-trash-can', 'Delete Item');
		$standardTable = $standardTable->render();

		$musicForm = new DataForm(request(), '/programmeCreateMusic', 'Add');
		$musicForm->setTitle('Add Music Item');
		$musicForm->addInput('text', 'value', 'Name', null, 1000, 1, true);
		$musicForm->addInput('text', 'label', 'Label', null, 255, 0);
		$musicForm->addInput('text', 'stage', 'Stage', null, 255, 0);
		$musicForm->addInput('checkbox', 'timeToggle', 'Time', null, null, null);
		$musicForm->addInput('time', 'time', '', null, null, null);
		$musicForm->addInput('url', 'link',  'Link', null, 255, 1);
		$musicForm->addInput('file', 'image', 'Image', null, null, null);
		$musicForm = $musicForm->render();

		$musicTable = new DataTable('programme_REF_2');
		$musicTable->sequence('type');
		$musicTable->setQuery('SELECT 
			p.*,
			a.fileName
			FROM programme AS p 
			LEFT JOIN asset AS a ON a.id = p.assetId
			WHERE p.type = "music"'
		);
		$musicTable->addColumn('id', '#');
		$musicTable->addColumn('value', 'Name', 3);
		$musicTable->addColumn('label', 'Label', 2, true);
		$musicTable->addColumn('stage', 'Stage', 2);
		$musicTable->addColumn('time', 'Time', 2, true);
		$musicTable->addColumn('link', 'Link' , 3, true);
		$musicTable->addColumn('active', 'Active', 2, false, 'toggle');
		$musicTable->addJsButton('showImage', ['record:fileName'], 'fa-solid fa-eye', 'View Image');
		$musicTable->addJsButton('showDeleteWarning', ['string:Programme', 'record:id', 'url:/programmeDelete/?'], 'fa-solid fa-trash-can', 'Delete Item');
		$musicTable = $musicTable->render();

    return view('admin/programme', compact(
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

    Programme::create([
			'value' => $request['value'],
			'label' => $request['label'],
		]);

		return redirect("/admin/programme")->with('message', 'Standard item created successfully.');
  }

  public function createMusic(Request $request)
  {
    $request->validate([
			'value' => 'required|string|max:1000',
      'label' => 'max:255',
			'stage' => 'max:255',
			'time' => 'max:255',
			'link' => 'max:255',
			'fileName' => 'image|mimes:jpg,jpeg,png,svg,webp',
    ]);

		$fileNames = ImageCommon::storeImages($request, 'music', 'programme');

		$time = null;

		if ($request['timeToggle'] == 'on') {
			$time = $request['time'];
		}

    Programme::create([
			'type' => 'music',
			'value' => $request['value'],
			'label' => $request['label'],
			'stage' => $request['stage'],
			'time' => $time,
			'link' => $request['link'],
			'assetId' => $fileNames[0]['id'] ?? null,
		]);

		return redirect("/admin/programme")->with('message', 'Music item created successfully.');
  }

  public function delete($id)
  {
    Programme::find($id)->delete();

		return redirect("/admin/programme")->with('message', "Programme item $id created successfully.");
  }
}
