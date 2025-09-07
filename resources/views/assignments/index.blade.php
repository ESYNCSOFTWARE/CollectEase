@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/assignments.css') }}">

@endsection
@section('styles')
<style>
 /* Card container */
.filter-card {
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 10px;
  padding: 16px;
  margin-top: 16px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

/* Grid layout */
.filter-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: 16px;
  align-items: end;
}

/* Filter group */
.filter-group label {
  display: block;
  font-size: 13px;
  font-weight: 600;
  color: #374151;
  margin-bottom: 4px;
}
.filter-select {
  width: 100%;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  padding: 6px 10px;
  font-size: 14px;
  background: #fff;
  transition: 0.2s;
}
.filter-select:focus {
  border-color: #2563eb;
  outline: none;
  box-shadow: 0 0 0 2px rgba(37,99,235,0.2);
}

/* Actions row */
.filter-actions {
  display: flex;
  justify-content: flex-end;
  gap: 8px;
  grid-column: 1 / -1; /* full width row */
}

/* Buttons */
.btn-primary-sm {
  background: #2563eb;
  color: #fff;
  padding: 6px 14px;
  font-size: 14px;
  border-radius: 6px;
  text-decoration: none;
  transition: 0.2s;
}
.btn-primary-sm:hover { background: #1e40af; }

.btn-secondary-sm {
  background: #f3f4f6;
  color: #111827;
  padding: 6px 14px;
  font-size: 14px;
  border-radius: 6px;
  text-decoration: none;
  transition: 0.2s;
}
.btn-secondary-sm:hover { background: #e5e7eb; }


</style>
@endsection


@section('content')
<div class="assignment-container">
  <div class="flex items-center justify-between" style="display:flex;align-items:center;justify-content:space-between;gap:12px">
    <h2 style="margin:0">All Cases</h2>
    <div style="display:flex;gap:8px">
      <a class="btn btn-primary" href="{{ route('assignments.create') }}">Create Case</a>
      <a class="btn" href="{{ route('assignments.assign.form') }}">Assign Cases</a>
      <a class="btn" href="{{ route('assignments.status') }}">Status Monitor</a>
    </div>
  </div>



  {{-- Filters --}}
 {{-- Filters --}}
<div style="
    background:#fff;
  
     
    padding:16px;
    margin-top:20px;
    box-shadow:0 2px 6px rgba(0,0,0,0.05);
">
  <form method="get" action="{{ route('assignments.index') }}" 
        style="display:flex;flex-wrap:wrap;gap:16px;align-items:center;">

    <!-- Status -->
    <div style="display:flex;align-items:center;gap:6px;">
      <span style="font-size:14px;font-weight:600;color:#374151;">Status</span>
      <select name="status" 
        style="border:1px solid #cbd5e1;border-radius:6px;padding:6px 10px;font-size:14px;background:#fff;">
        <option value="">-- Any --</option>
        @foreach(['New','Assigned','InProgress','Paid','Closed','Escalated', 'Reassigned'] as $s)
          <option value="{{ $s }}" @selected(request('status')===$s)>{{ $s }}</option>
        @endforeach
      </select>
    </div>

    <!-- Client -->
    <div style="display:flex;align-items:center;gap:6px;">
      <span style="font-size:14px;font-weight:600;color:#374151;">Client</span>
      <select name="client_id" 
        style="border:1px solid #cbd5e1;border-radius:6px;padding:6px 10px;font-size:14px;background:#fff;">
        <option value="">-- Any --</option>
        @foreach(\App\Models\Client::orderBy('name')->get() as $c)
          <option value="{{ $c->id }}" @selected(request('client_id')==$c->id)>{{ $c->name }}</option>
        @endforeach
      </select>
    </div>

    <!-- Region -->
    <div style="display:flex;align-items:center;gap:6px;">
      <span style="font-size:14px;font-weight:600;color:#374151;">Region</span>
      <select name="region_id" 
        style="border:1px solid #cbd5e1;border-radius:6px;padding:6px 10px;font-size:14px;background:#fff;">
        <option value="">-- Any --</option>
        @foreach(\App\Models\Region::orderBy('name')->get() as $r)
          <option value="{{ $r->id }}" @selected(request('region_id')==$r->id)>{{ $r->name }}</option>
        @endforeach
      </select>
    </div>

    <!-- Case Type -->
    <div style="display:flex;align-items:center;gap:6px;">
      <span style="font-size:14px;font-weight:600;color:#374151;">Case Type</span>
      <select name="case_type_id" 
        style="border:1px solid #cbd5e1;border-radius:6px;padding:6px 10px;font-size:14px;background:#fff;">
        <option value="">-- Any --</option>
        @foreach(\App\Models\CaseType::orderBy('name')->get() as $t)
          <option value="{{ $t->id }}" @selected(request('case_type_id')==$t->id)>{{ $t->name }}</option>
        @endforeach
      </select>
    </div>

    <!-- Buttons -->
    <div style="margin-left:auto;display:flex;gap:8px;">
      <a href="{{ route('assignments.index') }}" 
         style="background:#f3f4f6;color:#111827;padding:6px 14px;font-size:14px;border-radius:6px;text-decoration:none;
                border:1px solid #d1d5db;">
         Reset
      </a>
      <button type="submit" 
         style="background:#2563eb;color:#fff;padding:6px 14px;font-size:14px;border:none;border-radius:6px;cursor:pointer;
                box-shadow:0 1px 3px rgba(0,0,0,0.15);">
         Apply
      </button>
    </div>
  </form>
</div>



 
</div>
<table class="assignment-table">
  <thead>
    <tr>
      <th>Ref</th>
      <th>Debtor</th>
      <th>Client</th>
      <th>Type</th>
      <th>Region</th>
      <th>Status</th>
      <th>Collector</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    @forelse($cases as $c)
      @php
        $color = $c->region->color ?? $c->client->color ?? $c->type->color ?? '#ddd';
      @endphp
      <tr style="border-left:6px solid {{ $color }}">
        <td>{{ $c->reference_no }}</td>
        <td>{{ $c->debtor_name }}</td>
        <td>{{ $c->client->name ?? '-' }}</td>
        <td>{{ $c->type->name ?? '-' }}</td>
        <td>{{ $c->region->name ?? '-' }}</td>
        <td><span class="status-badge status-{{ $c->status }}">{{ $c->status }}</span></td>
        <td>{{ $c->assignedCollector->name ?? '-' }}</td>
        <td style="display:flex;gap:6px;flex-wrap:wrap">
          <a class="btn" href="{{ route('assignments.reassign.form',$c) }}">Reassign</a>
          <a class="btn btn-danger" href="{{ route('assignments.escalate.form',$c) }}">Escalate</a>
        </td>
      </tr>
    @empty
      <tr><td colspan="8">No cases found.</td></tr>
    @endforelse
  </tbody>
</table>

<div style="margin-top:12px">
  {{ $cases->links() }}
</div>
@endsection
