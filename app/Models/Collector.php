<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collector extends Model
{
    protected $fillable = ['name','email','phone','region_id','active'];
    public function region(){ return $this->belongsTo(Region::class); }

public function cases()
    {
        return $this->hasMany(CaseFile::class, 'assigned_collector_id');
    }

    // (optional) Assignments history
    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'to_collector_id');
    }

}
