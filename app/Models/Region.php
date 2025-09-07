<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = ['name','color'];
    public function collectors(){ return $this->hasMany(Collector::class); }
    public function cases(){ return $this->hasMany(CaseFile::class); }
}
