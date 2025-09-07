<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Escalation extends Model
{
    protected $fillable = ['case_id','level','escalated_to','reason','created_by'];
    public function caseFile(){ return $this->belongsTo(CaseFile::class, 'case_id'); }
}
