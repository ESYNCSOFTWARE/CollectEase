<?php

declare(strict_types=1);

namespace App\Domains\User\Controllers;

use App\Domains\Role\RoleQueries;
use App\Domains\User\DataObjects\AssignRolePermissionData;
use App\Domains\User\DataObjects\UserData;
use App\Domains\User\Enums\DashBoardTemplate;
use App\Domains\User\Resources\UserListResource;
use App\Domains\User\Services\UserService;
use App\Domains\User\UserQueries;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function __construct(
        protected UserQueries $userQueries
    ) {}

    public function fetchusers(Request $request): array
    {
        $filterData = [
            'search_text' => $request->get('search_text'),
            'sort_by' => $request->get('sort_by'),
            'sort_direction' => $request->get('sort_direction'),
            'per_page' => $request->get('per_page'),
        ];

        $lengthAwarePaginator = $this->userQueries->listQuery($filterData);

        return [
            'total_records' => $lengthAwarePaginator->total(),
            'data' => UserListResource::collection($lengthAwarePaginator->getCollection()),
        ];
    }

    public function index()
    {
        return Inertia::render(
            'users/Index',
        );
    }

    public function create(): Response
    {

        return Inertia::render('users/Manage',   [
            'templates' => DashBoardTemplate::formattedForSelection(),
        ]);
    }

    public function store(UserData $userData): RedirectResponse
    {
        $this->userQueries->addNew($userData);

        return to_route('users.index')->with('success', 'The user has been added successfully.');
    }

    public function edit(int $userId): Response
    {
        return Inertia::render('users/Manage', [
            'user' => $this->userQueries->getById($userId),
            'templates' => DashBoardTemplate::formattedForSelection(),
        ]);
    }

    public function update(UserData $userData, int $userId): RedirectResponse
    {
        $userId = $this->userQueries->getById($userId);
        $this->userQueries->update($userData, $userId);

        return to_route('users.index')->with('success', 'The user has been updated successfully.');
    }

    public function delete(int $userId)
    {
        $this->userQueries->getById($userId);

        $this->userQueries->delete($userId);

        return to_route('users.index')->with('success', 'The user has been deleted successfully.');
    }

    public function getCommonResources(): array
    {
        $userService = App::make(UserService::class);

        return $userService->getCommonRecords();
    }

    public function createRolePermission(int $userId): Response
    {

        $roleQueries = App::make(RoleQueries::class);

        $user = $this->userQueries->getById($userId);

        $roles = $user->roles()->with('permissions')->get();

        return Inertia::render('users/RolePermission', [
            'user' => $this->userQueries->getById($userId),
            'roles' => $roleQueries->roleList(),
            'role_ids' => $roles,
        ]);
    }

    public function assignRolePermission(AssignRolePermissionData $assignRolePermissionData, int $userId): RedirectResponse
    {

        try {

            $roleQueries = App::make(RoleQueries::class);

            $user = $this->userQueries->getById($userId);

            $role_ids = array_column($assignRolePermissionData->role_ids, 'id');

            $roles = $roleQueries->whereIn($role_ids);

            $user->roles()->sync($roles);

            return to_route('users.index')->with('success', 'role assigned to user successfully.');
        } catch (Exception $exception) {
            throw new Exception('An error occurred. Please try again.', $exception->getCode(), $exception);
        }
    }

    public function unauthorize()
    {
        return Inertia::render('Auth/Unauthorize');
    }

    public function status(int $userId): RedirectResponse
    {
        $this->userQueries->status($userId);

        return to_route('users.index')->with('success', 'The user status has been updated successfully.');
    }
}
