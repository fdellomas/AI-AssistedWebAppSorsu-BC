<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use Spatie\PdfToText\Pdf;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\Tokenization\WordTokenizer;
// use Phpml\Classification\SVM;
use Phpml\Metric\Accuracy;

use Phpml\SupportVectorMachine\SVM;

class KnowledgeBaseController extends Controller
{
    public function index()
    {
        $files = Storage::files('/public/knowledge-base');
        $fileName = [];
        foreach ($files as $file) {
            $fileName[] = basename($file);
        }

        return response()->json([
            'files' => $fileName
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required'
        ]);
        return response()->json([
            'file' => $validated['file']
        ], 200);

        $files_arr = $request->input('file');
        $folder = 'public/knowledge-base';
        foreach ($files_arr as $file_p) {
            $fileName = $file_p->getClientOriginalName();

            if (Storage::exists($folder . '/' . $fileName)) {
                return response()->json([
                    'message' => 'File already exists'
                ], 409);
            }

            $path = $file_p->storeAs($folder, $fileName);
        }
        // $files = [];
        // $knowledge_base = Storage::files($folder);
        // foreach ($knowledge_base as $kb) {
        //     $files[] = basename($kb);
        // }
        return response()->json([
            'message' => 'OK'
        ], 200);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'file_name' => 'required'
        ]);
        $file_path = 'public/knowledge-base';
        if (Storage::exists($file_path . '/' . $request->file_name)) {
            Storage::delete($file_path . '/' . $request->file_name);
            $files = [];
            $knowledge_base = Storage::files($file_path);
            foreach ($knowledge_base as $kb) {
                $files[] = basename($kb);
            }
            return response()->json([
                'files' => $files
            ]);
        }

        return response()->json([
            'message' => 'File does not exist'
        ], 500);
    }

    public function test(Request $request)
    {
        $request->validate([
            'query' => 'required'
        ]);

        $possible_questions = [
            'what are the courses',
            'how to register',
        ];
        $content = [
            'BSIT, BSCS',
            'admission process',
        ];

        // Sample data with labels
        $samples = $possible_questions;
        $labels = [
            'courses',
            'admission'
        ];

        // Tokenizer
        $tokenizer = new WordTokenizer();
        $tokenized_samples = array_map([$tokenizer, 'tokenize'], $samples);

        // TfIdf Transformer
        $tfidf = new TfIdfTransformer();

        // Fit the transformer with the training samples
        $tfidf->fit($tokenized_samples);

        // Transform the training samples
        $tfidf->transform($tokenized_samples);

        // Train the classifier
        $classifier = new SVM();
        $classifier->train($tokenized_samples, $labels);

        // Tokenize and transform the query
        $query = $request->input('query');
        $tokenized_query = $tokenizer->tokenize($query);

        // Transform the query using the existing TfIdf model
        $tfidf->transform([$tokenized_query]); // Only transform new data

        // Predict the relevance of the query
        $prediction = $classifier->predict([$tokenized_query]);

        if (!$prediction) {
            return response()->json([
                'message' => 'Query is not relevant to the knowledge base.'
            ], 400);
        }

        return response()->json([
            'message' => 'OK'
        ]);
    }



    public static function getKnowledgeBase()
    {
        $file_path = 'public/knowledge-base';
        $knowledge_base = Storage::files($file_path);
        $pdfText = '';
        foreach ($knowledge_base as $file) {
            $mimeType = Storage::mimeType($file);
            if ($mimeType === 'application/pdf') {

            } elseif (in_array($mimeType, [
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
                'application/msword' 
            ])) {
                $word = IOFactory::load($file);
                foreach ($word->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        $pdfText .= $element->getText() . "\n\n";
                    }
                }
            }
        }
    }
}
