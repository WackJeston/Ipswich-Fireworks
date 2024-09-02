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

	public static function authorise(string $accessLevelsData = null) {
		$accessLevels = [
			self::getMaster()
		];

		foreach (explode(', ', $accessLevelsData) as $i => $accessLevel) {
			$record = DB::select('SELECT 
				id 
				FROM access_levels 
				WHERE name = ?
				AND id NOT IN (?, ?)',
				[
					strtolower($accessLevel),
					self::getMaster(),
					self::getDefault()
				]
			);

			if (!empty($record)) {
				$accessLevels[] = $record['id'];
			}
		}

		if (in_array(auth()->user()->accessLevelId, $accessLevels)) {
			return true;
		} else {
			return false;
		}
	}
}