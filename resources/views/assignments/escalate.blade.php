
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/assignments.css') }}">
@endsection

@section('content')
<h2 style="margin:6px 0 12px">Escalate Case</h2>

<div class="assignment-card">
  <div style="margin-bottom:8px">
    <strong>{{ $case->reference_no }}</strong> — {{ $case->debtor_name }}
  </div>

  <form method="post" action="{{ route('assignments.escalate.store', $case) }}" class="assignment-form">
    @csrf
    <label>Level (1-5)
      <input type="number" name="level" min="1" max="5" value="1" required>
    </label>
    <label>Escalated To
      <input type="text" name="escalated_to" placeholder="">
    </label>
    <label style="grid-column:1/-1">Reason
      <textarea name="reason" rows="3" placeholder="Why escalate?"></textarea>
    </label>

    <div style="grid-column:1/-1;display:flex;gap:8px;justify-content:flex-end">
      <a class="btn" href="{{ route('assignments.index') }}">Cancel</a>
      <button class="btn btn-danger" type="submit">Escalate</button>
    </div>
  </form>
</div>
@endsection
