<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Resources\AuthorsResource;
use Illuminate\Support\Facades\DB;
use App\Models\Author;
use App\Models\Article;

class AuthorArticleController extends Controller
{
    use HttpResponses;

    public function showArticleAuthors(Article $article)
    {
        return AuthorsResource::collection(
            $article->authors
        );
    }

    public function storeArticleAuthor(Request $request)
    {
        $validated = $request->validate([
            'author_id' => ['required', 'exists:authors,id'],            
            'article_id' => ['required', 'exists:articles,id'],            
        ]);

        DB::table('article_author')->insert([
            'author_id' => $request->author_id,
            'article_id' => $request->article_id
        ]);

        return $this->success([], 'article author added successfully');
    }

    public function destroyArticleAuthors(Request $request)
    {
        $validated = $request->validate([
            'author_id' => ['required', 'exists:authors,id'],            
            'article_id' => ['required', 'exists:articles,id'],            
        ]);

        DB::table('article_author')
            ->where('author_id', $request->author_id)
            ->where('article_id', $request->article_id)
            ->delete();
        return $this->success([], 'article author deleted successfully');
    }
}
