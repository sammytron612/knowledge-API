<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Articles;
use App\Models\ArticleBody;
use App\Http\Resources\ArticlesCollection;
use App\Models\Uploads;
use App\Models\Section;
use App\Models\Authors;


class ApiController extends Controller
{

    public function search(Request $request)
    {


     /*   $api_token = $request->bearerToken();


        if($api_token !== 'testtoken')
        {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        } */

        do a join uery in here to join section

        $search = $request->searchTerm;

        $a = urldecode($search);

        return new ArticlesCollection(Articles::select('id','title','author','scope','status','section','created_at')->where('title','like','%'.$a.'%')->paginate(2));

    }

    public function create(Request $request)
    {

        $error = false;

        $array = ['title' => $request->title,
                'tags' => $request->tags,
                'section_id' => $request->section,
                'scope' => $request->scope,
                'status' => $request->status,
                'author' => $request->author,
            ];

        if(!Authors::find($reques->author))
        {
            $author = Authors::create(['author_id' => $request->author, 'name' => $request->name]);

            if(!$author) {$this->returnError();}
        }

        $article = Articles::create($array);

        if($article)
        {

            if($request->uploads)
            {
                $this->saveUploads($request->uploads, $article->id);
            }

            $article = Articles::findOrFail($article->id);
            $article->kb = $article->id + 1000;
            $article->save();

            $body = ArticleBody::create(['body' => $request->solution,  'article_id' => $article->id]);

            if(!$body)
            {

                $this->returnError();
            }

            return response()->Json(['message' => 'success',200]);
        }

        $this->returnError();
    }

    public function update(Request $request)
    {
        $array = ['title' => $request->title,
                'tags' => $request->tags,
                'section_id' => $request->section,
                'scope' => $request->scope,
                'status' => $request->status,

            ];

        $id = $request['id'];

        $article = Articles::findorFail($id);
        $article->update($array);
        $article->save();


        $articleBody = ArticleBody::where('article_id', $id)->firstOrFail();

        $articleBody->body = $request->solution;
        $articleBody->save();

        if($request->uploads)
        {
            $this->saveUploads($request->uploads, $article->id);
        }

        return response()->Json(['message' => 'success',200]);

    }


    public function show(Request $request)
    {

        $article = Articles::findOrFail($request->id);
        $article->views ++;
        $article->save();


        return response()->json(
            ['id' => $article->id,
            'title' => $article->title,
            'body' => $article->body->body,
            'tags' => $article->tags,
            'author' => $article->author,
            'scope' => $article->scope,
            'status' => $article->status,
            'kb' => $article->kb,
            'views' => $article->views,
            'section' => $article->section->title,
            'created' => $article->created_at,
            'uploads' => $article->uploads,
            ]
        );

    }

    public function returnBody(Request $request)
    {

        $article = Articles::findOrFail($request->id);

        if($article)
        {
            return response()->json(
                ['id' => $article->id,
                'title' => $article->title,
                'body' => $article->body->body,
                ],200);
        }

        $this->returnError();

    }

    public function deleteAttachment($id)
    {
        $response = Uploads::findorFail($id)->delete();

        return $response;
    }

    private function saveUploads($uploads, $articleId)
    {
        $error = false;
        $uploads = json_decode($uploads, true);

        foreach($uploads as $upload)
        {
            $array = ['name' => $upload['name'],
                    'path' => $upload['path'],
                    'article_id' => $articleId

        ];
            $uploads = Uploads::create($array);
            if(!$uploads){$error = true;}
        }

        if(!$error){
            return response()->Json(['message' => 'success',200]);
        }
        else{
            $this->returnError();
        }

    }

    private function returnError()
    {
        return response()->Json(['message' => 'error',500]);
    }
}
