<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Articles;
use App\Models\ArticleBody;
use App\Models\Uploads;
use App\Models\Authors;
use App\Http\Resources\Articlescollection;
use Illuminate\Support\Facades\DB;
use App\Models\Section;
use \Carbon\Carbon;
use Illuminate\Support\Facades\Cache;



class ApiController extends Controller
{
    public function inCommentSearch(Request $request)
    {
        $search = $request->searchTerm;

        $searchTerm = urldecode($search);

        $articles = Articles::select('id','title')
                    ->where('scope', 'Public')
                    ->where( function ($query) use($searchTerm){
                        $query->where('title','like', '%'.$searchTerm.'%')
                            ->orWhere('tags','like', '%'.$searchTerm.'%');
                    })
                    ->limit(20)->get();

        return response()->json($articles);

    }

    public function search(Request $request)
    {


        $search = $request->searchTerm;

        $searchTerm = urldecode($search);

        $articles = DB::table('articles')
            ->join('authors', 'author', '=', 'authors.author_id')
            ->join('sections', 'section_id', '=', 'sections.id')
            ->select('articles.id as id','articles.title as article_title','articles.views as views','articles.created_at','authors.name as author_name', 'articles.kb as kb', 'sections.title as section_title')
            ->where('articles.status','Published')
            ->where(function ($query) use($searchTerm)
            {
                $query->where('articles.title','like','%'.$searchTerm.'%')
                      ->orWhere('articles.tags','like','%'.$searchTerm.'%');
            })
            ->paginate(20);


        return response()->Json($articles);

        //return new ArticlesCollection($articles);

    }

    public function create(Request $request)
    {

        $error = false;

        $array = ['title' => $request->title,
                'tags' => $request->tags,
                'section_id' => $request->section,
                'scope' => $request->scope,
                'status' => $request->status,
                'expiry' => $request->expiry,
                'author' => $request->author,

            ];

        if(!Authors::find($request->author))
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
                'expiry' => $request->expiry,
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

        $article = Articles::with('uploads')->findOrFail($request->id);
        $article->views ++;
        $article->save();

        ;

        $articles = DB::table('articles')
            ->join('authors', 'author', '=', 'authors.author_id')
            ->join('sections', 'section_id', '=', 'sections.id')
            ->join('article_bodies','articles.id','=','article_bodies.article_id')
            ->select('articles.id as id','articles.title as article_title','articles.views as views','articles.tags as tags','articles.created_at','article_bodies.body as body','authors.name as author_name', 'articles.author as author_id', 'articles.kb as kb', 'articles.scope as scope', 'articles.expiry as expiry','articles.status as status','articles.expiry as expiry','sections.id as section_id', 'sections.title as section_title')
            ->where('articles.id',$request->id)
            ->first();

            return response()->json([$articles,
                                    'uploads' => $article->uploads

        ]);

    }

    public function showAll(Request $request)
    {
        $articles = DB::table('articles')
            ->join('authors', 'author', '=', 'authors.author_id')
            ->join('sections', 'section_id', '=', 'sections.id')
            ->join('article_bodies','articles.id','=','article_bodies.article_id')
            ->select('articles.id as id','articles.title as article_title','articles.views as views','articles.created_at','authors.name as author_name', 'articles.author as author_id', 'articles.kb as kb', 'articles.scope as scope','articles.status as status','sections.title as section_title')
            ->paginate(30);

        $expire = Carbon::now()->addMinutes(30);

        $viewed = Cache::remember('viewed', $expire, function() {
            return Articles::orderBy('views','desc')->limit(5)->get();
        });

        $recent = Cache::remember('recent', $expire, function() {
            return Articles::orderBy('created_at','desc')->limit(5)->get();
        });

            return response()->json(['articles' => $articles,'viewed' => $viewed, 'recent' => $recent],200);
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
