<?php

namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Classes\DataTable;
use App\Classes\DataForm;
use App\Classes\ImageCommon;
use App\Models\Map;
use App\Models\MapAsset;
use App\Models\NotificationEvent;


class MapController extends AdminController
{
  public function show()
  {
		$map = Map::firstOrCreate(['id' => 1]);

		$map = DB::select('SELECT 
			m.*,
			a.fileName
			FROM map AS m
			INNER JOIN asset AS a ON a.id = m.assetId
			WHERE m.id = 1
		');

		$map = ImageCommon::cacheImages($map)[0];
		$map->images = json_decode($map->images, true);

		$icons = DB::select('SELECT 
			ma.id,
			ma.name,
			a.fileName
			FROM map_asset AS ma
			INNER JOIN asset AS a ON a.id = ma.assetId;
		');

		$icons = ImageCommon::cacheImages($icons);

		$mapForm = new DataForm(request(), '/admin-mapUploadMap/');
		$mapForm->setTitle('Upload Map');
		$mapForm->addInput('file', 'image-1', 'Image', null, null, null, true);
		$mapForm = $mapForm->render();

		$iconForm = new DataForm(request(), '/admin-mapAddIcon/');
		$iconForm->setTitle('Upload Icon');
		$iconForm->addInput('file', 'image-2', 'Image', null, null, null, true);
		$iconForm = $iconForm->render();

		$mapAssetTable = new DataTable('map_asset');
		$mapAssetTable->setQuery('SELECT 
			ma.id,
			ma.name,
			a.fileName
			FROM map_asset AS ma
			INNER JOIN asset AS a ON a.id = ma.assetId
		');
		$mapAssetTable->addColumn('id', '#');
		$mapAssetTable->addColumn('name', 'Name', 2, true);
		$mapAssetTable->addJsButton('showImage', ['record:fileName'], 'fa-solid fa-image', 'View Image');
		$mapAssetTable->addJsButton('showDeleteWarning', ['string:Banner', 'record:id', 'url:/admin-mapDeleteIcon/?'], 'fa-solid fa-trash-can', 'Delete Banner');
		$mapAssetTable = $mapAssetTable->render();

		$programme = DB::select('SELECT id, value FROM programme WHERE active = 1 AND type = "music"');

		$scripts = [
		[
			'path' => '/js/multiselect-dropdown.js',
			'loadType' => 'defer',
			'onLoad' => sprintf('initiateMultiSelect()'),
		]
		];

    return view('admin/map', compact(
			'map',
			'icons',
			'mapForm',
			'iconForm',
			'mapAssetTable',
			'programme',
			'scripts',
    ));
  }

	public function toggleMap()
	{
		$map = Map::firstOrCreate(['id' => 1]);
		$map->active = !$map->active;
		$map->save();

		return json_encode($map->active);
	}

	public function uploadMap(Request $request)
	{
		$fileName = ImageCommon::storeImages($request, '1', 'map')[0];

		$map = Map::firstOrCreate(['id' => 1]);
		$map->assetId = $fileName['id'];
		$map->save();

		return redirect('/admin/map');
	}

	public function addIcon(Request $request)
	{
		$fileNames = ImageCommon::storeImages($request, '1', 'map');

		foreach ($fileNames as $fileName) {
			$mapAsset = new MapAsset();
			$mapAsset->name = $fileName['old'];
			$mapAsset->assetId = $fileName['id'];
			$mapAsset->save();
		}

		return redirect('/admin/map');
	}

	public function deleteIcon($id)
	{
		$mapAsset = MapAsset::find($id);
		$mapAsset->delete();

		return redirect('/admin/map');
	}

	public function saveMap(Request $request)
	{
		$map = Map::firstOrCreate([
			'id' => 1,
		]);

		$map->update([
			'assetId' => $request->asset,
			'canvasHeight' => $request->canvas['height'],
			'canvasWidth' => $request->canvas['width'],
			'images' => json_encode($request->images),
			'active' => 1,
		]);

		return true;
	}
}