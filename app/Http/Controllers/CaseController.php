<?php

namespace App\Http\Controllers;

use App\Models\{CaseFile, Client, CaseType, Region, Collector, Assignment, CaseStatusHistory};
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CaseController extends Controller
{
    public function index(Request $request)
    {
        $q = CaseFile::with(['client','type','region','assignedCollector'])
            ->latest();

        if ($request->filled('status')) $q->where('status', $request->status);
        if ($request->filled('client_id')) $q->where('client_id', $request->client_id);
        if ($request->filled('region_id')) $q->where('region_id', $request->region_id);
        if ($request->filled('case_type_id')) $q->where('case_type_id', $request->case_type_id);

        return view('assignments.index', [
            'title'=>'Assignments','pageTitle'=>'All Assignments',
            'cases'=>$q->paginate(15),
        ]);
    }

    public function create()
    {
        return view('assignments.create', [
            'title'=>'New Assignment','pageTitle'=>'Create Case',
            'clients'=>Client::orderBy('name')->get(),
            'regions'=>Region::orderBy('name')->get(),
            'types'=>CaseType::orderBy('name')->get(),
            'collectors'=>Collector::where('active',true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => ['required','exists:clients,id'],
            'debtor_name' => ['required','string','max:255'],
            'debtor_contact' => ['nullable','string','max:255'],
            'amount_due' => ['required','numeric','min:0'],
            'due_date' => ['nullable','date'],
            'case_type_id' => ['required','exists:case_types,id'],
            'region_id' => ['nullable','exists:regions,id'],
            'notes' => ['nullable','string'],
            'assigned_collector_id' => ['nullable','exists:collectors,id'],
        ]);

        $data['reference_no'] = 'CASE-' . Str::upper(Str::random(8));

        DB::transaction(function () use ($data) {
            $case = CaseFile::create($data);

            if (!empty($data['assigned_collector_id'])) {
                Assignment::create([
                    'case_id' => $case->id,
                    'from_collector_id' => null,
                    'to_collector_id' => $data['assigned_collector_id'],
                    'remarks' => 'Assigned on creation',
                ]);
                $case->update(['status' => 'Assigned']);
                CaseStatusHistory::create([
                    'case_id' => $case->id,
                    'old_status' => 'New',
                    'new_status' => 'Assigned',
                    'remarks' => 'Auto status on initial assignment',
                ]);
            }
        });

        return redirect()->route('assignments.index')->with('success','Case created.');
    }
}
