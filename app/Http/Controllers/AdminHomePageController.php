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

		$ticketNotice = Content::firstOrCreate([
			'page' => 'home',
			'position' => 'ticketNotice',
		]);

		$ticketNoticeForm = new DataForm(request(), '/admin-home-pageUpdateTicketNotice');
		$ticketNoticeForm->setTitle('Ticket Notice');
		$ticketNoticeForm->addInput('textarea', 'description', 'Ticket Notice', $ticketNotice->description, 1000);
		$ticketNoticeForm->addInput('checkbox', 'active', 'Active', $ticketNotice->active);
		$ticketNoticeForm = $ticketNoticeForm->render();

		$aboutUs1 = Content::firstOrCreate([
			'page' => 'home',
			'position' => 'aboutUs_1',
		]);

		$aboutUs2 = Content::firstOrCreate([
			'page' => 'home',
			'position' => 'aboutUs_2',
		]);

		$aboutUs3 = Content::firstOrCreate([
			'page' => 'home',
			'position' => 'aboutUs_3',
		]);

		$aboutUsForm = new DataForm(request(), '/admin-home-pageUpdateAboutUs');
		$aboutUsForm->setTitle('About Us Section');
		$aboutUsForm->addInput('text', 'title_1', 'Title 1', $aboutUs1->title, 100);
		$aboutUsForm->addInput('textarea', 'description_1', 'Paragraph 1', $aboutUs1->description, 1000);
		$aboutUsForm->addInput('text', 'title_2', 'Title 2', $aboutUs2->title, 100);
		$aboutUsForm->addInput('textarea', 'description_2', 'Paragraph 2', $aboutUs2->description, 1000);
		$aboutUsForm->addInput('text', 'title_3', 'Title 3', $aboutUs3->title, 100);
		$aboutUsForm->addInput('textarea', 'description_3', 'Paragraph 3', $aboutUs3->description, 1000);
		$aboutUsForm->addInput('checkbox', 'active', 'Active', $aboutUs1->active);
		$aboutUsForm = $aboutUsForm->render();


    return view('admin/home-page', compact(
      'sessionUser',
			'landingZoneBannerForm',
			'landingZoneBannerTable',
			'primaryInfoForm',
			'ticketNoticeForm',
			'aboutUsForm',
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


	public function updateTicketNotice(Request $request)
	{
		$request->validate([
			'description' => 'max:5000',
		]);

		Content::where('page', 'home')->where('position', 'ticketNotice')->update([
			'description' => $request->description,
			'active' => isset($request->active) ? 1 : 0,
		]);

		return redirect("/admin/home-page")->with('message', 'Ticket notice updated successfully.');
	}


	public function updateAboutUs(Request $request)
	{
		$request->validate([
			'title_1' => 'max:100',
			'description_1' => 'max:5000',
			'title_2' => 'max:100',
			'description_2' => 'max:5000',
			'title_3' => 'max:100',
			'description_3' => 'max:5000',
		]);

		Content::where('page', 'home')->where('position', 'aboutUs_1')->update([
			'title' => $request->title_1,
			'description' => $request->description_1,
			'active' => isset($request->active) ? 1 : 0,
		]);

		Content::where('page', 'home')->where('position', 'aboutUs_2')->update([
			'title' => $request->title_2,
			'description' => $request->description_2,
			'active' => isset($request->active) ? 1 : 0,
		]);

		Content::where('page', 'home')->where('position', 'aboutUs_3')->update([
			'title' => $request->title_3,
			'description' => $request->description_3,
			'active' => isset($request->active) ? 1 : 0,
		]);

		return redirect("/admin/home-page")->with('message', 'About us updated successfully.');
	}
}