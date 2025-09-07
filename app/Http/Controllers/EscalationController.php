<?php

namespace App\Http\Controllers;

use App\Models\{CaseFile, Escalation, CaseStatusHistory};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EscalationController extends Controller
{
    public function create(CaseFile $case)
    {
        return view('assignments.escalate', [
            'title'=>'Escalate Case','pageTitle'=>'Escalation',
            'case'=>$case,
        ]);
    }

    public function store(Request $request, CaseFile $case)
    {
        $data = $request->validate([
            'level' => ['required','integer','min:1','max:5'],
            'escalated_to' => ['nullable','string','max:255'],
            'reason' => ['nullable','string'],
        ]);

        DB::transaction(function () use ($case, $data) {
            Escalation::create([
                'case_id'=>$case->id,
                'level'=>$data['level'],
                'escalated_to'=>$data['escalated_to'] ?? null,
                'reason'=>$data['reason'] ?? null,
            ]);

            $old = $case->status;
            $case->update(['status'=>'Escalated']);

            CaseStatusHistory::create([
                'case_id'=>$case->id,
                'old_status'=>$old,
                'new_status'=>'Escalated',
                'remarks'=>'Escalated: '.($data['reason'] ?? ''),
            ]);
        });

        return redirect()->route('assignments.index')->with('success','Escalated.');
    }
}
