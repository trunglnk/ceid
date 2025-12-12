<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhatTangThem extends Model
{
  protected $fillable = [
    'phan_tram_tang_them',
    'tu_gia_tri',
    'den_gia_tri',
    'type'
  ];
  protected $appends = ['name', 'loai_phat_tang_them'];
  public function getNameAttribute()
  {
    if (!empty($this->attributes)) {
      if (!empty($this->attributes['den_gia_tri'])) {
        return $this->attributes['phan_tram_tang_them'] . '% (Từ ' . $this->attributes['tu_gia_tri'] . ' đến ' . $this->attributes['den_gia_tri'] . ')';
      } else {
        return $this->attributes['phan_tram_tang_them'] . '% (Từ ' . $this->attributes['tu_gia_tri'] . ' trở lên)';
      }
    } else {
      return null;
    }
  }

  public function getLoaiPhatTangThemAttribute()
  {
    if (!empty($this->attributes['type']) && $this->attributes['type'] == 'khi_thai') {
      return (object)['id' => "khi_thai", 'ten' => 'Khí thải,bụi'];
    }
    if (!empty($this->attributes['type']) && $this->attributes['type'] == 'nuoc_thai') {
      return (object)['id' => "nuoc_thai", 'ten' => 'Nước thải'];
    }
    return '';
  }
}
