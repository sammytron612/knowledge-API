<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Articles;


class ArticleController extends Controller
{
    public function returnBody(Request $request)
    {

        $article = Articles::find($request->id);

        return response()->json(
            ['id' => $article->id,
            'title' => $article->title,
            'body' => $article->body->body,
            'tags' => $article->tags,
            'author' => $article->author,
            'kb' => $article->kb,
            'views' => $article->views,
            'section' => $article->section->title,
            'created' => $article->created_at,
            ]
        );

    }

}

