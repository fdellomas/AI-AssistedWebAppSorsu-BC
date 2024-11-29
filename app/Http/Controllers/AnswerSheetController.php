<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnswerSheet;
use App\Http\Requests\AnswerSheet\StoreRequest;

class AnswerSheetController extends Controller
{
    public function index()
    {
        $answers = AnswerSheet::all();

        return response()->json(['answers' => $answers]);
    }

    public function show(AnswerSheet $answer)
    {
        return response()->json([
            'message' => 'OK',
            'answer' => $answer
        ], 200);
    }

    public function update(AnswerSheet $answer, Request $request)
    {
        $validated = $request->validate([
            'category' => 'required',
            'answer' => 'required',
            'possible_questions' => 'required|array',
        ]);
        $result = $answer->update($validated);
        if (!$result) {
            return response()->json([
                'message' => 'Failed to update knowledge base',
            ], 400);
        }
        return response()->json([
            'message' => 'OK',
        ], 200);
    }

    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $result = AnswerSheet::create($validated);

        if ($result) {
            return response()->json(['message' => 'OK']);
        }

        return response()->json(['message' => 'Failed'], 500);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'answer_id' => 'required|exists:answer_sheets,id'
        ]);

        $answer = AnswerSheet::find($request->answer_id);
        $answer->delete();
        $answers = AnswerSheet::all();

        return response()->json([
            'answers' => $answers
        ]);
    }

    public function archive()
    {
        $archive = AnswerSheet::onlyTrashed()->get();
        return response()->json([
            'message' => 'OK',
            'archive' => $archive,
        ], 200);
    }

    public function restore($id)
    {
        $answer = AnswerSheet::withTrashed()->findOrFail($id);
        $result = $answer->restore();
        if (!$result) {
            return response()->json([
                'message' => 'Failed to restore'
            ], 400);
        }
        $archive = AnswerSheet::onlyTrashed()->get();
        return response()->json([
            'message' => 'OK',
            'archive' => $archive,
        ], 200);
    }

    public function destroy($id)
    {
        $answer = AnswerSheet::withTrashed()->findOrFail($id);
        $result = $answer->forceDelete();
        if (!$result) {
            return response()->json([
                'message' => 'Failed to permanently delete',
            ], 400);
        }
        $archive = AnswerSheet::onlyTrashed()->get();
        return response()->json([
            'message' => 'OK',
            'archive' => $archive,
        ], 200);
    }
}
