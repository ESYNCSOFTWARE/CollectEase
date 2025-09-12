<?php

declare(strict_types=1);

namespace App\Domains\Client\Controllers;

use App\Domains\Client\ClientQueries;
use App\Domains\Client\DataObjects\ClientData;
use App\Domains\Client\Enums\ClientType;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ClientController extends Controller
{
    public function __construct(
        protected ClientQueries $clientQueries
    ) {}

    public function fetchClients(Request $request): array
    {
        $filterData = [
            'search_text' => $request->get('search_text'),
            'sort_by' => $request->get('sort_by'),
            'sort_direction' => $request->get('sort_direction'),
            'per_page' => $request->get('per_page'),
        ];

        $lengthAwarePaginator = $this->clientQueries->listQuery($filterData);

        return [
            'total_records' => $lengthAwarePaginator->total(),
            'data' => $lengthAwarePaginator->getCollection(),
        ];
    }

    public function index()
    {
        return Inertia::render(
            'clients/Index',
        );
    }

    public function create(): Response
    {
        return Inertia::render('clients/Manage',   [
            'client_types' => ClientType::formattedForSelection(),
        ]);
    }

    public function store(ClientData $clientData): RedirectResponse
    {
        $this->clientQueries->addNew($clientData);

        return to_route('clients.index')->with('success', 'The client has been added successfully.');
    }

    public function edit(int $clientId): Response
    {
        return Inertia::render('clients/Manage', [
            'client' => $this->clientQueries->getById($clientId),
            'client_types' => ClientType::formattedForSelection(),
        ]);
    }

    public function update(ClientData $clientData, int $clientId): RedirectResponse
    {
        $this->clientQueries->update($clientData, $clientId);

        return to_route('clients.index')->with('success', 'The client has been updated successfully.');
    }

    public function delete(int $clientId)
    {
        $this->clientQueries->delete($clientId);

        return to_route('clients.index')->with('success', 'The client has been deleted successfully.');
    }

    public function status(int $clientId): RedirectResponse
    {
        $this->clientQueries->status($clientId);

        return to_route('clients.index')->with('success', 'The client status has been updated successfully.');
    }
}
