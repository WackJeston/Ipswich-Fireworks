<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\DataTable;
use App\DataForm;
use App\Models\Content;


class AdminScoutsController extends Controller
{
  public function show()
  {
		$aboutUs1 = Content::firstOrCreate([
			'page' => 'scouts',
			'position' => 'aboutUs_1',
		]);

		$aboutUs2 = Content::firstOrCreate([
			'page' => 'scouts',
			'position' => 'aboutUs_2',
		]);

		$aboutUs3 = Content::firstOrCreate([
			'page' => 'scouts',
			'position' => 'aboutUs_3',
		]);

		$aboutUsForm = new DataForm(request(), '/admin-scoutsUpdateAboutUs');
		$aboutUsForm->setTitle('About Us Section');
		$aboutUsForm->addInput('text', 'title_1', 'Title 1', $aboutUs1->title, 100);
		$aboutUsForm->addInput('textarea', 'description_1', 'Paragraph 1', $aboutUs1->description, 1000);
		$aboutUsForm->addInput('text', 'title_2', 'Title 2', $aboutUs2->title, 100);
		$aboutUsForm->addInput('textarea', 'description_2', 'Paragraph 2', $aboutUs2->description, 1000);
		$aboutUsForm->addInput('text', 'title_3', 'Title 3', $aboutUs3->title, 100);
		$aboutUsForm->addInput('textarea', 'description_3', 'Paragraph 3', $aboutUs3->description, 1000);
		$aboutUsForm->addInput('checkbox', 'active', 'Active', $aboutUs1->active);
		$aboutUsForm = $aboutUsForm->render();


    return view('admin/scouts', compact(
			'aboutUsForm',
    ));
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

		Content::where('page', 'scouts')->where('position', 'aboutUs_1')->update([
			'title' => $request->title_1,
			'description' => $request->description_1,
			'active' => isset($request->active) ? 1 : 0,
		]);

		Content::where('page', 'scouts')->where('position', 'aboutUs_2')->update([
			'title' => $request->title_2,
			'description' => $request->description_2,
			'active' => isset($request->active) ? 1 : 0,
		]);

		Content::where('page', 'scouts')->where('position', 'aboutUs_3')->update([
			'title' => $request->title_3,
			'description' => $request->description_3,
			'active' => isset($request->active) ? 1 : 0,
		]);

		return redirect("/admin/scouts")->with('message', 'About us updated successfully.');
	}
}