<?php

namespace App\Http\Controllers;

use App\Services\NewsService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class NewsController extends Controller
{
    private NewsService $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function getNews(Request $request)
    {
        try {
            if (array_key_exists('HTTP_AUTHORIZATION', $_SERVER)) {
                try {
                    $user = JWTAuth::parseToken()->authenticate();
                    return $this->newsService->getRecommendedNews($user, $request);
                } catch (\Exception $e) {
                    return $this->newsService->getDefaultNews($request);
                }
            }
            return $this->newsService->getDefaultNews($request);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], $exception->getCode());
        }
    }

    function getSources()
    {
        return $this->newsService->getSources();
    }

}