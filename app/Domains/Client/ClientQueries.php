<?php

declare(strict_types=1);

namespace App\Domains\Client;

use App\Domains\Client\DataObjects\ClientData;
use App\Domains\Client\Models\Client;
use Illuminate\Pagination\LengthAwarePaginator;

class ClientQueries
{
    public function listQuery(array $filterData): LengthAwarePaginator
    {
        return Client::query()
        ->select('id', 'name', 'status', 'email','contact_person','address','type','code')
        ->when(!empty($filterData['search_text']), function ($query) use ($filterData) {
            $query->where('name', 'LIKE', '%'.$filterData['search_text'].'%');
        })
        ->when(!empty($filterData['sort_by']), function ($query) use ($filterData) {
            $query->orderBy($filterData['sort_by'], $filterData['sort_direction'] ?? 'asc');
        }, function ($query) {
            $query->orderBy('id', 'desc');
        })
        ->paginate($filterData['per_page']);
    }

    public function addNew(ClientData $clientData): Client
    {
        $data = $clientData->all();
        return Client::query()->create($data);
    }

    public function getById(int $clientId): Client
    {
        return Client::query()->select('id', 'name', 'status', 'email','contact_person','address','type','code')
            ->findOrFail($clientId);
    }

     public function update(ClientData $clientData, int $clientId): void
    {
        $client = $this->getById($clientId);
        $client->update($clientData->all());
    }

    public function delete(int $clientId): void
    {
        $client = $this->getById($clientId);
        $client->delete();
    }

    public function status(int $clientId): void
    {
        $client = Client::query()->findorfail($clientId);
        $client->status = ! $client->status;
        $client->save();
    }
}
