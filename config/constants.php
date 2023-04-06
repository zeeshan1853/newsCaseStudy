<?php
$newsApiBaseUrl = 'https://newsapi.org/v2/';
$newsApiTopHeadlines = $newsApiBaseUrl . 'top-headlines/';
// $newsCredBaseUrl = 'https://api.newscred.com/';

return [
    'newsApi' => [
        'secret' => env('NEWS_API_SECRET'),
        'baseUrl' => $newsApiBaseUrl,
        'topHeadLines' => $newsApiTopHeadlines,
        'everything' => $newsApiBaseUrl . 'everything',
        'sources' => $newsApiTopHeadlines . 'sources',
    ]
];