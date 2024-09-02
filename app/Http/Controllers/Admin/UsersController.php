<?php
namespace App\Http\Controllers\Admin;

use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Classes\DataTable;
use App\Classes\DataForm;
use App\Classes\AccessLevelCommon;
use App\Models\User;


class UsersController extends AdminController
{
  public function show()
  {
		$accessLevels = AccessLevelCommon::getAccessLevels(true);

		$createForm = new DataForm(request(), '/usersCreate', 'Add');
		$createForm->addInput('text', 'firstname', 'First Name', null, 100, 1, true);
		$createForm->addInput('text', 'lastname', 'Last Name', null, 100, 1, true);
		$createForm->addInput('email', 'email', 'Email', null, 100, 1, true);
		$createForm->addInput('password', 'password', 'Password', null, 100, 6, true);
		$createForm->addInput('select', 'accessLevelId', 'Access Level', AccessLevelCommon::getDefault(), 255, 1, false);
		$createForm->populateOptions('accessLevelId', $accessLevels, false);
		$createForm = $createForm->render();

    $usersTable = new DataTable('users');
		$usersTable->setQuery('SELECT 
			u.*, 
			CONCAT(u.firstName, " ", u.lastName) AS `name`, 
			DATE_FORMAT(u.created_at, "%%d/%%m/%%Y %%H:%%i:%%s") AS `date`,
			al.name AS `accessLevel`
			FROM users AS u
			LEFT JOIN access_levels AS al ON u.accessLevelId = al.id
			WHERE u.admin = 1
			GROUP BY u.id'
		);
		$usersTable->addColumn('id', '#');
		$usersTable->addColumn('name', 'Name');
		$usersTable->addColumn('email', 'Email', 2);
		$usersTable->addColumn('accessLevel', 'Access Level', 2, true);
		$usersTable->addColumn('date', 'Created At', 2, true);
		$usersTable->addLinkButton('user-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$usersTable = $usersTable->render();

    return view('admin/users', compact(
			'createForm',
      'usersTable',
    ));
  }


  public function create(Request $request)
  {
    $request->validate([
      'firstname' => 'required|max:100',
      'lastname' => 'required|max:100',
      'email' => ['required', 'email', 'max:100', Rule::unique('users')],
      'password' => 'required|min:6|max:100',
			'accessLevelId' => 'required|exists:access_levels,id',
    ]);

    User::create([
      'admin' => 1,
      'firstname' => ucfirst($request->firstname),
      'lastname' => ucfirst($request->lastname),
      'email' => strtolower($request->email),
      'password' => Hash::make($request->password),
			'accessLevelId' => $request->accessLevelId,
    ]);

    return redirect('/admin/users')->with('message', 'User created.');
  }
}
