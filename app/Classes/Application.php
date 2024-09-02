<?php
namespace App\Classes;

use Illuminate\Support\Facades\View;
use App\Classes\AccessLevelCommon;
use DB;

class Application {
	public static function initiatePublic() {
		$publicLinks = [
			$findUs = [
				"title"=>"find us",
				"link"=>"/find-us",
				"icon"=>"fa-solid fa-map-location-dot",
			],
			$programme = [
				"title"=>"programme",
				"link"=>"/programme",
				"icon"=>"fa-regular fa-rectangle-list",
			],
			$scouts = [
				"title"=>"11th Ipswich Scout Group",
				"link"=>"/scouts",
				"icon"=>"fa-solid fa-people-group",
			],
			$contact = [
				"title"=>"contact us",
				"link"=>"/contact",
				"icon"=>"fa-solid fa-address-card",
			],
			$feedback = [
				"title"=>"leave feedback",
				"link"=>"/feedback",
				"icon"=>"fa-solid fa-message",
			],
			$sponsors = [
				"title"=>"supporters & sponsors",
				"link"=>"/supporters",
				"icon"=>"fa-solid fa-heart-pulse",
			],
		];

		$socials = [
			$instagram = [
				"title"=>"instagram",
				"link"=>"https://www.instagram.com/ipswichfireworks50/",
				"icon"=>"fa-brands fa-instagram",
			],
			$facebook = [
				"title"=>"facebook",
				"link"=>"https://www.facebook.com/IpswichFireworks/",
				"icon"=>"fa-brands fa-facebook",
			],
			$twitter = [
				"title"=>"twitter",
				"link"=>"https://twitter.com/parkfireworks/",
				"icon"=>"fa-brands fa-x-twitter",
			],
			// $tiktok = [
			//   "title"=>"tiktok",
			//   "link"=>"https://www.tiktok.com/ipswichfireworks50/",
			//   "icon"=>"fa-brands fa-tiktok",
			// ],
		];

		$contactResult = DB::select('SELECT type, value FROM contact ORDER BY type ASC');

		$contact = [
			'email' => [],
			'phone' => [],
			'line2' => '',
			'line3' => '',
		];

		foreach ($contactResult as $i => $row) {
			if ($row->type == 'email') {
				$contact['email'][] = $row->value;
			} elseif ($row->type == 'phone') {
				$contact['phone'][] = $row->value;
			}	else {
				$contact[$row->type] = $row->value;
			}
		}

		$date = date('Y-m-d H:i:s');
		$start = DB::select('SELECT date FROM settings WHERE id = 1')[0]->date;
		$end = DB::select('SELECT datetime FROM settings WHERE id = 4')[0]->datetime;
				
		if (($date >= $start) && ($date <= $end)){
			$ticketsActive = true;
		} else {
			$ticketsActive = false; 
		}

		View::share([
			'publicLinks' => $publicLinks,
			'socials' => $socials,
			'contact' => $contact,
			'ticketsActive' => $ticketsActive,
		]);
	}
	

	public static function initiateAdmin() {
		$adminLinks = [];

		$adminLinks[] = [
			"title"=>"enquiries",
			"link"=>"/admin/enquiries",
			"icon"=>"fa-regular fa-comment",
		];

		$adminLinks[] = [
			"title"=>"settings",
			"link"=>"/admin/settings",
			"icon"=>"fa-solid fa-gear",
		];

		if (AccessLevelCommon::authorise()) {
			$adminLinks[] = [
				"title"=>"system",
				"icon"=>"fa-solid fa-power-off",
				"sublink"=>[
					[
						"title"=>"access levels",
						"link"=>"/admin/access-levels",
						"icon"=>"fa-solid fa-lock",
					],
					[
						"title"=>"cache",
						"link"=>"/admin/cache",
						"icon"=>"fa-solid fa-database",
					],
					[
						"title"=>"cron jobs",
						"link"=>"/admin/cron-jobs",
						"icon"=>"fa-solid fa-clock",
					],
					[
						"title"=>"secrets",
						"link"=>"/admin/secrets",
						"icon"=>"fa-solid fa-key",
					],
					[
						"title"=>"users",
						"link"=>"/admin/users",
						"icon"=>"fa-solid fa-user-astronaut",
					],
				],
			];
		}

		$adminLinks[] = [
			"title"=>"website",
			"icon"=>"fa-solid fa-globe",
			"sublink"=>[
				[
					"title"=>"home page",
					"link"=>"/admin/home-page",
					"icon"=>"fa-solid fa-house-chimney",
				],
				[
					"title"=>"find us",
					"link"=>"/admin/find-us",
					"icon"=>"fa-solid fa-map-location-dot",
				],
				[
					"title"=>"contact",
					"link"=>"/admin/contact",
					"icon"=>"fa-solid fa-address-card",
				],
				[
					"title"=>"map",
					"link"=>"/admin/map",
					"icon"=>"fas fa-map-marked-alt",
				],
				[
					"title"=>"programme",
					"link"=>"/admin/programme",
					"icon"=>"fa-solid fa-clipboard-list",
				],
				[
					"title"=>"Scout Group",
					"link"=>"/admin/scouts",
					"icon"=>"fa-solid fa-people-group",
				],
				[
					"title"=>"supporters",
					"link"=>"/admin/supporters",
					"icon"=>"fa-solid fa-heart-pulse",
				],
			],
		];
		
		$contactResult = DB::select('SELECT type, value FROM contact ORDER BY type ASC');

		$contact = [
			'email' => [],
			'phone' => [],
			'line2' => '',
			'line3' => '',
		];

		foreach ($contactResult as $i => $row) {
			if ($row->type == 'email') {
				$contact['email'][] = $row->value;
			} elseif ($row->type == 'phone') {
				$contact['phone'][] = $row->value;
			}	else {
				$contact[$row->type] = $row->value;
			}
		}

		View::share([
			'adminLinks' => $adminLinks,
			'contact' => $contact,
		]);
	}
}