<?php
namespace LaravelShipStation;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

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

        $headers = [
            'Authorization' => 'Basic ' . base64_encode("{$apiKey}:{$apiSecret}"),
        ];

        if (! empty($partnerApiKey)) {
            $headers['x-partner'] = $partnerApiKey;
        }

        parent::__construct([
            'base_uri' => $this->base_uri,
            'headers'  => $headers,
        ]);
    }

    /**
     * Create and send an HTTP GET request.
     *
     * Use an absolute path to override the base path of the client, or a
     * relative path to append to the base path of the client. The URL can
     * contain the query string as well.
     *
     * @param string|UriInterface $uri     URI object or string.
     * @param array               $options Request options to apply.
     *
     * @throws GuzzleException
     */
    public function get($endpoint, array $options = []): ResponseInterface
    {
        $response = $this->request('GET', "{$this->endpoint}{$endpoint}", ['query' => $options]);

        $this->sleepIfRateLimited($response);

        return $response;
    }

    /**
     * Create and send an HTTP POST request.
     *
     * Use an absolute path to override the base path of the client, or a
     * relative path to append to the base path of the client. The URL can
     * contain the query string as well.
     *
     * @param string|UriInterface $uri     URI object or string.
     * @param array               $options Request options to apply.
     *
     * @throws GuzzleException
     */
    public function post($endpoint, array $options = []): ResponseInterface
    {
        $response = $this->request('POST', "{$this->endpoint}{$endpoint}", ['json' => $options]);

        $this->sleepIfRateLimited($response);

        return $response;
    }

    /**
     * Create and send an HTTP DELETE request.
     *
     * Use an absolute path to override the base path of the client, or a
     * relative path to append to the base path of the client. The URL can
     * contain the query string as well.
     *
     * @param string|UriInterface $uri     URI object or string.
     * @param array               $options Request options to apply.
     *
     * @throws GuzzleException
     */
    public function delete($endpoint, array $options = []): ResponseInterface
    {
        $response = $this->request('DELETE', "{$this->endpoint}{$endpoint}");

        $this->sleepIfRateLimited($response);

        return $response;
    }

    /**
     * Create and send an HTTP PUT request.
     *
     * Use an absolute path to override the base path of the client, or a
     * relative path to append to the base path of the client. The URL can
     * contain the query string as well.
     *
     * @param string|UriInterface $uri     URI object or string.
     * @param array               $options Request options to apply.
     *
     * @throws GuzzleException
     */
    public function put($endpoint, array $options = []): ResponseInterface
    {
        $response = $this->request('PUT', "{$this->endpoint}{$endpoint}", ['json' => $options]);

        $this->sleepIfRateLimited($response);

        return $response;
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
        $this->maxAllowedRequests = (int) $response->getHeader('X-Rate-Limit-Limit')[0];
        $this->remainingRequests = (int) $response->getHeader('X-Rate-Limit-Remaining')[0];
        $this->secondsUntilReset = (int) $response->getHeader('X-Rate-Limit-Reset')[0];

        if (($this->secondsUntilReset / $this->remainingRequests) > 1.5 || $this->isRateLimited()) {
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
