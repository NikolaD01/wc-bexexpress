<?php

namespace WC_BE\Http\Services\API;

use WC_BE\Dependencies\GuzzleHttp\Exception\GuzzleException;

class BexExpressClientService extends AbstractClientService
{
    protected string $authToken;

    public function __construct(string $baseUri, string $authToken)
    {
        parent::__construct($baseUri);
        $this->authToken = $authToken;

    }

    /**
     * Create a new shipment
     *
     * @param array $shipmentData
     * @return mixed
     * @throws GuzzleException
     * @throws GuzzleException
     */
    public function createShipment(array $shipmentData): mixed
    {
        $endpoint = '/ship/api/Ship/postShipments';
        $options = [
            'headers' => [
                'X-AUTH-TOKEN' => $this->authToken,
                'Content-Type' => 'application/json',
            ],
        ];

        return $this->post($endpoint, $shipmentData, $options);
    }
}