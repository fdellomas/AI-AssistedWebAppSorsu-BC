<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Tokenization\WhitespaceTokenizer;
use Phpml\Math\Distance\Cosine;
use App\Models\AnswerSheet;
use App\Models\QueryLog;
use App\Models\QueryLogItem;

class QueryController extends Controller
{
    public function query(Request $request)
    {
        $sheets = AnswerSheet::all();
        $samples = [];
        $labels = [];
        foreach ($sheets as $key => $value) {
            // $question_array = $value->possible_questions;
            $label = $value->category;
            foreach ($value->possible_questions as $question) {
                $samples[] = $question;
                $labels[] = $label;
            }
        }
        $vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer());
        $vectorizer->fit($samples);
        $vectorizer->transform($samples);

        $transformer = new TfIdfTransformer($samples);
        $transformer->transform($samples);

        // Process the input query
        $inputQuery = strtolower($request->input('question'));

        // Split the query into sub-questions
        $subQueries = preg_split('/[?.!;]+/', $inputQuery, -1, PREG_SPLIT_NO_EMPTY);

        $answers = [];

        foreach ($subQueries as $subQuery) {
            $inputSample = [trim($subQuery)];
            $vectorizer->transform($inputSample);
            $transformer->transform($inputSample);

            // Compute similarity with each sample
            $maxSimilarity = -1;
            $bestAnswer = '';

            foreach ($samples as $index => $sample) {
                $similarity = $this->cosineSimilarity($inputSample[0], $sample);
                if ($similarity > $maxSimilarity) {
                    $maxSimilarity = $similarity;
                    $bestAnswer = $labels[$index];
                }
            }

            if ($bestAnswer) {
                $answers[] = $bestAnswer;
            }
        }

        // Combine all answers into a single response
        $finalAnswer = implode(' ', $answers);

        $queried_answer = AnswerSheet::where('category', $finalAnswer)->get();

        $query_log = QueryLog::create([
            'user_id' => $request->user_id,
            'question' => $request->question,
        ]);

        foreach ($queried_answer as $key => $value) {
            QueryLogItem::create([
                'query_log_id' => $query_log->id,
                'answer_sheet_id' => $value['id'],
            ]);
        }

        $new_log = QueryLog::with(['items', 'items.answer'])->where('id', $query_log->id)->first();

        return response()->json([
            'answer' => $new_log,
        ]);
    }

    protected function cosineSimilarity(array $vec1, array $vec2): float
    {
        // $dotProduct = 0.0;
        // $magnitude1 = 0.0;
        // $magnitude2 = 0.0;
    
        // foreach ($vec1 as $i => $value1) {
        //     $value2 = $vec2[$i];
        //     $dotProduct += $value1 * $value2;
        //     $magnitude1 += $value1 ** 2;
        //     $magnitude2 += $value2 ** 2;
        // }
    
        // if ($magnitude1 == 0 || $magnitude2 == 0) {
        //     return 0.0; // Avoid division by zero
        // }
    
        // return $dotProduct / (sqrt($magnitude1) * sqrt($magnitude2));
        $dotProduct = 0.0;
        $magnitude1 = 0.0;
        $magnitude2 = 0.0;

        foreach ($vec1 as $i => $value1) {
            $value2 = $vec2[$i];
            $dotProduct += $value1 * $value2;
            $magnitude1 += $value1 ** 2;
            $magnitude2 += $value2 ** 2;
        }

        if ($magnitude1 == 0 || $magnitude2 == 0) {
            return 0.0; // Avoid division by zero
        }

        return $dotProduct / (sqrt($magnitude1) * sqrt($magnitude2));
    }
}
