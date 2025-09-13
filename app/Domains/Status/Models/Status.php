<?php

declare(strict_types=1);

namespace App\Domains\Status\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use App\Domains\Role\Models\Role;
use App\Domains\Permission\Models\Permission;

class Status extends Model
{
    use HasFactory;

    /**
     * Table name (optional if it follows conventions).
     *
     * @var string
     */
    protected $table = 'statuses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'name',
        'type',
        'code',
        'description',
        'color',
        'sort_order',
        'is_default',
    ];

    /**
     * Attribute casts.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'type' => 'string',
        'description' => 'string',
        'sort_order' => 'integer',
        'is_default' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Roles related to this status via pivot table `role_statuses`.
     *
     * Pivot columns: can_view, can_update
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'role_statuses', // pivot table
            'status_id',     // this model's FK on pivot
            'role_id'        // related model's FK on pivot
        )
            ->withPivot(['can_view', 'can_update'])
            ->withTimestamps();
    }

    /**
     * Return permissions available to this status (collected from roles' permissions).
     *
     * Note: this returns a Collection (not an Eloquent relation) because permissions are reached
     * through roles -> permission_role pivot.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function permissions(): EloquentCollection
    {
        return Permission::query()
            ->join('permission_role', 'permissions.id', '=', 'permission_role.permission_id')
            ->join('role_statuses', 'permission_role.role_id', '=', 'role_statuses.role_id')
            ->where('role_statuses.status_id', $this->id)
            ->select('permissions.*')
            ->distinct()
            ->get();
    }

    /**
     * Check if any of the given permission names exist for roles tied to this status.
     *
     * @param  array<string>  $permissions
     * @return bool
     */
    public function hasAnyPermission(array $permissions): bool
    {
        return Permission::query()
            ->join('permission_role', 'permissions.id', '=', 'permission_role.permission_id')
            ->join('role_statuses', 'permission_role.role_id', '=', 'role_statuses.role_id')
            ->where('role_statuses.status_id', $this->id)
            ->whereIn('permissions.name', $permissions)
            ->exists();
    }
}