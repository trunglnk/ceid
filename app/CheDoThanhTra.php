<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CheDoThanhTra extends Model
{
    protected $fillable = [
        'ma',
        'ten',
        'mo_ta',
        'inactive',     
    ];
}
