<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['title'];

    public function article()
    {
        return $this->belongsTo(Article::class,'id','section_id');
    }
}
