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
use App\Models\User;
use Phpml\Classification\KNearestNeighbors;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;

class QueryController extends Controller
{
    public function query(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required',
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::find($validated['user_id']);

        if (User::where('id', $validated['user_id'])->where('question_limit', '>=', 10)->exists()) {
            $newLog = QueryLog::create([
                'user_id' => $validated['user_id'],
                'question' => $validated['question'],
                'answer' => 'You have reached the limit of 10 questions. Please try again some time after further system updates.'
            ]);
            return response()->json([

            ], 500);
        }

        function cosineSimilarity($vec1, $vec2) {
            $intersection = array_intersect_key($vec1, $vec2);
            $dotProduct = 0;
            $normA = 0;
            $normB = 0;
        
            foreach ($intersection as $key => $value) {
                $dotProduct += $vec1[$key] * $vec2[$key];
            }
        
            foreach ($vec1 as $key => $value) {
                $normA += $value ** 2;
            }
        
            foreach ($vec2 as $key => $value) {
                $normB += $value ** 2;
            }
        
            return $dotProduct / (sqrt($normA) * sqrt($normB));
        }
        
        function textToVector($text) {
            $words = explode(' ', strtolower($text));
            $vector = [];
            foreach ($words as $word) {
                $vector[$word] = ($vector[$word] ?? 0) + 1;
            }
            return $vector;
        }

        $samples = [];
        $sheets = AnswerSheet::all();
        foreach ($sheets as $key => $value) {
            $label = $value->answer;
            foreach ($value->possible_questions as $question) {
                $sample = textToVector($question);
                $samples[] = ['label' => $label, 'vector' => $sample];
            }
        }
        $queryVector = textToVector($validated['question']);
        
        $threshold = 0.1;
        $predictedLabel = 'Unrelated';
        $highestSimilarity = 0;
        
        foreach ($samples as $sample) {
            $similarity = cosineSimilarity($queryVector, $sample['vector']);
            if ($similarity > $highestSimilarity && $similarity > $threshold) {
                $highestSimilarity = $similarity;
                $predictedLabel = $sample['label'];
            }
        }

        $response = $predictedLabel;

        if ($predictedLabel != 'Unrelated') {
            $client = new Client();
            $prompt = "Question: " . $validated['question'] . "\nReference: " . $predictedLabel;
            try {
                $body = $client->post('https://api.openai.com/v1/chat/completions', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                        'Content-Type' => 'application/json',
                    ],
                    'json' => [
                        'model' => 'gpt-3.5-turbo',
                        'messages' => [
                            [
                                'role' => 'system',
                                'content' => 'You are a helpful assistant.'
                            ],
                            [
                                'role' => 'user',
                                'content' => $prompt
                            ]
                        ],
                        'max_tokens' => 150,
                        'temperature' => 0.7,
                    ],
                ]);
                $response = json_decode($body->getBody(), true);
                $answer = $response['choices'][0]['message']['content'];
                $newLog = QueryLog::create([
                    'user_id' => $validated['user_id'],
                    'question' => $validated['question'],
                    'answer' => $answer
                ]);
                $question_limit = $user->question_limit ?? 0;
                $question_limit++;
                $user->update([
                    'question_limit' => $question_limit
                ]);
                return response()->json([
                    'message' => 'OK',
                    'answer' => $newLog
                ], 200);
            } catch (ClientException $e) {
                if ($e->getCode() === 429) {
                    Log::warning('API rate limit exceeded: ' . $e->getMessage());
                    return response()->json([
                        'error' => 'The system is temporarily busy. Please try again later.'
                    ], 429);
                } else {
                    throw $e;
                }
            }
        }

        $log = QueryLog::create([
            'user_id' => $validated['user_id'],
            'question' => $validated['question'],
            'answer' => "I'm sorry, but I couldn't find relevant information in the knowledge base for that question. Could you try rephrasing or asking a different question?"
        ]);
        
        return response()->json([
            'message' => $predictedLabel !== 'Unrelated' ? 'OK' : 'Unrelated',
            'answer' => $log
        ], 200);
    }


}
