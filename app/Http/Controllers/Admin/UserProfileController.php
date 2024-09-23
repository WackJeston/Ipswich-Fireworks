<?php
namespace App\Http\Controllers\Admin;

use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Classes\DataTable;
use App\Classes\DataForm;
use App\Classes\AccessLevelCommon;
use App\Models\Address;
use App\Models\User;


class UserProfileController extends AdminController
{
  public function show($id)
  {
    if (User::find($id) == null) {
      return redirect('/admin/users')->withErrors(['1' => 'User not found']);
    }
		
		$authorised = AccessLevelCommon::authorise();

		if (!$authorised && $id != auth()->user()->id) {
			return back()->withErrors(['1' => 'Not Authorised']);
		}

    $user = DB::select(sprintf('SELECT
      u.*,
			al.name AS accessLevel
      FROM users AS u
			LEFT JOIN access_levels AS al ON u.accessLevelId = al.id
      WHERE u.id = %d
      LIMIT 1
    ', $id));

    $user = $user[0];

		$billingAddress = Address::where('userId', $id)->where('defaultBilling', true)->first();

		$editForm = new DataForm(request(), sprintf('/user-profileUpdate/%d', $id), 'Update');
		$editForm->addInput('text', 'firstname', 'First Name', $user->firstName, 255, 1, true);
		$editForm->addInput('text', 'lastname', 'Last Name', $user->lastName, 255, 1, true);
		$editForm->addInput('email', 'email', 'Email', $user->email, 255, 1, true);
		$editForm->addInput('password', 'password', 'Password', null, 255, 6, false, 'New Password');
		$editForm->addInput('select', 'accessLevelId', 'Access Level', $user->accessLevelId, 255, 1, true);
		$editForm->populateOptions('accessLevelId', AccessLevelCommon::getAccessLevels(true), false);
		$editForm = $editForm->render();

    return view('admin/user-profile', compact(
			'authorised',
      'user',
			'billingAddress',
			'editForm',
    ));
  }


  public function update(Request $request, $id)
  {
    $request->validate([
      'firstname' => 'max:100',
      'lastname' => 'max:100',
      'email' => ['email', 'max:100', Rule::unique('users')->ignore($id)],
      'password' => 'nullable|min:6|max:100',
			'accessLevelId' => 'required|exists:access_levels,id'
    ]);

    User::where('id', $id)->update([
      'firstname' => $request->firstname,
      'lastname' => $request->lastname,
      'email' => $request->email,
			'accessLevelId' => $request->accessLevelId,
    ]);

    if ($request->password) {
      User::where('id', $id)->update([
        'password' => Hash::make($request->password),
      ]);
    }

    return redirect("/admin/user-profile/$id")->with('message', 'User updated.');
  }


  public function delete($id)
  {
    User::find($id)->delete();

    return redirect("/admin/users")->with('message', 'User deleted.');
  }
}
