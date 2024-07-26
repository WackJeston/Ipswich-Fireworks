<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\View;

class PublicController extends Controller
{
  public function __construct()
	{
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

		View::share([
			'publicLinks' => $publicLinks,
			'socials' => $socials,
			'contact' => $contact,
		]);
	}
}
