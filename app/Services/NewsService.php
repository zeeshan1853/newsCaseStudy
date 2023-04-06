<?php
namespace App\Services;

use App\Models\User;
use Config;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class NewsService
{

    public function getRecommendedNews(User $user, Request $request)
    {
        $query = $this->getQuery($request);
        $preference = $user->preferences;
        $sources = $preference->sources;
        $categories = $preference->categories;
        $authors = $preference->authors;
        if (empty($query['sources']) && !empty($sources)) {
            $query['sources'] = $sources;
        }
        if (empty($query['category']) && !empty($categories)) {
            $query['category'] = $categories;
        }

        return $this->runQuery($query);
    }

    public function getDefaultNews(Request $request)
    {
        $query = $this->getQuery($request);
        return $this->runQuery($query);
    }

    public function getQuery(Request $request)
    {
        $search = $request->input('search');
        $date = $request->input('date');
        $category = $request->input('category');
        $source = $request->input('source');

        $query = [
            'q' => $search,
            'sortBy' => 'popularity',
            'apiKey' => Config::get('constants.newsApi.secret'),
            'pageSize' => 10
        ];

        if (empty($query['q']) || $query['q'] == "undefined") {
            $query['q'] = 'news';
        }
        if ($category && $category != "undefined") {
            $query['category'] = $category;
        }
        if ($source && $source != 'undefined') {
            $query['sources'] = $source;
        }
        if ($date && $date != 'undefined') {
            $query['from'] = $date;
        } else {
            $query['from'] = date('Y-m-d');
        }
        return $query;
    }

    public function runQuery($query)
    {
        $url = Config::get('constants.newsApi.everything');

        if (!empty($query['category'])) {
            $url = Config::get('constants.newsApi.topHeadLines');
        }

        $client = new Client();
        $response = $client->request('GET', $url, [
            'query' => $query
        ]);
        $articles = json_decode($response->getBody());
        return $articles;
    }

    public function getHeadlines()
    {
        return [];
    }

    public function getSources()
    {
        try {
            $client = new Client();
            $response = $client->request('GET', Config::get('constants.newsApi.sources'), [
                'query' => [
                    'sortBy' => 'popularity',
                    'country' => 'us',
                    'apiKey' => Config::get('constants.newsApi.secret'),
                ]
            ]);
            return json_decode($response->getBody());
        } catch (Exception $e) {
            \Log::error($e);
        }
        return null;
    }

}