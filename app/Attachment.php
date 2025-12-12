<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable =['reference_type','reference_id','file_id','file_size','file_type','file_icon','link','name'];
}
