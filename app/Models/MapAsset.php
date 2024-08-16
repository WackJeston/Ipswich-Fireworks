<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapAsset extends Model
{
  use HasFactory;

	protected $table = 'map_asset';

  protected $fillable = [
		'name',
		'map',
		'sequence',
		'assetId',
  ];
}
