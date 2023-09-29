<?php

namespace App\Http\Controllers\Api;

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
            $articleRecommendation->create([
                'article_top' => $request->article_top,
                'article_recommendation' => $request->article
            ]);
        }
        $articleRecommendation->update([
            'article_top' => $request->article_top,
            'article_recommendation' => $request->article
        ]);

        return $this->ok("succes", true);
    }
}
