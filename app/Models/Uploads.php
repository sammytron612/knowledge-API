<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uploads extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name','path','article_id'];

    public function article()
    {
        return $this->belongsTo(Article::class,'id','article_id');
    }
}
