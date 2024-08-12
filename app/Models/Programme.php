<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
  use HasFactory;

	protected $table = 'programme';

  protected $fillable = [
		'type',
		'label',
		'value',
		'stage',
		'time',
		'link',
    'active',
		'sequence',
		'assetId'
  ];

	protected static function booted() {
		static::created(function ($self) {
			$self->sequence = $self->id;
			$self->save();
    });
	}
}
