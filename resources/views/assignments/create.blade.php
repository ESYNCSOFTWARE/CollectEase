@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/assignments.css') }}">
@endsection

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold">Create New Case</h2>
    <a href="{{ route('bulk.upload') }}" 
       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
        <i class="fas fa-upload"></i> Bulk Upload
    </a>
</div>

<form method="post" action="{{ route('assignments.store') }}" class="assignment-container assignment-form">
  @csrf

  <label>Client
    <select name="client_id" required>
      <option value="">-- Select --</option>
      @foreach($clients as $x)<option value="{{ $x->id }}">{{ $x->name }}</option>@endforeach
    </select>
  </label>

  <label>Case Type
    <select name="case_type_id" required>
      <option value="">-- Select --</option>
      @foreach($types as $x)<option value="{{ $x->id }}">{{ $x->name }}</option>@endforeach
    </select>
  </label>

  <label>Region
    <select name="region_id">
      <option value="">-- Optional --</option>
      @foreach($regions as $x)<option value="{{ $x->id }}">{{ $x->name }}</option>@endforeach
    </select>
  </label>

  <label>Debtor Name
    <input type="text" name="debtor_name" required>
  </label>

  <label>Debtor Contact
    <input type="text" name="debtor_contact">
  </label>

  <label>Amount Due
    <input type="number" step="0.01" name="amount_due" required>
  </label>

  <label>Due Date
    <input type="date" name="due_date">
  </label>

  <label style="grid-column:1/-1">Notes
    <textarea name="notes" rows="3"></textarea>
  </label>

  <label style="grid-column:1/-1">Assign to Collector (optional)
    <select name="assigned_collector_id">
      <option value="">-- None --</option>
      @foreach($collectors as $x)<option value="{{ $x->id }}">{{ $x->name }}</option>@endforeach
    </select>
  </label>

  <div style="grid-column:1/-1;display:flex;gap:8px;justify-content:flex-end">
    <a class="btn" href="{{ route('assignments.index') }}">Cancel</a>
    <button class="btn btn-primary" type="submit">Create Case</button>
  </div>
</form>
@endsection
