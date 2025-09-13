<?php

declare(strict_types=1);

namespace App\Domains\RoleStatus\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\Status\Models\Status;
use App\Domains\Role\Models\Role;

class RoleStatus extends Model
{
    protected $table = 'role_statuses';

    protected $fillable = [
        'role_id',
        'status_id',
        'can_view',
        'can_update'
    ];

    protected $casts = [
        'can_view' => 'boolean',
        'can_update' => 'boolean',
    ];


    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}
