<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\DataTable;
use App\DataForm;
use App\Models\Banners;


class AdminHomePageController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

		$landingZoneBannerForm = new DataForm(request(), '/admin-home-pageAddLandingZoneBanner', 'Add Banner');
		$landingZoneBannerForm->setTitle('Add Landing Zone Banner');
		$landingZoneBannerForm->addInput('file', 'image', 'Image', null, null, null, true);
		$landingZoneBannerForm->addInput('text', 'name', 'Rename', null, 100, 1);
		$landingZoneBannerForm = $landingZoneBannerForm->render();

		$landingZoneBannerTable = new DataTable('banners');
		$landingZoneBannerTable->setQuery('SELECT * FROM banners WHERE page = "homepage" AND position = "landingZone"');
		$landingZoneBannerTable->addColumn('id', '#');
		$landingZoneBannerTable->addColumn('name', 'Name');
		$landingZoneBannerTable->addColumn('active', 'Active', 1, false, 'toggle');
		$landingZoneBannerTable->addJsButton('showImage', ['record:fileName'], 'fa-solid fa-eye', 'View Image');
		$landingZoneBannerTable->addJsButton('showDeleteWarning', ['string:Category', 'record:id', 'url:/admin-home-pageDeleteLandingZoneBanner/?'], 'fa-solid fa-trash-can', 'Delete Banner');
		$landingZoneBannerTable = $landingZoneBannerTable->render();


    return view('admin/home-page', compact(
      'sessionUser',
			'landingZoneBannerForm',
			'landingZoneBannerTable',
    ));
  }

	public function addLandingZoneBanner(Request $request) {
		$fileNames = storeImages($request, 'homePageLZ', 'carousel');

		foreach ($fileNames as $fileName) {
			Banners::create([
				'page' => 'homepage',
				'position' => 'landingZone',
				'name' => !empty($request->name) ? $request->name : $fileName['old'],
				'fileName' => $fileName['new'],
				'primary' => 0,
			]);
		}

    return redirect("/admin/home-page")->with('message', 'Banner uploaded successfully.');
	}


  public function deleteLandingZoneBanner($id)
  {
    $banner = Banners::where('id', $id)->first();
    
    deleteS3($banner->fileName);

    Banners::find($banner->id)->delete();

    return redirect("/admin/home-page/$id")->with('message', "Banner #$id has been deleted.");
  }
}
