<?php

namespace App\Http\Controllers;

use App\Helpers;
use App\Helpers\ArticleMapper;
use App\Helpers\NewsAPIService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NewsController extends Controller
{

    /**
     * @throws GuzzleException
     */
    function getNewsFeed(Request $request): JsonResponse
    {

        $user = Auth::user();
        $newsSources = Helpers::getNewsSourcesForUser($user->id);
        $http_client = new NewsAPIService();
        $articleFormatter = new ArticleMapper();

        foreach ($newsSources as $source){

            $data = [
                'query' => [
                    'q' => $request->query('search'),
                ],
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ];

            switch($source->source_tag){

                case 'NEWS_ORG':
                    $data['headers']['X-Api-Key'] = env('NEWS_ORG_API_KEY');
                    $APIUrl = env('NEWS_ORG_API_URL');
                    $tag = 'NEWS_ORG';
                    break;

                case 'NY_TIMES':
                    $data['query']['api-key'] = env('NY_TIMES_API_KEY');
                    $APIUrl = env('NY_TIMES_API_URL');
                    $tag = 'NY_TIMES';
                    break;

                case 'THE_GUARDIAN':
                default:
                    $data['query']['api-key'] = env('THE_GUARDIAN_API_KEY');
                    $APIUrl = env('THE_GUARDIAN_API_URL');
                    $tag = 'THE_GUARDIAN';
                    break;
            }

            $http_client->callAPI($APIUrl, $tag, $data);
        }

        foreach($http_client->getResults() as $result){

            $tag = $result['tag'];

            switch($tag){

                case 'NEWS_ORG':
                    $articleFormatter->formatArticleGeneral($result['data']['articles'], $tag);
                    break;

                case 'NY_TIMES':
                    $articleFormatter->formatArticleGeneral($result['data']['response']['docs'], $tag);
                    break;

                case 'THE_GUARDIAN':
                default:
                    $articleFormatter->formatArticleGeneral($result['data']['response']['results'], $tag);
                    break;
            }
        }

        return response()->json( $articleFormatter->getFormattedArticles() );
    }
}
