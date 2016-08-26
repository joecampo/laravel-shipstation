<?php
namespace LaravelShipStation;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class ShipStation extends Client
{
    /**
     * @var string The current endpoint for the API. The default endpoint is /orders/
     */
    public $endpoint = '/orders/';

    /**
     * @var array Our list of valid ShipStation endpoints.
     */
    private $endpoints = [
        '/accounts/',
        '/carriers/',
        '/customers/',
        '/orders/',
        '/products/',
        '/shipments/',
        '/stores/',
        '/users/',
        '/warehouses/',
        '/webhooks/'
    ];

    /**
     * @var string Base API URL for ShipStation
     */
    private $base_uri = 'https://ssapi.shipstation.com';

    /**
     * ShipStation constructor.
     *
     * @param  string  $apiKey
     * @param  string  $apiSecret
     * @throws \Exception
     */
    public function __construct($apiKey, $apiSecret)
    {
        if (!isset($apiKey, $apiSecret)) {
            throw new \Exception('Your API key and/or private key are not set. Did you run artisan vendor:publish?');
        }

        parent::__construct([
            'base_uri' => $this->base_uri,
            'headers'  => [
                'Authorization' => 'Basic ' . base64_encode("{$apiKey}:{$apiSecret}"),
            ]
        ]);
    }

    /**
     * Get a resource using the assigned endpoint ($this->endpoint).
     *
     * @param  array  $options
     * @param  string  $endpoint
     * @return array
     */
    public function get($options = [], $endpoint = '')
    {
        $response = $this->request('GET', "{$this->endpoint}{$endpoint}", [
            'query' => $options
        ]);

        $this->sleepIfRateLimited($response);

        return json_decode($response->getBody()->getContents());
    }

    /**
     * Post to a resource using the assigned endpoint ($this->endpoint).
     *
     * @param  array  $options
     * @param  string  $endpoint
     * @return array
     */
    public function post($options = [], $endpoint = '')
    {
        if ($options instanceof \stdClass) {
            $options = $this->toArray($options);
        }

        $response = $this->request('POST', "{$this->endpoint}{$endpoint}", [
            'form_params' => $options
        ]);

        $this->sleepIfRateLimited($response);

        return json_decode($response->getBody()->getContents());
    }

    /**
     * Delete a resource using the assigned endpoint ($this->endpoint).
     *
     * @param  string  $endpoint
     * @return array
     */
    public function delete($endpoint = '')
    {
        $response = $this->request('DELETE', "{$this->endpoint}{$endpoint}");

        $this->sleepIfRateLimited($response);

        return json_decode($response->getBody()->getContents());
    }

    /**
     * Update a resource using the assigned endpoint ($this->endpoint).
     *
     * @param  array  $options
     * @param  string  $endpoint
     * @return array
     */
    public function update($options = [], $endpoint = '')
    {
        if ($options instanceof \stdClass) {
            $options = $this->toArray($options);
        }

        $response = $this->request('PUT', "{$this->endpoint}{$endpoint}", [
            'form_params' => $options
        ]);

        $this->sleepIfRateLimited($response);

        return json_decode($response->getBody()->getContents());
    }

    /**
     * Check to see if we are about to rate limit and pause if necessary.
     *
     * @param Response $response
     */
    public function sleepIfRateLimited(Response $response)
    {
        $rateLimit = $response->getHeader('X-Rate-Limit-Remaining')[0];
        $rateLimitWait = $response->getHeader('X-Rate-Limit-Reset')[0];

        if (($rateLimitWait / $rateLimit) > 1.5) {
            sleep(1.5);
        }
    }

    /**
     * Convert an object to an array.
     *
     * @param  \stdClass $object
     * @return array
     */
    private function toArray($object)
    {
        $vars = get_object_vars($object);

        foreach ($vars as $key => $value) {
            if (is_object($value)) {
                $vars[$key] = $this->toArray($value);
            }
        }

        return $vars;
    }

    /**
     * Set our endpoint by accessing it via a property.
     *
     * @param  string $property
     * @return $this
     */
    public function __get($property)
    {
        if (in_array('/' . $property . '/', $this->endpoints)) {
            $this->endpoint = '/' . $property . '/';
        }

        $className = "LaravelShipStation\\Helpers\\" . ucfirst($property);

        if (class_exists($className)) {
            return new $className($this);
        }

        return $this;
    }
}
