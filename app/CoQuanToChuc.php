<?php

namespace App;

use App\Scopes\OrderScope;
use Illuminate\Database\Eloquent\Model;

class CoQuanToChuc extends Model
{
    protected $fillable = [
        'ten',
        'order',
        'inactive',
        'cap_bo',
    ];
    protected $dates = ['created_at', 'updated_at'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new \App\Scopes\OrderScope);
    }
}
