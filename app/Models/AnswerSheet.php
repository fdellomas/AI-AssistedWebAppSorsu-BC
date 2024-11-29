<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnswerSheet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category', 'sub_category', 'answer', 'possible_questions'
    ];

    protected $casts = [
        'possible_questions' => 'array'
    ];
}
