<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DonVi extends Model
{
	protected $fillable = [
		'ten',
		'eng_ten',        
		'short_ten',
		'website',
];
}
