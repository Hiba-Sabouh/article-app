<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use App\Http\Resources\AuthorsResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreAuthorRequest;
use App\Traits\HttpResponses;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class AuthorController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return AuthorsResource::collection(
            Author::all()
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
    public function store(StoreAuthorRequest $request)
    {
        $request->validated($request->all());
        $author = Author::create([
            'name' => $request->name,
            'email' => $request->email,
            'country' => $request->country,
        ]);

        return $this->success([new AuthorsResource($author)], 'author stored successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Author $author)
    {
        return new AuthorsResource($author);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Author $author)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Author $author)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:255'],            
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($author->id)],            
            'country' => ['required','string','max:255'], 
        ]);
        $author->update($request->all());

        return $this->success([new AuthorsResource($author)], 'author updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author)
    {
        DB::table('article_author')->where('author_id', $author->id)->delete();
        $author->delete();
        return $this->success([], 'author deleted successfully');

    }
}
