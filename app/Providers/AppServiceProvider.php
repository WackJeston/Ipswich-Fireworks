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
          $people = [
            "title"=>"people",
            "icon"=>"fa-solid fa-users",
            "sublink"=>$subLinks = [
              $customers = [
                "title"=>"customers",
                "link"=>"/admin/customers",
                "icon"=>"fa-solid fa-user",
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
              $contact = [
                "title"=>"contact",
                "link"=>"/admin/contact",
                "icon"=>"fa-solid fa-address-card",
              ],
            ],
          ],
					// $test = [
					// 	"title"=>"test",
					// 	"link"=>"/admin/test",
					// 	"icon"=>"fa-solid fa-flask-vial",
					// ],
        ];

        View::share([
          'adminLinks' => $adminLinks,
        ]);
      }

      else {
        $publicLinks = [
          $contact = [
            "title"=>"contact",
            "link"=>"/contact",
            "icon"=>"fa-solid fa-address-card",
          ],
					$findUs = [
            "title"=>"find us",
            "link"=>"/contact",
            "icon"=>"fa-solid fa-map-location-dot",
          ],
					$sponsors = [
						"title"=>"sponsors",
						"link"=>"/sponsors",
						"icon"=>"fa-solid fa-heart-pulse",
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
          'publicLinks' => $publicLinks,
					// 'contact' => $contact,
        ]);
      }
    }
}
