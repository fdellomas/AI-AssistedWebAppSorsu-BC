<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueryLogItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'query_log_id',
        'answer_sheet_id',
    ];

    public function answer()
    {
        return $this->belongsTo(AnswerSheet::class, 'answer_sheet_id', 'id');
    }
}
