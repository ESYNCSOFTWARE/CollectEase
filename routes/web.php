<?php

declare(strict_types=1);

use App\Domains\Permission\Controllers\PermissionController;
use App\Domains\Permission\Controllers\PermissionGroupController;
use App\Domains\Role\Controllers\RoleController;
use App\Domains\User\Controllers\ProfileController;
use App\Domains\User\Controllers\UserController;
use App\Domains\User\Middleware\AccessControl;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Login');
});
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth:sanctum')->group(function (): void {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('profile.change_password');

    Route::controller(UserController::class)->name('users.')->group(function (): void {
        Route::get('users', 'index')->name('index')->middleware(AccessControl::class.':VIEW_USERS');
        Route::get('fetch-users', 'fetchUsers')->name('fetch')->middleware(AccessControl::class.':VIEW_USERS');
        Route::get('users/create', 'create')->name('create')->middleware(AccessControl::class.':CREATE_USERS');
        Route::post('users', 'store')->name('store')->middleware(AccessControl::class.':CREATE_USERS');
        Route::get('users/{userId}/edit', 'edit')->name('edit')->middleware(AccessControl::class.':UPDATE_USERS');
        Route::get('create-role-permissions/{userId}', 'createRolePermission')->name('create.role.permissions')->middleware(AccessControl::class.':CREATE_USERS');
        Route::put('assign-role-permissions/{userId}', 'assignRolePermission')->name('assign.role.permissions')->middleware(AccessControl::class.':CREATE_USERS');
        Route::get('fetch-assign-role-permissions/{userId}', 'fetchAssignRolePermission')->name('fetch.assigned.role.permissions')->middleware(AccessControl::class.':CREATE_USERS');
        Route::put('users/{userId}/update', 'update')->name('update')->middleware(AccessControl::class.':UPDATE_USERS');
        Route::post('users/{userId}/delete', 'delete')->name('delete')->middleware(AccessControl::class.':DELETE_USERS');
        Route::post('users/{userId}/status', 'status')->name('status')->middleware(AccessControl::class.':UPDATE_USERS');
        Route::get('unauthorize', 'unauthorize')->name('unauthorize.index');
    });

    Route::controller(RoleController::class)->name('roles.')->group(function (): void {
        Route::get('roles', 'index')->name('index')->middleware(AccessControl::class.':VIEW_ROLES');
        Route::get('fetch-roles', 'fetchRoles')->name('fetch')->middleware(AccessControl::class.':VIEW_ROLES');
        Route::get('roles/create', 'create')->name('create')->middleware(AccessControl::class.':CREATE_ROLES');
        Route::post('roles', 'store')->name('store')->middleware(AccessControl::class.':CREATE_ROLES');
        Route::get('roles/{roleId}/edit', 'edit')->name('edit')->middleware(AccessControl::class.':UPDATE_ROLES');
        Route::put('roles/{roleId}/update', 'update')->name('update')->middleware(AccessControl::class.':UPDATE_ROLES');
        Route::post('roles/{roleId}/delete', 'delete')->name('delete')->middleware(AccessControl::class.':DELETE_ROLES');
        Route::get('create-permission-to-role/{roleId}', 'createPermissionToRole')->name('create.permission.to.role')->middleware(AccessControl::class.':CREATE_ROLES');
        Route::post('assign-permission-to-role', 'assignPermissionToRole')->name('assign.permission.to.role')->middleware(AccessControl::class.':CREATE_ROLES');
        Route::put('assign-group-permission-to-role/{roleId}', 'assignGroupPermission')->name('assign.group.permission.to.role')->middleware(AccessControl::class.':CREATE_ROLES');
        Route::get('role-permissions', 'rolePermission')->name('rolePermission');
        Route::post('attach-group-permission-to-role', 'attachGroupPermissionToRole')->name('attach.group.permissions');
        Route::post('detach-group-permission-to-role', 'detachGroupPermissionFromRole')->name('detach.group.permissions');
    });
    Route::controller(PermissionGroupController::class)->name('permission_groups.')->group(function (): void {
        Route::get('permission-groups', 'index')->name('index')->middleware(AccessControl::class.':VIEW_PERMISSION_GROUPS');
        Route::get('fetch-permission-groups', 'fetchPermissionGroups')->name('fetch')->middleware(AccessControl::class.':VIEW_PERMISSION_GROUPS');
        Route::get('permission-group/create', 'create')->name('create')->middleware(AccessControl::class.':CREATE_PERMISSION_GROUPS');
        Route::post('permission-groups', 'store')->name('store')->middleware(AccessControl::class.':CREATE_PERMISSION_GROUPS');
        Route::get('permission-groups/{permissionGroupId}/edit', 'edit')->name('edit')->middleware(AccessControl::class.':UPDATE_PERMISSION_GROUPS');
        Route::put('permission-groups/{permissionGroupId}/update', 'update')->name('update')->middleware(AccessControl::class.':UPDATE_PERMISSION_GROUPS');
        Route::post('permission-groups/{permissionGroupId}/delete', 'delete')->name('delete')->middleware(AccessControl::class.':DELETE_PERMISSION_GROUPS');
        Route::get('create-permission-to-groups/{permissionGroupId}', 'createPermissionToGroup')->name('create.permission.to.group')->middleware(AccessControl::class.':CREATE_PERMISSION_GROUPS');
        Route::put('assign-permission-permissions/{permissionGroupId}', 'assignGroupPermission')->name('assign.group.permissions')->middleware(AccessControl::class.':CREATE_PERMISSION_GROUPS');
    });

    Route::controller(PermissionController::class)->name('permissions.')->group(function (): void {
        Route::get('permission-list', 'index')->name('index')->middleware(AccessControl::class.':VIEW_PERMISSIONS');
        Route::get('fetch-permissions', 'fetchPermissions')->name('fetch')->middleware(AccessControl::class.':VIEW_PERMISSIONS');
        Route::get('permissions/create', 'create')->name('create')->middleware(AccessControl::class.':CREATE_PERMISSIONS');
        Route::post('permissions', 'store')->name('store')->middleware(AccessControl::class.':CREATE_PERMISSIONS');
        Route::get('permissions/{permissionId}/edit', 'edit')->name('edit')->middleware(AccessControl::class.':UPDATE_PERMISSIONS');
        Route::put('permissions/{permissionId}/update', 'update')->name('update')->middleware(AccessControl::class.':UPDATE_PERMISSIONS');
        Route::post('permissions/{permissionId}/delete', 'delete')->name('delete')->middleware(AccessControl::class.':UPDATE_PERMISSIONS');
    });

});

require __DIR__.'/auth.php';
