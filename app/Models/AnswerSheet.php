<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerSheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'category', 'sub_category', 'answer', 'possible_questions'
    ];

    protected $casts = [
        'possible_questions' => 'array'
    ];
}
