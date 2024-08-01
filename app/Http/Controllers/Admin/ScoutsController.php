<?php
namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\DataClasses\DataTable;
use App\DataClasses\DataForm;
use App\Models\Content;


class ScoutsController extends AdminController
{
  public function show()
  {		
		// $aboutUs1 = Content::firstOrCreate([
		// 	'page' => 'scouts',
		// 	'position' => 'aboutUs_1',
		// ]);

		// $aboutUs2 = Content::firstOrCreate([
		// 	'page' => 'scouts',
		// 	'position' => 'aboutUs_2',
		// ]);

		// $aboutUs3 = Content::firstOrCreate([
		// 	'page' => 'scouts',
		// 	'position' => 'aboutUs_3',
		// ]);

		$aboutUs1 = Content::where('page', 'scouts')->where('position', 'aboutUs_1')->first();
		$aboutUs2 = Content::where('page', 'scouts')->where('position', 'aboutUs_2')->first();
		$aboutUs3 = Content::where('page', 'scouts')->where('position', 'aboutUs_3')->first();

		$aboutUsForm = new DataForm(request(), '/admin-scoutsUpdateAboutUs', 'Save');
		$aboutUsForm->setTitle('About Us Section');
		$aboutUsForm->addInput('text', 'title1', 'Title 1', $aboutUs1->title, 100);
		$aboutUsForm->addInput('textarea', 'description1', 'Paragraph 1', $aboutUs1->description, 1000);
		$aboutUsForm->addInput('text', 'title2', 'Title 2', $aboutUs2->title, 100);
		$aboutUsForm->addInput('textarea', 'description2', 'Paragraph 2', $aboutUs2->description, 1000);
		$aboutUsForm->addInput('text', 'title3', 'Title 3', $aboutUs3->title, 100);
		$aboutUsForm->addInput('textarea', 'description3', 'Paragraph 3', $aboutUs3->description, 1000);
		$aboutUsForm->addInput('checkbox', 'active', 'Active', $aboutUs1->active);
		$aboutUsForm = $aboutUsForm->render();

    return view('admin/scouts', compact(
			'aboutUsForm',
    ));
  }

	public function updateAboutUs(Request $request)
	{
		dd($request->title1);

		$request->validate([
			'title1' => 'max:100',
			'description1' => 'max:5000',
			'title2' => 'max:100',
			'description2' => 'max:5000',
			'title3' => 'max:100',
			'description3' => 'max:5000',
		]);

		Content::where('page', 'scouts')->where('position', 'aboutUs_1')->update([
			'title' => $request->title1,
			'description' => $request->description1,
			'active' => isset($request->active) ? 1 : 0,
		]);

		Content::where('page', 'scouts')->where('position', 'aboutUs_2')->update([
			'title' => $request->title2,
			'description' => $request->description2,
			'active' => isset($request->active) ? 1 : 0,
		]);

		Content::where('page', 'scouts')->where('position', 'aboutUs_3')->update([
			'title' => $request->title3,
			'description' => $request->description3,
			'active' => isset($request->active) ? 1 : 0,
		]);

		return redirect("/admin/scouts")->with('message', 'About us updated successfully.');
	}
}