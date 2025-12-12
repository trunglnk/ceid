<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PdfAnalysisResult extends Model
{
    protected $table = 'pdf_analysis_result';

    protected $fillable = [
        'attachment_id',
        'hash',
        'summary',
        'content',
        'json'
    ];
    public $timestamps = true;
    protected $casts = ['json' => 'array'];

    public function attachment()
    {
        return $this->hasOne(Attachment::class, 'attachment_id');
    }
}
