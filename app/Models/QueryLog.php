<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueryLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'answer_sheet_id', 'question', 'answer'
    ];

    public function items()
    {
        return $this->hasMany(QueryLogItem::class, 'query_log_id', 'id');
    }
}
