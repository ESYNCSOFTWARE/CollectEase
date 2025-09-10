<?php

declare(strict_types=1);

namespace App\Domains\Region\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Domains\Permission\Models\Permission;
use App\Domains\Role\Models\Role;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Region extends Authenticatable  
{
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'status',
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    public function permissions(): HasManyThrough
    {
        return $this->hasManyThrough(Permission::class, Role::class, 'role_user', 'role_id', 'id', 'id');
    }

    public function hasAnyPermission(array $permissions): bool
    {
        $userPermissions = Permission::query()->join('permission_role', 'permissions.id', '=', 'permission_role.permission_id')
            ->join('role_user', 'permission_role.role_id', '=', 'role_user.role_id')
            ->where('role_user.user_id', $this->id)
            ->whereIn('permissions.name', $permissions)
            ->pluck('permissions.name')
            ->unique()
            ->toArray();

        return ! empty($userPermissions);
    }
    
}
