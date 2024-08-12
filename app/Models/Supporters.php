<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supporters extends Model
{
  use HasFactory;

  protected $fillable = [
		'type',
		'name',
		'link',
		'assetId',
    'active',
		'sequence',
  ];

	protected static function booted() {
		static::created(function ($self) {
			$self->sequence = $self->id;
			$self->save();
    });
	}
}
