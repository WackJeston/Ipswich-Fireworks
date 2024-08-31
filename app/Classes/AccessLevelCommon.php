<?php
namespace App\Classes;

use DB;
use App\Models\AccessLevel;

class AccessLevelCommon {
	public static function getAccessLevels(bool $form = false) {
		$accessLevels = DB::select('SELECT * FROM access_levels ORDER BY `default` DESC, `master` ASC, `name` ASC');

		if ($form) {
			$accessLevels = array_map(function($accessLevel) {
				return [
					'value' => $accessLevel->id,
					'label' => $accessLevel->name,
				];
			}, $accessLevels);
		}

		return $accessLevels;
	}

	public static function getDefault() {
		return AccessLevel::select('id')->where('default', 1)->first()['id'];
	}

	public static function getMaster() {
		return AccessLevel::select('id')->where('master', 1)->first()['id'];
	}
}