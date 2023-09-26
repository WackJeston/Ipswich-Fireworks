<?php

namespace App\Http\Controllers;

use DB;
use App\Models\NotificationUser;


class AdminHeaderController extends Controller
{
  public function toggleNotification(int $id, int $notificationUserId, string $type)
  {
    if ($record = NotificationUser::find($notificationUserId)) {
			$record->delete();
			return false;
		
		} else {
			$record = new NotificationUser;
			$record->notificationId = $id;
			$record->userId = auth()->user()->id;

			if ($type == 'email') {
				$record->email = 1;
			} elseif ($type == 'phone') {
				$record->phone = 1;
			} else {
				$record->standard = 1;
			}

			$record->save();
			return true;

		}
  }
}
