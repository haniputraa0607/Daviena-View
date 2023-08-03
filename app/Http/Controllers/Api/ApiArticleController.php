<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiArticleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $article = $request->length ?  Article::paginate($request->length ?? 10) : Article::get();
        return $this->ok("Success Get Data All article", $article);
    }

    public function show(Article $article): JsonResponse
    {
        return $this->ok("Success", $article);
    }

    public function store(Request $request): JsonResponse
    {
        $article = Article::create($request->all());
        return $this->ok("Success", $article);
    }
    public function update(Request $request, Article $article): JsonResponse
    {
        $article->update($request->all());
        return $this->ok("Success", $article);
    }
    public function destroy(Article $article): JsonResponse
    {
        $article->delete();
        return $this->ok("Success", $article);
    }
}
