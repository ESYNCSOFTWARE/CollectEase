<?php

namespace App\Http\Controllers;

use App\Models\{CaseFile, CaseStatusHistory};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StatusController extends Controller
{ public function index(Request $request)
{
    $query = CaseFile::with('statusHistory');

    // ✅ Add Search Filter
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('reference_no', 'like', "%{$search}%")
              ->orWhere('debtor_name', 'like', "%{$search}%")
              ->orWhereHas('client', fn($c) => $c->where('name', 'like', "%{$search}%"));
        });
    }

    // ✅ Optional Status Filter (if you want dropdown too)
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    return view('assignments.status', [
        'title'     => 'Case Status',
        'pageTitle' => 'Case Status',
        'cases'     => $query->latest()->paginate(20),
    ]);
}


    public function update(Request $request, CaseFile $case)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['New','Assigned','InProgress','Paid','Closed','Escalated'])],
            'remarks' => ['nullable','string'],
        ]);

        $old = $case->status;
        $case->update(['status'=>$data['status']]);

        CaseStatusHistory::create([
            'case_id'=>$case->id,
            'old_status'=>$old,
            'new_status'=>$data['status'],
            'remarks'=>$data['remarks'] ?? null,
        ]);

        return back()->with('success','Status updated.');
    }
}
