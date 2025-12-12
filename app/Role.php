<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'description', 
        'code', 
        'system'
    ];

    public function functions(){
        return $this->hasMany('App\RoleMenu', 'role_id', 'id');
    }

    public $timestamps = false;
}
