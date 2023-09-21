<?php

namespace App\Providers;

use DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      Validator::extend('unique_custom', function ($attribute, $value, $parameters)
      {
        list($table, $field, $field2, $field2Value) = $parameters;
        return DB::table($table)->where($field, $value)->where($field2, $field2Value)->count() == 0;
      });

      if (str_contains(url()->current(), '/admin/')) {
        $adminLinks = [
					$messages = [
            "title"=>"messages",
            "icon"=>"fa-regular fa-comment",
            "sublink"=>$subLinks = [
              $enquiries = [
								"title"=>"enquiries",
								"link"=>"/admin/enquiries",
								"icon"=>"fa-solid fa-envelope",
							],
              $feedback = [
                "title"=>"feedback",
                "link"=>"/admin/feedback",
                "icon"=>"fa-solid fa-comment-medical",
              ],
              $newSponsors = [
                "title"=>"Sponsor Enquiries",
                "link"=>"/admin/new-sponsors",
                "icon"=>"fa-solid fa-person-circle-plus",
              ],
            ],
          ],
					$settings = [
						"title"=>"settings",
						"link"=>"/admin/settings",
						"icon"=>"fa-solid fa-gear",
					],
					$users = [
						"title"=>"users",
						"link"=>"/admin/users",
						"icon"=>"fa-solid fa-user-astronaut",
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
              $sponsors = [
                "title"=>"sponsors",
                "link"=>"/admin/sponsors",
                "icon"=>"fa-solid fa-handshake-angle",
              ],
            ],
          ],
        ];

        View::share([
          'adminLinks' => $adminLinks,
        ]);
      }

      else {
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
					$supporters = [
						"title"=>"our supporters",
						"link"=>"/supporters",
						"icon"=>"fa-solid fa-heart-pulse",
					],
					$sponsors = [
						"title"=>"our sponsors",
						"link"=>"/sponsors",
						"icon"=>"fa-solid fa-handshake-angle",
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
    }
}
