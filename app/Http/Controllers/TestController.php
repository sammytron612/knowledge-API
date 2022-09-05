<?php

namespace App\Http\Controllers;
use App\Models\Articles;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class TestController extends Controller
{
    public function test()
    {


        do a join quiery

        //$articles = Articles::with('section')->select('id','title','author','scope','status','sectionTitle','created_at')->first();
        $articles = Articles::
        dd($articles);
    }
}
