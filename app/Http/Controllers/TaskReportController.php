<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskReportController extends Controller
{
    public function generateReport(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|string',
            'end_date' => 'required|string',
            'project_id' => 'required|integer',
            'user_id' => 'required|integer'
        ]);

        $startDate = Carbon::parse($validated['start_date'])->format('Y-m-d H:i:s');
        $endDate = Carbon::parse($validated['end_date'])->format('Y-m-d H:i:s');

        $project = null;
        if ($validated['project_id'] != 0) {
            $project = Project::find($validated['project_id']);
        }

        $user = null;
        if (!empty($validated['user_id'])) {
            $user = User::find($validated['user_id']);
        }

        $tasks = Task::with('project', 'user')
            ->when($validated['project_id'] != 0, function ($query) use ($validated) {
                $query->where('project_id', $validated['project_id']);
            })
            ->where('user_id', $validated['user_id'])
            ->whereDate('date_from', '>=', $startDate)
            ->whereDate('date_to', '<=', $endDate);

        $tasks = $tasks->get();
        $search_data = [
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'project_id' => $validated['project_id'],
            'user_name' => $user ? $user->name : null
        ];

        $pdf = PDF::loadView('pdf.report', compact('project', 'user', 'tasks', 'search_data'));

        return $pdf->download('generate_report.pdf');
    }
}
