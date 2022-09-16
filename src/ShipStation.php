<?php
namespace LaravelShipStation;


use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;

class ShipStation
{
    /**
     * @var string The current endpoint for the API. The default endpoint is /orders/
     */
    public $endpoint = '/orders/';

    /**
     * @var \Illuminate\Support\Facades\Http Overriden client used for calling the API
     */
    public $client = null;

    /**
     * @var array Our list of valid ShipStation endpoints.
     */
    private $endpoints = [
        '/accounts/',
        '/carriers/',
        '/customers/',
        '/fulfillments/',
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

    /** @var int */
    private $maxAllowedRequests = 0;

    /** @var int|null */
    private $remainingRequests = null;

    /** @var int */
    private $secondsUntilReset = 0;

    /**
     * ShipStation constructor.
     *
     * @param  string  $apiKey
     * @param  string  $apiSecret
     * @param  string  $apiURL
     * @param  string|null  $partnerApiKey
     * @throws \Exception
     */
    public function __construct($apiKey, $apiSecret, $apiURL, $partnerApiKey = null)
    {
        if (!isset($apiKey, $apiSecret)) {
            throw new \Exception('Your API key and/or private key are not set. Did you run artisan vendor:publish?');
        }

        $this->base_uri = $apiURL;

        $app = new Container();
        $app->singleton('app', 'Illuminate\Container\Container');

        Facade::setFacadeApplication($app);

        $client = Http::baseUrl($this->base_uri)
            ->withBasicAuth($apiKey, $apiSecret);

        if (! empty($partnerApiKey)) {
            $client = $client->withHeaders([
                'x-partner' => $partnerApiKey
            ]);
        }

        $this->client = $client;
    }

    /**
     * Get a resource using the assigned endpoint ($this->endpoint).
     *
     * @param  array  $options
     * @param  string  $endpoint
     * @return \stdClass
     */
    public function get($options = [], $endpoint = '')
    {
        $response = $this->client->get("{$this->endpoint}{$endpoint}", $options);

        $this->sleepIfRateLimited($response);

        return $response->object();
    }

    /**
     * Post to a resource using the assigned endpoint ($this->endpoint).
     *
     * @param  array  $options
     * @param  string  $endpoint
     * @return \stdClass
     */
    public function post($options = [], $endpoint = '')
    {
        $response = $this->client->post("{$this->endpoint}{$endpoint}", (array) $options);

        $this->sleepIfRateLimited($response);

        return $response->object();
    }

    /**
     * Delete a resource using the assigned endpoint ($this->endpoint).
     *
     * @param  string  $endpoint
     * @return \stdClass
     */
    public function delete($endpoint = '')
    {
        $response = $this->client->delete("{$this->endpoint}{$endpoint}");

        $this->sleepIfRateLimited($response);

        return $response->object();
    }

    /**
     * Update a resource using the assigned endpoint ($this->endpoint).
     *
     * @param  array  $options
     * @param  string  $endpoint
     * @return \stdClass
     */
    public function update($options = [], $endpoint = '')
    {
        $response = $this->client->put("{$this->endpoint}{$endpoint}", (array) $options);
        $this->sleepIfRateLimited($response);

        return $response->object();
    }

    /**
     * Get the maximum number of requests that can be sent per window
     *
     * @return int
     */
    public function getMaxAllowedRequests()
    {
        return $this->maxAllowedRequests;
    }

    /**
     * Get the remaining number of requests that can be sent in the current window
     *
     * @return int
     */
    public function getRemainingRequests()
    {
        return $this->remainingRequests;
    }

    /**
     * Get the number of seconds remaining until the next window begins
     *
     * @return int
     */
    public function getSecondsUntilReset()
    {
        return $this->secondsUntilReset;
    }

    /**
     * Are we currently rate limited?
     * We are if there are no more requests allowed in the current window
     *
     * @return bool
     */
    public function isRateLimited()
    {
        return $this->remainingRequests !== null && ! $this->remainingRequests;
    }

    /**
     * Check to see if we are about to rate limit and pause if necessary.
     *
     * @param Response $response
     */
    public function sleepIfRateLimited(Response $response)
    {
        $this->maxAllowedRequests = (int) $response->header('X-Rate-Limit-Limit');
        $this->remainingRequests = (int) $response->header('X-Rate-Limit-Remaining');
        $this->secondsUntilReset = (int) $response->header('X-Rate-Limit-Reset');

        if ($this->isRateLimited() || ($this->secondsUntilReset / $this->remainingRequests) > 1.5) {
            sleep(1.5);
        }
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
