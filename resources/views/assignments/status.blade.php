@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/assignments.css') }}">
@endsection

@section('content')
 
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
  <h2 style="margin:0;font-size:20px;font-weight:700;color:#111827;">Case Status Monitoring</h2>

  <!-- Search Bar -->
  <form method="GET" action="{{ route('assignments.status') }}" style="position:relative;">
    <input type="text" name="search" value="{{ request('search') }}" 
      placeholder="Search cases..." 
      style="padding:8px 36px 8px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;min-width:220px;">
    
    <!-- Search Icon -->
    <span style="position:absolute;right:12px;top:50%;transform:translateY(-50%);color:#9ca3af;">
       
    </span>
  </form>
</div>

@foreach($cases as $c)
  @php
    $color = $c->region->color ?? $c->client->color ?? $c->type->color ?? '#ddd';
  @endphp
  <div class="assignment-card" style="border-left:6px solid {{ $color }}">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:12px">
      <div>
        <strong>{{ $c->reference_no }}</strong> — {{ $c->debtor_name }}
        <span class="status-badge status-{{ $c->status }}" style="margin-left:8px">{{ $c->status }}</span>
        <div style="font-size:12px;color:#6b7280">Client: {{ $c->client->name ?? '-' }} · Region: {{ $c->region->name ?? '-' }}</div>
      </div>

      <form method="post" action="{{ route('assignments.status.update',$c) }}" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
        @csrf
        <select name="status" required>
          @foreach(['New','Assigned','InProgress','Paid','Closed','Escalated'] as $s)
            <option value="{{ $s }}" @selected($c->status === $s)>{{ $s }}</option>
          @endforeach
        </select>
        <input type="text" name="remarks" placeholder="Remarks">
        <button class="btn btn-primary" type="submit">Update</button>
      </form>
    </div>

    @if($c->statusHistory->count())
      <details style="margin-top:8px">
        <summary>History ({{ $c->statusHistory->count() }})</summary>
        <ul style="margin:6px 0 0 18px">
          @foreach($c->statusHistory as $h)
            <li style="font-size:13px;color:#374151">
              {{ $h->created_at->format('Y-m-d H:i') }} — {{ $h->old_status ?? '-' }} → {{ $h->new_status }}
              @if($h->remarks) — <em style="color:#6b7280">{{ $h->remarks }}</em>@endif
            </li>
          @endforeach
        </ul>
      </details>
    @endif
  </div>
@endforeach

<div style="margin-top:12px">
  {{ $cases->links() }}
</div>
@endsection
