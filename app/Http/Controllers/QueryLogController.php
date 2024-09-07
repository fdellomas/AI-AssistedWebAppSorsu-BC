<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QueryLog;

class QueryLogController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $logs = QueryLog::with(['items', 'items.answer'])
            ->where('user_id', $request->user_id)
            ->orderByDesc('created_at')
            ->take(5)
            ->get()
            ->reverse()
            ->values();

        return response()->json([
            'query_log' => $logs
        ]);
    }

    public function oldQueries(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'last_query_id' => 'required|exists:query_logs,id'
        ]);

        $old_logs = QueryLog::with(['items', 'items.answer'])
            ->where('id', '<', $request->last_query_id)
            ->orderByDesc('created_at')
            ->take(5)
            ->get()
            ->reverse()
            ->values();

        return response()->json([
            'query_log' => $old_logs
        ]);
    }

    public static function store($question, $anwser)
    {
        QueryLog::create([
            'user_id' => auth()->user()->id,
            'answer_sheet_id' => $anwser,
            'question' => $question,
        ]);
    }
}
