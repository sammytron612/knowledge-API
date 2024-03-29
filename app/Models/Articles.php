<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articles extends Model
{
    use HasFactory;

    protected $fillable = ['title','section_id','tags','author','slug','kb','scope','expiry','status'];

    public function body()
    {
        return $this->hasOne(ArticleBody::class,'article_id');
    }

    public function section()
    {
        return $this->hasOne(Section::class,'id','section_id');
    }

    public function uploads()
    {
        return $this->hasMany(Uploads::class,'article_id');
    }

    public function sectionTitle() {
        return $this->section->title;
}

}
