<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Articles;
use App\Http\Resources\ArticlesResource;
use App\Http\Resources\ArticlesCollection;

class SearchController extends Controller
{
    public function search(Request $request)
    {

        $search = $request->searchTerm;
        $a = urldecode($search);

        return new ArticlesCollection(Articles::select('id','title')->where('title','like','%'.$a.'%')->paginate(2));


    }
}
