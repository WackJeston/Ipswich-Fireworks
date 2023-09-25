<?php

namespace App\Http\Controllers;

use DB;
use App\Models\NotificationUser;


class AdminDashboardController extends Controller
{
  public function toggleNotification(int $id, string $type)
  {
    if ($record = NotificationUser::find($id)) {
			$record->delete();

			return 0;
		
		} else {
			$record = new NotificationUser;
			$record->notificationId = $id;
			$record->userId = auth()->user()->id;

			if ($type = 'email') {
				$record->email = 1;
			} elseif ($type = 'phone') {
				$record->phone = 1;
			} else {
				$record->standard = 1;
			}

			$record->save();
			
			return 1;
		}

		return false;
  }
}
