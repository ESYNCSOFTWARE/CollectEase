@extends('layouts.app')

@section('content')
    <!-- Auto Update Button -->
    <div class="flex justify-end mb-6">
        <form action="{{ route('assignments.autoUpdatePriorities') }}" method="POST">
            @csrf
            <button type="submit" 
                class="flex items-center px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow transition">
                <i class="fas fa-sync-alt mr-2"></i> Auto-Update Priorities
            </button>
        </form>
    </div>
<div class="p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800"> Priority & Due Date Management</h2>

   
    <!-- ✅ Keep the colorful KPI Cards (your previous style) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="p-4 bg-red-100 text-red-700 rounded-lg text-center shadow">
            <p class="text-2xl font-bold">{{ $overdueCount }}</p>
            <p>Overdue Cases</p>
        </div>
        <div class="p-4 bg-yellow-100 text-yellow-700 rounded-lg text-center shadow">
            <p class="text-2xl font-bold">{{ $dueSoonCount }}</p>
            <p>Due This Week</p>
        </div>
        <div class="p-4 bg-green-100 text-green-700 rounded-lg text-center shadow">
            <p class="text-2xl font-bold">{{ $upcomingCount }}</p>
            <p>Upcoming Cases</p>
        </div>
    </div>

 <!-- Filters -->
<div class="bg-white shadow rounded-xl p-4 mb-6">
    <form method="GET" action="{{ route('assignments.priority_due_date') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        
        <!-- Client -->
        <div>
            <label class="text-sm font-medium text-gray-600">Client</label>
            <select name="client_id" class="w-full border rounded p-2">
                <option value="">All Clients</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                        {{ $client->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Date Range -->
        <div>
            <label class="text-sm font-medium text-gray-600">Start Date</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full border rounded p-2">
        </div>
        <div>
            <label class="text-sm font-medium text-gray-600">End Date</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full border rounded p-2">
        </div>

        <!-- Status -->
        <div>
            <label class="text-sm font-medium text-gray-600">Status</label>
            <select name="status" class="w-full border rounded p-2">
                <option value="">All Status</option>
                <option value="New" {{ request('status')=='New' ? 'selected' : '' }}>New</option>
                <option value="Assigned" {{ request('status')=='Assigned' ? 'selected' : '' }}>Assigned</option>
                <option value="Reassigned" {{ request('status')=='Reassigned' ? 'selected' : '' }}>Reassigned</option>
                <option value="Paid" {{ request('status')=='Paid' ? 'selected' : '' }}>Paid</option>
                <option value="Escalated" {{ request('status')=='Escalated' ? 'selected' : '' }}>Escalated</option>
            </select>
        </div>

        <!-- Priority -->
        <div>
            <label class="text-sm font-medium text-gray-600">Priority</label>
            <select name="priority" class="w-full border rounded p-2">
                <option value="">All Priorities</option>
                <option value="Low" {{ request('priority')=='Low' ? 'selected' : '' }}>Low</option>
                <option value="Medium" {{ request('priority')=='Medium' ? 'selected' : '' }}>Medium</option>
                <option value="High" {{ request('priority')=='High' ? 'selected' : '' }}>High</option>
                <option value="Critical" {{ request('priority')=='Critical' ? 'selected' : '' }}>Critical</option>
            </select>
        </div>

        <!-- Submit -->
        <div class="md:col-span-5 flex justify-end items-end">
            <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
                Apply Filters
            </button>
        </div>
    </form>
</div>


    <!-- Modern Cases Table -->
    <div class="bg-white shadow rounded-xl overflow-hidden">
        <table class="w-full text-sm border-collapse">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="p-3 text-left text-gray-600">Case Ref</th>
                    <th class="p-3 text-left text-gray-600">Client</th>
                    <th class="p-3 text-left text-gray-600">Debtor</th>
                    <th class="p-3 text-left text-gray-600">Amount</th>
                    <th class="p-3 text-left text-gray-600">Collector</th>
                    <th class="p-3 text-left text-gray-600">Due Date</th>
                    <th class="p-3 text-left text-gray-600">Priority</th>
                    <th class="p-3 text-left text-gray-600">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cases as $case)
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="p-3 font-medium">{{ $case->reference_no }}</td>
                    <td class="p-3">{{ $case->client->name ?? 'N/A' }}</td>
                    <td class="p-3">{{ $case->debtor_name }}</td>
                    <td class="p-3 font-semibold text-gray-700">RM{{ number_format($case->amount_due, 2) }}</td>
                    <td class="p-3">{{ $case->assignedCollector->name ?? 'Unassigned' }}</td>
                    <td class="p-3 
                        {{ $case->due_date->lt(now()) ? 'text-red-600 font-bold' : 
                           ($case->due_date->lt(now()->addDays(7)) ? 'text-yellow-600 font-semibold' : 'text-green-600') }}">
                        {{ $case->due_date->format('d M Y') }}
                    </td>
                    <td class="p-3">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $case->priority == 'Critical' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $case->priority == 'High' ? 'bg-orange-100 text-orange-700' : '' }}
                            {{ $case->priority == 'Medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $case->priority == 'Low' ? 'bg-green-100 text-green-700' : '' }}">
                            {{ $case->priority }}
                        </span>
                    </td>
                    <td class="p-3">{{ $case->status }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="p-6 text-center text-gray-500">No cases found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
