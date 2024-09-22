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
}
