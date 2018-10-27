<?php

namespace App\OE\WoW;



use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Cache\Repository;

class BattlenetAccessTokenProvider
{
    /** @var Guzzle */
    private $client;
    /** @var Repository */
    private $cache;

    public function __construct(Client $client, Repository $cache)
    {
        $this->client = $client;
        $this->cache = $cache;
    }

    public function get()
    {
        if ($this->cache->has('access-token')) return $this->cache->get('access-token');

        $clientId = config('operation-eskimo.battle-net-client-id');
        $clientSecret = config('operation-eskimo.battle-net-client-secret');

        $response = $this->client->post('https://eu.battle.net/oauth/token', [
            'auth' => [
                $clientId,
                $clientSecret
            ],
            'form_params' => ['grant_type' => 'client_credentials']
        ]);

        if ($response->getStatusCode() != 200) {
            throw new \Exception("Unable to get access token");
        }

        $response = json_decode($response->getBody()->getContents());

        $this->cache->put('access-token', $response->access_token, Carbon::now()->addSeconds($response->expires_in - 100));

        return $response->access_token;
    }
}