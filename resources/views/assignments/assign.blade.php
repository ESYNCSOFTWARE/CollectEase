@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800"> Assign Cases</h2>

    <!-- 🔎 Filters -->
    <div class="bg-white shadow rounded-lg p-4 mb-6">
        <form method="GET" action="{{ route('assignments.assign.form') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Client Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Client</label>
                <select name="client_id" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
                    <option value="">All Clients</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ request('client_id')==$client->id ? 'selected' : '' }}>
                            {{ $client->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Case Type Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Case Type</label>
                <select name="case_type_id" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
                    <option value="">All Types</option>
                    @foreach($caseTypes as $type)
                        <option value="{{ $type->id }}" {{ request('case_type_id')==$type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Button -->
            <div class="flex items-end">
                 <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded-md shadow hover:bg-blue-700 text-sm">
        Apply
    </button>
            </div>
        </form>
    </div>

    <!-- ✅ Bulk Assign Form -->
    <div class="bg-white shadow rounded-lg p-4">
        <form method="POST" action="{{ route('assignments.bulkAssign') }}">
            @csrf

            <!-- Collector Selection -->
            <div class="flex justify-between items-center mb-4">
                <div>
                    <label class="text-sm font-medium text-gray-600 mr-2">Assign To Collector:</label>
                    <select name="collector_id" class="border rounded-lg p-2 focus:ring-2 focus:ring-green-500">
                        <option value="">Select Collector</option>
                        @foreach($collectors as $collector)
                            <option value="{{ $collector->id }}">{{ $collector->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-5 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 flex items-center">
                    <!--<i class="fas fa-user-check mr-2"></i>--> Assign Selected
                </button>
            </div>

            <!-- Cases Table -->
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b">
                            <th class="p-3">
                                <input type="checkbox" id="select-all" class="w-4 h-4">
                            </th>
                            <th class="p-3 text-left text-gray-600">Case Ref</th>
                            <th class="p-3 text-left text-gray-600">Client</th>
                            <th class="p-3 text-left text-gray-600">Debtor</th>
                            <th class="p-3 text-left text-gray-600">Amount</th>
                            <th class="p-3 text-left text-gray-600">Case Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cases as $case)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="p-3">
                                <input type="checkbox" name="case_ids[]" value="{{ $case->id }}" class="w-4 h-4">
                            </td>
                            <td class="p-3 font-medium text-gray-700">{{ $case->reference_no }}</td>
                            <td class="p-3">{{ $case->client->name ?? 'N/A' }}</td>
                            <td class="p-3">{{ $case->debtor_name }}</td>
                            <td class="p-3 font-semibold text-gray-800">${{ number_format($case->amount_due,2) }}</td>
                            <td class="p-3">{{ $case->type->name ?? 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-gray-500">No unassigned cases found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>

<script>
    // ✅ Select/Deselect all checkboxes
    document.getElementById('select-all').addEventListener('change', function(e) {
        document.querySelectorAll('input[name="case_ids[]"]').forEach(cb => cb.checked = e.target.checked);
    });
</script>
@endsection
