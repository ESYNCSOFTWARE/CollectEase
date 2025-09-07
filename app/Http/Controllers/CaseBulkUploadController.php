<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CaseFile;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class CaseBulkUploadController extends Controller
{
    public function showForm()
{
    return view('bulk-upload');
}

    public function handleUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt,xlsx|max:2048',
        ]);

        $file = $request->file('file');
        $rows = array_map('str_getcsv', file($file));

        // Remove header row
        $header = array_shift($rows);

        foreach ($rows as $row) {
            // 👇 This is where we fix the date parsing
            $due = $row[6]; // assuming 7th column is due_date
            try {
                if (strpos($due,'/') !== false) {
                    // format like 31/08/2025
                    $dueDate = Carbon::createFromFormat('d/m/Y', $due);
                } else {
                    // format like 2025-08-31
                    $dueDate = Carbon::parse($due);
                }
            } catch (\Exception $e) {
                $dueDate = null; // fallback if parsing fails
            }

            CaseFile::create([
                'reference_no' => 'CASE-'.Str::upper(Str::random(6)),
                'client_id' => 1, // you can map actual client here
                'case_type_id' => 1,
                'region_id' => 1,
                'debtor_name' => $row[3],
                'debtor_contact' => $row[4],
                'amount_due' => $row[5],
                'due_date' => $dueDate,
                'status' => 'New',
                'notes' => $row[7] ?? '',
            ]);
        }

        return redirect()->route('dashboard')
            ->with('success','Cases uploaded successfully!');
    }
}
