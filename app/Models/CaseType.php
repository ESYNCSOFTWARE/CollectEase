<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseType extends Model
{
    protected $fillable = ['name','color'];
    public function cases(){ return $this->hasMany(CaseFile::class); }
}
