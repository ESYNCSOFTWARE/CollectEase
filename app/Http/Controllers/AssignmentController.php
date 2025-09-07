<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CaseFile;
use App\Models\Collector;
use App\Models\Assignment;
use App\Models\Escalation;

class AssignmentController extends Controller
{
    /**
     * 📌 Show all cases for assignment
     */
 public function form(Request $request)

    { 
        $clients = \App\Models\Client::all();
    $caseTypes = \App\Models\CaseType::all();
    $collectors = Collector::all();

    $query = CaseFile::with(['client','type'])
        ->whereNull('assigned_collector_id'); // only unassigned cases

    if ($request->client_id) {
        $query->where('client_id', $request->client_id);
    }

    if ($request->case_type_id) {
        $query->where('case_type_id', $request->case_type_id);
    }

    $cases = $query->get();

    return view('assignments.assign', compact('clients','caseTypes','collectors','cases'));
    }


public function bulkAssign(Request $request)
{
    $request->validate([
        'case_ids' => 'required|array',
        'collector_id' => 'required|exists:collectors,id',
    ]);

    foreach ($request->case_ids as $caseId) {
        $case = CaseFile::findOrFail($caseId);
        $case->assigned_collector_id = $request->collector_id;
        $case->status = 'Assigned';
        $case->save();

        Assignment::create([
            'case_id' => $case->id,
            'from_collector_id' => null,
            'to_collector_id' => $request->collector_id,
        ]);
    }

    return back()->with('success', 'Selected cases assigned successfully.');
}
    
    /**
     * 📌 Assign a case to a collector
     */
    public function store(Request $request)
    {
        $request->validate([
            'case_id' => 'required|exists:cases,id',
            'collector_id' => 'required|exists:collectors,id',
        ]);

        $case = CaseFile::findOrFail($request->case_id);
        $case->assigned_collector_id = $request->collector_id;
        $case->status = 'Assigned';
        $case->save();

        Assignment::create([
            'case_id' => $case->id,
            'from_collector_id' => null,
            'to_collector_id' => $request->collector_id,
        ]);

        return back()->with('success','Case assigned successfully.');
    }

    /**
     * 📌 Show re-assignment form
     */
    public function reassignForm(CaseFile $case)
    {
        $collectors = Collector::all();
        return view('assignments.reassign', compact('case','collectors'));
    }

    /**
     * 📌 Handle re-assignment
     */
 public function reassign(Request $request, CaseFile $case)
{
    $request->validate([
        'collector_id' => 'required|exists:collectors,id',
    ]);

    Assignment::create([
        'case_id' => $case->id,
        'from_collector_id' => $case->assigned_collector_id,
        'to_collector_id' => $request->collector_id,
    ]);

    $case->update([
        'assigned_collector_id' => $request->collector_id,
        'status' => 'Reassigned'
    ]);

    return redirect()->route('assignments.index')->with('success','Case reassigned successfully.');
}


    /**
     * 📌 Priority & Due Date Management
     */
 public function priorityDueDate(Request $request)
{
    $today = now();

    // Stats
    $overdueCount = CaseFile::where('due_date', '<', $today)->count();
    $dueSoonCount = CaseFile::whereBetween('due_date', [$today, $today->copy()->addDays(7)])->count();
    $upcomingCount = CaseFile::where('due_date', '>', $today->copy()->addDays(7))->count();

    // Build query with filters
    $query = CaseFile::with(['client','assignedCollector']);

    if ($request->client_id) {
        $query->where('client_id', $request->client_id);
    }

    if ($request->start_date && $request->end_date) {
        $query->whereBetween('due_date', [$request->start_date, $request->end_date]);
    }

    if ($request->status) {
        $query->where('status', $request->status);
    }

    if ($request->priority) {
        $query->where('priority', $request->priority);
    }

    $cases = $query->orderBy('due_date','asc')->get();

    // Clients for filter dropdown
    $clients = \App\Models\Client::all();

    return view('assignments.priority_due_date', compact(
        'overdueCount','dueSoonCount','upcomingCount','cases','clients'
    ));
     return redirect()->route('assignments.index')->with('success','Re-assigned successfully.');

}
    /**
     * 📌 Escalation Tracking Page
     */
    public function escalations()
    {
        $escalations = Escalation::with(['case','case.client','case.assignedCollector'])
            ->latest()
            ->get();

        return view('assignments.escalations', compact('escalations'));
    }

    /**
     * 📌 Escalate a case
     */
    public function escalateCase(Request $request, $id)
    {
        $case = CaseFile::findOrFail($id);

        $request->validate([
            'reason' => 'required|string|max:255',
            'escalated_to' => 'required|string|max:255',
        ]);

        Escalation::create([
            'case_id' => $case->id,
            'reason' => $request->reason,
            'escalated_to' => $request->escalated_to,
        ]);

        $case->status = 'Escalated';
        $case->save();

        return back()->with('success','Case escalated successfully.');
    }

    /**
     * 📌 Group Assignment (bulk assign)
     */
    public function groupAssignment(Request $request)
    {
        $query = CaseFile::query();

        if ($request->client_id) {
            $query->where('client_id',$request->client_id);
        }
        if ($request->region_id) {
            $query->where('region_id',$request->region_id);
        }
        if ($request->case_type_id) {
            $query->where('case_type_id',$request->case_type_id);
        }

        $cases = $query->get();

        foreach ($cases as $case) {
            Assignment::create([
                'case_id' => $case->id,
                'from_collector_id' => $case->assigned_collector_id,
                'to_collector_id' => $request->collector_id,
            ]);

            $case->assigned_collector_id = $request->collector_id;
            $case->status = 'Assigned';
            $case->save();
        }

        return back()->with('success','Group assignment completed.');
  
    }

    public function autoUpdatePriorities()
{
    $cases = CaseFile::all();

    foreach ($cases as $case) {
        if ($case->due_date < now()) {
            $case->priority = 'Critical';   // overdue
        } elseif ($case->due_date <= now()->addDays(7)) {
            $case->priority = 'High';       // due soon
        } elseif ($case->due_date <= now()->addDays(14)) {
            $case->priority = 'Medium';     // moderate
        } else {
            $case->priority = 'Low';        // safe
        }
        $case->save();
    }

    return back()->with('success', 'Priorities updated automatically based on due dates.');
}
}
