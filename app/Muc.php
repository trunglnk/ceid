<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Muc extends Model
{
    protected $fillable = [
        'ma',
        'ten', 
        'mo_ta', 
        'khoan_id',
        'loai',
        'muc_phat_nho_nhat',      
        'muc_phat_lon_nhat',   
    ];

    protected $append = ['ma_ten'];
    
    public function getMaTenAttribute(){
        return $this->attributes['ma'].'-'.$this->attributes['ten'];
    }
    public $timestamps = false;

    public function khoan(){
        return $this->belongsTo('App\Khoan');
    }
}