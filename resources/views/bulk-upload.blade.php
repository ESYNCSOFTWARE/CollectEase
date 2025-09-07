@extends('layouts.app')

@section('content')
<div class="dashboard-card p-6">
    <h2 class="text-xl font-bold mb-4">Bulk Upload Cases</h2>

    <p class="mb-4 text-gray-600">
        Please upload a CSV/Excel file in the correct format. 
        <a href="{{ route('bulk.template') }}" class="text-blue-600 hover:underline">
            Download Template
        </a>
    </p>

    <form action="{{ route('bulk.handle') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" accept=".csv,.xlsx" required class="mb-4">
        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
            <i class="fas fa-upload"></i> Upload
        </button>
    </form>
</div>
@endsection
