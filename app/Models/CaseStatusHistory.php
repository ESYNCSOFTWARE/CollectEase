<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseStatusHistory extends Model
{
    protected $fillable = ['case_id','old_status','new_status','changed_by','remarks'];
    public function caseFile(){ return $this->belongsTo(CaseFile::class, 'case_id'); }
}
