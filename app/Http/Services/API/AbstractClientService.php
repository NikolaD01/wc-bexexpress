<?php

namespace WC_BE\Http\Services\API;

use WC_BE\Dependencies\GuzzleHttp\Client;
use WC_BE\Dependencies\GuzzleHttp\Exception\GuzzleException;

class AbstractClientService
{
    protected string $baseUri;
    protected Client $client;

    public function __construct($baseUri)
    {
        $this->setBaseUri($baseUri);
        $this->setClient();
    }

    protected function setBaseUri(string $baseUri) : void
    {
        $this->baseUri = $baseUri;
    }
    protected function setClient(): void
    {
        $this->client = new Client([
            'base_uri' => $this->baseUri,
            'timeout' => 10.0,
        ]);
    }


    /**
     * @throws GuzzleException
     */
    public function get(string $endpoint, array $options = []) : mixed
    {
        return $this->client->get($endpoint, [
            'timeout' => 10.0,
        ]);
    }

    /**
     * @throws GuzzleException
     */
    public function post(string $endpoint, array $data, array $options = []) : mixed
    {

        return $this->client->post($endpoint, array_merge($options, ['json' => $data]));
    }
}