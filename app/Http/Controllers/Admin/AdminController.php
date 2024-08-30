<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\View;

class AdminController extends Controller
{
  public function __construct()
	{
		$adminLinks = [
			$enquiries = [
				"title"=>"enquiries",
				"link"=>"/admin/enquiries",
				"icon"=>"fa-regular fa-comment",
			],
			$settings = [
				"title"=>"settings",
				"link"=>"/admin/settings",
				"icon"=>"fa-solid fa-gear",
			],
			$system = [
				"title"=>"system",
				"icon"=>"fa-solid fa-power-off",
				"sublink"=>$subLinks = [
					$accessLevels = [
						"title"=>"access levels",
						"link"=>"/admin/access-levels",
						"icon"=>"fa-solid fa-lock",
					],
					$cache = [
						"title"=>"cache",
						"link"=>"/admin/cache",
						"icon"=>"fa-solid fa-database",
					],
					$cronJobs = [
						"title"=>"cron jobs",
						"link"=>"/admin/cron-jobs",
						"icon"=>"fa-solid fa-clock",
					],
					$secrets = [
						"title"=>"secrets",
						"link"=>"/admin/secrets",
						"icon"=>"fa-solid fa-key",
					],
					$users = [
						"title"=>"users",
						"link"=>"/admin/users",
						"icon"=>"fa-solid fa-user-astronaut",
					],
				],
			],
			$website = [
				"title"=>"website",
				"icon"=>"fa-solid fa-globe",
				"sublink"=>$subLinks = [
					$homePage = [
						"title"=>"home page",
						"link"=>"/admin/home-page",
						"icon"=>"fa-solid fa-house-chimney",
					],
					$findUs = [
						"title"=>"find us",
						"link"=>"/admin/find-us",
						"icon"=>"fa-solid fa-map-location-dot",
					],
					$contact = [
						"title"=>"contact",
						"link"=>"/admin/contact",
						"icon"=>"fa-solid fa-address-card",
					],
					$map = [
						"title"=>"map",
						"link"=>"/admin/map",
						"icon"=>"fas fa-map-marked-alt",
					],
					$programme = [
						"title"=>"programme",
						"link"=>"/admin/programme",
						"icon"=>"fa-solid fa-clipboard-list",
					],
					$scouts = [
						"title"=>"Scout Group",
						"link"=>"/admin/scouts",
						"icon"=>"fa-solid fa-people-group",
					],
					$supporters = [
						"title"=>"supporters",
						"link"=>"/admin/supporters",
						"icon"=>"fa-solid fa-heart-pulse",
					],
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
