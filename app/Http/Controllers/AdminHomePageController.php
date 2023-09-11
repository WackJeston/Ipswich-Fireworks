<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\DataTable;
use App\DataForm;
use App\Models\Banners;
use App\Models\Content;
use App\Models\Settings;


class AdminHomePageController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

		$framingOptions = [];

		$framingOptions[] = ['value' => 'top', 'label' => 'Top'];
		$framingOptions[] = ['value' => 'bottom', 'label' => 'Bottom'];
		

		$landingZoneBannerForm = new DataForm(request(), '/admin-home-pageAddLandingZoneBanner', 'Add Banner');
		$landingZoneBannerForm->setTitle('Landing Zone Banner');
		$landingZoneBannerForm->addInput('file', 'image-1', 'Image', null, null, null, true);
		$landingZoneBannerForm->addInput('text', 'name', 'Rename', null, 100, 1);
		$landingZoneBannerForm->addInput('select', 'framing', 'Framing');
		$landingZoneBannerForm->populateOptions('framing', $framingOptions);
		$landingZoneBannerForm = $landingZoneBannerForm->render();

		$landingZoneBannerTable = new DataTable('banners_REF_1');
		$landingZoneBannerTable->setQuery('SELECT * FROM banners WHERE page = "home" AND position = "landingZone"');
		$landingZoneBannerTable->addColumn('id', '#');
		$landingZoneBannerTable->addColumn('name', 'Name', 2);
		$landingZoneBannerTable->addColumn('active', 'Active', 1, false, 'toggle');
		$landingZoneBannerTable->addColumn('framing', 'Framing', 1, true);
		$landingZoneBannerTable->addJsButton('showImage', ['record:fileName'], 'fa-solid fa-eye', 'View Image');
		$landingZoneBannerTable->addJsButton('showDeleteWarning', ['string:Banner', 'record:id', 'url:/admin-home-pageDeleteLandingZoneBanner/?'], 'fa-solid fa-trash-can', 'Delete Banner');
		$landingZoneBannerTable = $landingZoneBannerTable->render();

		$primaryInfo = Content::firstOrCreate([
			'page' => 'home',
			'position' => 'primaryInfo',
		]);

		$primaryInfoForm = new DataForm(request(), '/admin-home-pageUpdatePrimaryInfo');
		$primaryInfoForm->setTitle('Primary Info Section');
		$primaryInfoForm->addInput('text', 'title', 'Title', $primaryInfo->title, 100);
		$primaryInfoForm->addInput('text', 'subtitle', 'Subtitle', $primaryInfo->subtitle, 255);
		$primaryInfoForm->addInput('textarea', 'description', 'Paragraph', $primaryInfo->description, 1000);
		$primaryInfoForm->addInput('checkbox', 'active', 'Active', $primaryInfo->active);
		$primaryInfoForm = $primaryInfoForm->render();

		$bottomBannerForm = new DataForm(request(), '/admin-home-pageAddBottomBanner', 'Add Banner');
		$bottomBannerForm->setTitle('Bottom Banner');
		$bottomBannerForm->addInput('file', 'image-2', 'Image', null, null, null, true);
		$bottomBannerForm->addInput('text', 'name', 'Rename', null, 100, 1);
		$bottomBannerForm = $bottomBannerForm->render();

		$bottomBannerTable = new DataTable('banners_REF_2');
		$bottomBannerTable->setQuery('SELECT * FROM banners WHERE page = "home" AND position = "bottom"');
		$bottomBannerTable->addColumn('id', '#');
		$bottomBannerTable->addColumn('name', 'Name', 2);
		$bottomBannerTable->addColumn('active', 'Active', 1, false, 'toggle');
		$bottomBannerTable->addJsButton('showImage', ['record:fileName'], 'fa-solid fa-eye', 'View Image');
		$bottomBannerTable->addJsButton('showDeleteWarning', ['string:Banner', 'record:id', 'url:/admin-home-pageDeleteLandingZoneBanner/?'], 'fa-solid fa-trash-can', 'Delete Banner');
		$bottomBannerTable = $bottomBannerTable->render();

    return view('admin/home-page', compact(
      'sessionUser',
			'landingZoneBannerForm',
			'landingZoneBannerTable',
			'primaryInfoForm',
			'bottomBannerForm',
			'bottomBannerTable',
    ));
  }

	public function addLandingZoneBanner(Request $request) {
		$request->validate([
			'name' => 'max:100',
			'image-1' => 'required|image|mimes:jpg,jpeg,png,svg,webp,webp',
		]);

		$fileNames = storeImages($request, 'homePageLZ', 'carousel');

		foreach ($fileNames as $fileName) {
			Banners::create([
				'page' => 'home',
				'position' => 'landingZone',
				'framing' => $request->framing,
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

    return redirect("/admin/home-page")->with('message', "Banner #$id has been deleted.");
  }


	public function updatePrimaryInfo(Request $request)
  {
    $request->validate([
      'title' => 'max:100',
			'subtitle' => 'max:255',
			'description' => 'max:5000',
    ]);

		$active = isset($request->active) ? 1 : 0;

    Content::where('page', 'home')->where('position', 'primaryInfo')->update([
			'title' => $request->title,
			'subtitle' => $request->subtitle,
			'description' => $request->description,
			'active' => $active,
    ]);

    return redirect("/admin/home-page")->with('message', 'Primary info updated successfully.');
  }

	public function addBottomBanner(Request $request) {
		$request->validate([
			'name' => 'max:100',
			'image-2' => 'required|image|mimes:jpg,jpeg,png,svg,webp,webp',
		]);

		$fileNames = storeImages($request, 'homePageLZ', 'carousel');

		foreach ($fileNames as $fileName) {
			Banners::create([
				'page' => 'home',
				'position' => 'bottom',
				'name' => !empty($request->name) ? $request->name : $fileName['old'],
				'fileName' => $fileName['new'],
				'primary' => 0,
			]);
		}

    return redirect("/admin/home-page")->with('message', 'Banner uploaded successfully.');
	}


  public function deleteBottomBanner($id)
  {
    $banner = Banners::where('id', $id)->first();
    
    deleteS3($banner->fileName);

    Banners::find($banner->id)->delete();

    return redirect("/admin/home-page")->with('message', "Banner #$id has been deleted.");
  }
}