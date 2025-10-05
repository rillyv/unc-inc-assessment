<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            Article::with('user')->latest()->paginate(10),
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if (
            $user->role !== UserRole::ADMIN ||
            $user->role !== UserRole::AUTHOR
        ) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'title' => 'required|min:3',
            'content' => 'required|min:30',
            'image_path' => 'nullable|string',
        ]);

        $data['user_id'] = Auth::id();

        $article = Article::create($data);

        // TODO: call Python microservice for summary + keywords
        return response()->json($article, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(Article::with('user')->findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = $request->user();

        $article = Article::with('user')->findOrFail($id);

        if ($user->role !== UserRole::ADMIN && $article->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'title' => 'sometimes|min:3',
            'content' => 'sometimes|min:30',
            'image_path' => 'nullable|string',
        ]);

        $article->update($data);

        return response()->json($article);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
