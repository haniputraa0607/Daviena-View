<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Controller;
use App\Models\ArticleRecommendation;
use App\Models\OfficialPartnerHome;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiArticleRecommendationController extends Controller
{
    public function index(ArticleRecommendation $article_recommendation): JsonResponse
    {
        return $this->ok('success', $article_recommendation->first());
    }

    public function update(Request $request): JsonResponse
    {
        $articleRecommendation = ArticleRecommendation::find(1);
    
        if (!$articleRecommendation) {
            $data_create = [
                'article_top' => $request->input('article_top', ''),
                'article_recommendation' => json_encode($request->input('article', []))
            ];
            ArticleRecommendation::create($data_create);
        } else {
            $articleRecommendation->update([
                'article_top' => $request->input('article_top', ''),
                'article_recommendation' => json_encode($request->input('article', []))
            ]);
        }
    
        return $this->ok("success", true);
    }
}
