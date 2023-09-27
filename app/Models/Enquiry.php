<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewEnquiry;

class Enquiry extends Model
{
  use HasFactory;

	protected $table = 'enquiry';

  protected $fillable = [
		'type',
		'name',
		'email',
    'phone',
    'subject',
    'message',
  ];

	public static function boot()
	{
		parent::boot();

		self::created(function($model){
			$emails = DB::select('SELECT
				u.email
				FROM users AS u
				INNER JOIN notification_user AS nu ON nu.userId = u.id AND nu.email = 1
				INNER JOIN notification AS n ON n.id = nu.notificationId AND n.name = ?
			', [$model->type]);

			foreach ($emails as $i => $email) {
				Mail::to($email)->send(new NewEnquiry($model->id));
			}
		});
	}
}
