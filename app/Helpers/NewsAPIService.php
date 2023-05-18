<?php

namespace App\Helpers;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;
use GuzzleHttp\Exception\GuzzleException;

class NewsAPIService
{
    public $http_client;
    public $http_results;

    public $http_promises;

    public function __construct(){

        $this->http_client = new Client(['exceptions' => false]);
        $this->http_results = [];
        $this->http_promises = [];
    }

    public function getResults(): array
    {
        $responses = Utils::settle($this->http_promises)->wait(true);

        $this->http_results = [];

        foreach($responses as $tag => $response){

            if($response['state'] == 'fulfilled'){

                $this->http_results[] = [ 'data' => json_decode($response['value']->getBody(), true), 'tag' => $tag ];
            }
        }

        return $this->http_results;
    }

    /**
     * @throws GuzzleException
     */
    public function callAPI($uri, $tag, $data = [] ): void
    {

        $this->http_promises[$tag] = $this->http_client->getAsync($uri, $data);
    }
}
