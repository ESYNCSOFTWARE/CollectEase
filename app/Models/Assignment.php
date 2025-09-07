<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'case_id','from_collector_id','to_collector_id','changed_by','assigned_at','remarks'
    ];
    protected $casts = ['assigned_at' => 'datetime'];

    public function caseFile(){ return $this->belongsTo(CaseFile::class, 'case_id'); }


    public function fromCollector() {
        return $this->belongsTo(Collector::class, 'from_collector_id');
    }

    public function toCollector() {
        return $this->belongsTo(Collector::class, 'to_collector_id');
    }

}
