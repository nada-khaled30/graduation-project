<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ArticleController extends Controller
{
    // ✅ عرض جميع المقالات
    public function index(Request $request)
{
    $query = $request->input('q');
    $user = Auth::user(); // المستخدم الحالي

    $articles = Article::query();

    if ($query) {
        $articles->where('title', 'like', "%$query%")
                 ->orWhere('body', 'like', "%$query%");
    }

    $articles = $articles->get();

    $data = $articles->map(function ($article) use ($user) {
        return [
            'id' => $article->id,
            'title' => $article->title,
            'body' => $article->body,
            'image' => $article->image,
            'created_at' => $article->created_at->format('j M Y'),
            'favorite' => $user ? $user->favorites->contains($article->id) : false
        ];
    });

    return response()->json([
        'success' => true,
        'message' => 'Articles retrieved successfully',
        'data' => $data
    ]);
}

    // ✅ إنشاء مقالة جديدة
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'image' => 'nullable|string',
        ]);

        $article = Article::create($request->all());

        return response()->json([
            'message' => 'Article created successfully',
            'data' => $article
        ], 201);
    }

    // ✅ عرض مقالة حسب ID
    public function show($id)
{
    $article = Article::findOrFail($id);
    $user = Auth::user();

    return response()->json([
        'message' => 'Article retrieved successfully',
        'data' => [
            'id' => $article->id,
            'title' => $article->title,
            'body' => $article->body,
            'image' => $article->image,
            'created_at' => $article->created_at->format('j M Y'),
            'favorite' => $user ? $user->favorites->contains($article->id) : false
        ]
    ], 200);
}



    public function toggleFavorite($id)
{
    $article = Article::findOrFail($id);
    $user = Auth::user();

    $user->favorites()->toggle($article->id);

    return response()->json([
        'message' => 'Favorite status updated',
        'favorited' => $user->favorites->contains($id)
    ]);
}

public function share($id)
{
    $article = Article::findOrFail($id);
    $link = url("/api/v1/articles/$id"); // رابط الـ API نفسه

    return response()->json([
        'message' => 'Share link generated',
        'share_link' => $link
    ]);
}

public function download($id)
{
    $article = Article::findOrFail($id);

    $pdf = Pdf::loadHtml("
        <h1>{$article->title}</h1>
        <p>{$article->body}</p>
    ");

    return $pdf->download("article_{$article->id}.pdf");
}

public function myFavorites()
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['error' => 'Unauthenticated'], 401);
    }

    $favorites = $user->favorites()->get();

    return response()->json([
        'message' => 'All favorite articles retrieved successfully',
        'data' => $favorites
    ]);
}

public function showFavorite($id)
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['error' => 'Unauthenticated'], 401);
    }

    $favorite = $user->favorites()->where('articles.id', $id)->first();

    if (!$favorite) {
        return response()->json(['error' => 'Favorite article not found'], 404);
    }

    return response()->json([
        'message' => 'Favorite article retrieved',
        'data' => $favorite
    ]);
}


public function removeFavorite($id)
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['error' => 'Unauthenticated'], 401);
    }

    $user->favorites()->detach($id);

    return response()->json([
        'message' => 'Article removed from favorites'
    ]);
}

}
