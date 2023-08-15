<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\DataTable;
use App\DataForm;
use App\Models\Banners;
use App\Models\Content;


class AdminHomePageController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

		$landingZoneBannerForm = new DataForm(request(), '/admin-home-pageAddLandingZoneBanner', 'Add Banner');
		$landingZoneBannerForm->setTitle('Landing Zone Banner');
		$landingZoneBannerForm->addInput('file', 'image', 'Image', null, null, null, true);
		$landingZoneBannerForm->addInput('text', 'name', 'Rename', null, 100, 1);
		$landingZoneBannerForm = $landingZoneBannerForm->render();

		$landingZoneBannerTable = new DataTable('banners');
		$landingZoneBannerTable->setQuery('SELECT * FROM banners WHERE page = "home" AND position = "landingZone"');
		$landingZoneBannerTable->addColumn('id', '#');
		$landingZoneBannerTable->addColumn('name', 'Name', 2);
		$landingZoneBannerTable->addColumn('active', 'Active', 1, false, 'toggle');
		$landingZoneBannerTable->addJsButton('showImage', ['record:fileName'], 'fa-solid fa-eye', 'View Image');
		$landingZoneBannerTable->addJsButton('showDeleteWarning', ['string:Banner', 'record:id', 'url:/admin-home-pageDeleteLandingZoneBanner/?'], 'fa-solid fa-trash-can', 'Delete Banner');
		$landingZoneBannerTable = $landingZoneBannerTable->render();

		$content1 = DB::select('SELECT * FROM content WHERE page = "home" AND position = "1"');

		if (empty($content1)) {
			Content::create([
				'page' => 'home',
				'position' => '1',
			]);

			$content1 = DB::select('SELECT * FROM content WHERE page = "home" AND position = "1"');
		}

		$content1 = $content1[0];

		$content1Form = new DataForm(request(), '/admin-home-pageUpdateContent1');
		$content1Form->setTitle('Primary Info Section');
		$content1Form->addInput('text', 'title', 'Title', $content1->title, 100);
		$content1Form->addInput('text', 'subtitle', 'Subtitle', $content1->subtitle, 255);
		$content1Form->addInput('textarea', 'description', 'Description', $content1->description, 1000);
		$content1Form->addInput('checkbox', 'active', 'Active', $content1->active);
		// $content1Form->addInput('file', 'image', 'Image', null, null, null, true);
		// $content1Form->addInput('text', 'name', 'Rename', null, 100, 1);
		$content1Form = $content1Form->render();

    return view('admin/home-page', compact(
      'sessionUser',
			'landingZoneBannerForm',
			'landingZoneBannerTable',
			'content1Form',
    ));
  }

	public function addLandingZoneBanner(Request $request) {
		$fileNames = storeImages($request, 'homePageLZ', 'carousel');

		foreach ($fileNames as $fileName) {
			Banners::create([
				'page' => 'home',
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

    return redirect("/admin/home-page")->with('message', "Banner #$id has been deleted.");
  }


	public function updateContent1(Request $request)
  {
    $request->validate([
      'title' => 'max:100',
			'subtitle' => 'max:255',
			'description' => 'max:1000',
    ]);

		$active = isset($request->active) ? 1 : 0;

    Content::where('page', 'home')->where('position', '1')->update([
			'title' => $request->title,
			'subtitle' => $request->subtitle,
			'description' => $request->description,
			'active' => $active,
    ]);

    return redirect("/admin/home-page")->with('message', 'Content updated successfully.');
  }
}