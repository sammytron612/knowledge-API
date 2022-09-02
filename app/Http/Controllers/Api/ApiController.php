<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Articles;
use App\Models\ArticleBody;
use App\Http\Resources\ArticlesCollection;
use App\Models\Uploads;


class ApiController extends Controller
{

    public function search(Request $request)
    {


     /*   $api_token = $request->bearerToken();


        if($api_token !== 'testtoken')
        {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        } */

        $search = $request->searchTerm;

        $a = urldecode($search);

        return new ArticlesCollection(Articles::select('id','title')->where('title','like','%'.$a.'%')->paginate(2));

    }

    public function create(Request $request)
    {

        $array = ['title' => $request->title,
                'tags' => $request->tags,
                'section_id' => $request->section,
                'scope' => $request->scope,
                'status' => $request->status,
                'author' => $request->author
            ];

        $article = Articles::create($array);

        if($article)
        {

            if($request->uploads)
            {
                $this->saveUploads($request->uploads, $article->id);
            }

            $article = Articles::find($article->id);
            $article->kb = $article->id + 1000;
            $article->save();

            $body = ArticleBody::create(['body' => $request->solution,  'article_id' => $article->id]);

            if($body)
            {

                return response()->Json(['message' => 'success',200]);
            }
        }


       return response()->Json(['message' => 'error',200]);
    }

    public function show(Request $request)
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

    public function returnBody(Request $request)
    {

        $article = Articles::find($request->id);

        return response()->json(
            ['id' => $article->id,
            'title' => $article->title,
            'body' => $article->body->body,
            ]);

    }

    private function saveUploads($uploads, $articleId)
    {
        $uploads = json_decode($uploads, true);

        foreach($uploads as $upload)
        {
            $array = ['name' => $upload['name'],
                    'path' => $upload['path'],
                    'article_id' => $articleId

        ];
            Uploads::create($array);
        }

        return;
    }
}
