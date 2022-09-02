<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleBody extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['body','article_id'];

    public function article()
    {
        return $this->belongsTo(Articles::class);
    }
}
