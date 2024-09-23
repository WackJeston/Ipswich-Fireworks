<?php

namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Classes\DataTable;
use App\Classes\DataForm;
use App\Classes\ImageCommon;
use App\Models\Banners;
use App\Models\Content;


class HomePageController extends AdminController
{
  public function show()
  {
		$framingOptions = [];

		$framingOptions[] = ['value' => 'top', 'label' => 'Top'];
		$framingOptions[] = ['value' => 'bottom', 'label' => 'Bottom'];

		$framingOptions2 = $framingOptions;

		$landingZoneBannerForm = new DataForm(request(), '/admin-home-pageAddLandingZoneBanner/', 'Add Banner');
		$landingZoneBannerForm->setTitle('Landing Zone Banner');
		$landingZoneBannerForm->addInput('file', 'image-1', 'Image', null, null, null, true);
		$landingZoneBannerForm->addInput('select', 'framing', 'Framing');
		$landingZoneBannerForm->populateOptions('framing', $framingOptions);
		$landingZoneBannerForm->addInput('text', 'title', 'Title', null, 100);
		$landingZoneBannerForm = $landingZoneBannerForm->render();

		array_unshift($framingOptions2, ['value' => '', 'label' => '']);

		$landingZoneBannerTable = new DataTable('banners_REF_1');
		$landingZoneBannerTable->sequence('parentId');
		$landingZoneBannerTable->setQuery('SELECT 
			b.id,
			b.parentId,
			b.title,
			b.description,
			b.framing,
			b.active,
			b.sequence,
			a.fileName
			FROM banners AS b
			INNER JOIN banners AS b2 ON b2.id = b.parentId
			INNER JOIN asset AS a ON a.id = b.assetId
			WHERE b2.page = "home" 
			AND b2.position = "landingZone"
		');
		$landingZoneBannerTable->addColumn('id', '#');
		$landingZoneBannerTable->addColumn('title', 'Title', 2, true);
		$landingZoneBannerTable->addColumn('framing', 'Framing', 1, true, 'select', $framingOptions2);
		$landingZoneBannerTable->addColumn('active', 'Active', 1, false, 'toggle');
		$landingZoneBannerTable->addJsButton('showImage', ['record:fileName'], 'fa-solid fa-image', 'View Image');
		$landingZoneBannerTable->addJsButton('showDeleteWarning', ['string:Banner', 'record:id', 'url:/admin-home-pageDeleteLandingZoneBanner/?'], 'fa-solid fa-trash-can', 'Delete Banner');
		$landingZoneBannerTable = $landingZoneBannerTable->render();

		$primaryInfo = Content::firstOrCreate([
			'page' => 'home',
			'position' => 'primaryInfo',
		]);

		$primaryInfoForm = new DataForm(request(), '/admin-home-pageUpdatePrimaryInfo/');
		$primaryInfoForm->setTitle('Primary Info Section');
		$primaryInfoForm->addInput('text', 'title', 'Title', $primaryInfo->title, 100);
		$primaryInfoForm->addInput('text', 'subtitle', 'Subtitle', $primaryInfo->subtitle, 255);
		$primaryInfoForm->addInput('textarea', 'description', 'Paragraph', $primaryInfo->description, 1000);
		$primaryInfoForm->addInput('checkbox', 'active', 'Active', $primaryInfo->active);
		$primaryInfoForm = $primaryInfoForm->render();

		$bottomBannerForm = new DataForm(request(), '/admin-home-pageAddBottomBanner/', 'Add Banner');
		$bottomBannerForm->setTitle('Bottom Banner');
		$bottomBannerForm->addInput('file', 'image-2', 'Image', null, null, null, true);
		$bottomBannerForm->addInput('select', 'framing', 'Framing');
		$bottomBannerForm->populateOptions('framing', $framingOptions);
		$bottomBannerForm = $bottomBannerForm->render();

		$bottomBannerTable = new DataTable('banners_REF_2');
		$bottomBannerTable->sequence('parentId');
		$bottomBannerTable->setQuery('SELECT 
			b.id,
			b.parentId,
			b.title,
			b.description,
			b.framing,
			b.active,
			b.sequence,
			a.fileName
			FROM banners AS b
			INNER JOIN banners AS b2 ON b2.id = b.parentId
			INNER JOIN asset AS a ON a.id = b.assetId
			WHERE b2.page = "home" 
			AND b2.position = "bottom"
		');
		$bottomBannerTable->addColumn('id', '#');
		$bottomBannerTable->addColumn('framing', 'Framing', 1, true, 'select', $framingOptions2);
		$bottomBannerTable->addColumn('active', 'Active', 1, false, 'toggle');
		$bottomBannerTable->addJsButton('showImage', ['record:fileName'], 'fa-solid fa-image', 'View Image');
		$bottomBannerTable->addJsButton('showDeleteWarning', ['string:Banner', 'record:id', 'url:/admin-home-pageDeleteLandingZoneBanner/?'], 'fa-solid fa-trash-can', 'Delete Banner');
		$bottomBannerTable = $bottomBannerTable->render();

    return view('admin/home-page', compact(
			'landingZoneBannerForm',
			'landingZoneBannerTable',
			'primaryInfoForm',
			'bottomBannerForm',
			'bottomBannerTable',
    ));
  }

	public function addLandingZoneBanner(Request $request)
	{
		$request->validate([
			'image-1' => 'required|image|mimes:jpg,jpeg,png,svg,webp,webp',
			'title' => 'max:100',
		]);

		$fileNames = ImageCommon::storeImages($request, 'homePageLZ', 'carousel');

		$parentId = Banners::select('id')->where('page', 'home')->where('position', 'landingZone')->first()->id;

		foreach ($fileNames as $fileName) {
			Banners::create([
				'parentId' => $parentId,
				'framing' => $request->framing,
				'title' => $request->title,
				'active' => true,
				'assetId' => $fileName['id'],
			]);
		}

    return redirect("/admin/home-page")->with('message', 'Banner uploaded successfully.');
	}


  public function deleteLandingZoneBanner($id)
  {
    Banners::find($id)->delete();

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
			'image-2' => 'required|image|mimes:jpg,jpeg,png,svg,webp,webp',
		]);

		$fileNames = ImageCommon::storeImages($request, 'homePageBottom', 'carousel');

		$parentId = Banners::select('id')->where('page', 'home')->where('position', 'bottom')->first()->id;

		foreach ($fileNames as $fileName) {
			Banners::create([
				'parentId' => $parentId,
				'framing' => $request->framing,
				'active' => true,
				'assetId' => $fileName['id'],
			]);
		}

    return redirect("/admin/home-page")->with('message', 'Banner uploaded successfully.');
	}


  public function deleteBottomBanner($id)
  {
    Banners::find($id)->delete();

    return redirect("/admin/home-page")->with('message', "Banner #$id has been deleted.");
  }
}