<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class CaseFile extends Model
{
    protected $table = 'cases';

   protected $fillable = [
    'reference_no','client_id','debtor_name','debtor_contact',
    'amount_due','due_date','case_type_id','region_id','status',
    'priority','assigned_collector_id','notes'
];

    protected $casts = [
        'due_date' => 'date',
        'amount_due' => 'decimal:2'
    ];

    // 🔗 Relationships
    public function client(){ return $this->belongsTo(Client::class); }
    public function region(){ return $this->belongsTo(Region::class); }
    public function type(){ return $this->belongsTo(CaseType::class, 'case_type_id'); }
    public function assignedCollector(){ return $this->belongsTo(Collector::class, 'assigned_collector_id'); }
    public function assignments(){ return $this->hasMany(Assignment::class, 'case_id'); }
    public function statusHistory(){ return $this->hasMany(CaseStatusHistory::class, 'case_id'); }
    public function escalations(){ return $this->hasMany(Escalation::class, 'case_id'); }

    // ✅ Scope: get only overdue cases
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->whereNotIn('status', ['Paid','Closed'])
                     ->whereDate('due_date','<', now()->toDateString());
    }

    // ✅ Accessor: check if THIS case is overdue
    public function getIsOverdueAttribute(): bool
    {
        if (!$this->due_date) return false;
        if (in_array($this->status, ['Paid','Closed'])) return false;
        return $this->due_date->isPast();
    }
}
