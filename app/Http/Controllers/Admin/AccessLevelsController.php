<?php
namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use App\Classes\DataTable;
use App\Classes\DataForm;

use App\Models\AccessLevel;

class AccessLevelsController extends AdminController
{
  public function show()
  {
		$form = new DataForm(request(), '/accessLevelCreate', 'Create');
		$form->setTitle('Create Access Level');
		$form->addInput('text', 'name', 'Name', null, 255, 1, true);
		$form->addInput('checkbox', 'default', 'Default', null, 0, 0);
		$form->addInput('checkbox', 'master', 'Master', null, 0, 0);
		$form = $form->render();

    $table = new DataTable('access_levels');
		$table->setQuery('SELECT * FROM access_levels', [], 'id', 'ASC');
		$table->addColumn('id', '#');
		$table->addColumn('name', 'Name', 2);
		$table->addColumn('default', 'Default', 1, false, 'setPrimary:default:1');
		$table->addColumn('master', 'Master', 1, false, 'setPrimary:master:1');
		$table->addJsButton('showDeleteWarning', ['string:Access Level', 'record:id', 'url:/access-levelsDelete/?'], 'fa-solid fa-trash-can', 'Delete Access Level');
		$table = $table->render();
		
    return view('admin/access-levels', compact(
			'form',
			'table',
    ));
  }

	public function create(Request $request)
	{
		$request->validate([
			'name' => 'required|max:255',
		]);

		AccessLevel::create([
			'name' => $request->name,
			'default' => $request->default ? 1 : 0,
			'master' => $request->master ? 1 : 0,
		]);

		return redirect('/admin/access-levels')->with('message', 'Access level created.');
	}

	public function delete($id)
	{
		AccessLevel::destroy($id);
		return redirect('/admin/access-levels')->with('message', 'Access level deleted.');
	}
}
