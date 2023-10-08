<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Resources\ArticlesResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreArticleRequest;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ArticleController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ArticlesResource::collection(
            Article::all()
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        $request->validated($request->all());

        if($request->image){
            $ext = $request->file('image')->getClientOriginalExtension();
            $imageFileName = time().".".$ext;
            $path = 'images/articles';
            $request->file('image')->move($path,$imageFileName);
            $image = $path.'/'.$imageFileName ;
        } else {
            $image = null;
        }

        $article = Article::create([
            'title' => $request->title,
            'publication_date' => $request->publication_date,
            'body' => $request->body,
            'image' => $image
        ]);

        return new ArticlesResource($article);
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        return new ArticlesResource($article);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Article $article)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreArticleRequest $request, Article $article)
    {
        $request->validated($request->all());

        DB::transaction(function() use ($article, $request) {
            if(!is_null($request->image)){
                $path = $article->image;
                if(File::exists($path)){
                    File::delete($path);
                }
                $ext = $request->file('image')->getClientOriginalExtension();
                $imageFileName = time().".".$ext;
                $path = 'images/products';
                $request->file('image')->move($path,$imageFileName);
                $imageFileNameWithPath = $path.'/'.$imageFileName;
    
            }else{
                $imageFileNameWithPath = $product->image;
            }

            $product->update([
                'title' => $request->title,
                'publication_date' => $request->publication_date,
                'body' => $request->body,
                'image' =>  $imageFileNameWithPath,
            ]);
        });
        return $this->success([new ArticlesResource($article)], 'article updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        DB::transaction(function() use ($article) {
            DB::table('article_author')->where('article_id', $article->id)->delete();
            $path = $article->image;
            if(File::exists($path)){
                File::delete($path);
            }  
            $article->delete();
        });

        return $this->success([], 'article deleted successfully');

    }

    
}
