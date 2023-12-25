<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class DaseboardController extends Controller
{
    //add article
    public function addArticle(Request $req)
    {

        $req->validate([
            'article' => "required"
        ]);


        if (!Article::count()) {
            $article = new Article();
        } else {
            $article = Article::first();
        }
        $article->article = $req->article;
        $article->save();

        return response()->json([
            "message" => "authorize",
            "status" => 'success',
            "data" => $req->article
        ], 200);;
    }
}
