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

    /**
     * @throws GuzzleException
     */
    public function getLabel(int $shipmentId, int $parcelNo = 1): mixed
    {
        $endpoint = '/shipDNF/ship/getLabel';

        $queryParams = [
            'shipmentId' => $shipmentId,
            'parcelNo' => $parcelNo,
        ];

        $queryString = http_build_query($queryParams);
        $url = $endpoint . '?' . $queryString;

        $options = [
            'headers' => [
                'X-AUTH-TOKEN' => $this->authToken,
                'Content-Type' => 'application/json',
            ],
        ];

        return $this->get($url, $options);
    }

}