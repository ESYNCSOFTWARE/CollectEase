
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/assignments.css') }}">
@endsection

@section('content')
<h2 style="margin:6px 0 12px">Re-Assign Case</h2>

<div class="assignment-card">
  <div style="margin-bottom:8px">
    <strong>{{ $case->reference_no }}</strong> — {{ $case->debtor_name }}
  </div>
  <div style="color:#6b7280;margin-bottom:8px">
    Current Collector: <strong>{{ $case->assignedCollector->name ?? 'Unassigned' }}</strong>
  </div>

  <form method="post" action="{{ route('assignments.reassign', $case) }}" class="assignment-form" style="grid-template-columns:1fr 2fr">
    @csrf
    <label>New Collector
      <select name="collector_id" required>
        <option value="">-- Select --</option>
        @foreach($collectors as $x)<option value="{{ $x->id }}">{{ $x->name }}</option>@endforeach
      </select>
    </label>
    <label>Remarks
      <input type="text" name="remarks" placeholder="Why reassign? (optional)">
    </label>
    <div style="grid-column:1/-1;display:flex;gap:8px;justify-content:flex-end">
      <a class="btn" href="{{ route('assignments.index') }}">Back</a>
      <button class="btn btn-primary" type="submit">Re-Assign</button>
    </div>
  </form>
</div>
@endsection
