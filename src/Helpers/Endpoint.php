<?php
namespace LaravelShipStation\Helpers;

use LaravelShipStation\ShipStation;

abstract class Endpoint
{
    private $api;

    /**
     * Endpoint constructor.
     *
     * @param ShipStation $api
     */
    public function __construct(ShipStation $api)
    {
        $this->api = $api;
    }

    /**
     * Get a resource using the assigned endpoint ($this->api->endpoint).
     *
     * @param  array  $options
     * @param  string  $endpoint
     * @return \stdClass
     */
    public function get($options = [], $endpoint = '')
    {
        $response = $this->api->get($endpoint, (array) $options);

        return json_decode($response->getBody()->getContents());
    }

    /**
     * Post to a resource using the assigned endpoint ($this->api->endpoint).
     *
     * @param  array  $options
     * @param  string  $endpoint
     * @return \stdClass
     */
    public function post($options = [], $endpoint = '')
    {
        $response = $this->api->post($endpoint, (array) $options);

        return json_decode($response->getBody()->getContents());
    }

    /**
     * Delete a resource using the assigned endpoint ($this->api->endpoint).
     *
     * @param  string  $endpoint
     * @return \stdClass
     */
    public function delete($endpoint = '')
    {
        $response = $this->api->delete($endpoint);

        return json_decode($response->getBody()->getContents());
    }

    /**
     * Update a resource using the assigned endpoint ($this->api->endpoint).
     *
     * @param  array  $options
     * @param  string  $endpoint
     * @return \stdClass
     */
    public function update($options = [], $endpoint = '')
    {
        $response = $this->api->put($endpoint, (array) $options);

        return json_decode($response->getBody()->getContents());
    }


    /**
     * Get the order the orderId from an orderNumber.
     *
     * @param  mixed  $orderNumber
     * @return int|null
     */
    public function getOrderId($orderNumber)
    {
        $pages = $this->getTotalPages($orderNumber);

        foreach (range(1, $pages) as $i) {
            $response = $this->api->request('GET', "/orders/", [
                'query' => [
                    'orderNumber' => $orderNumber,
                    'page' => $i
                ]
            ]);

            $this->api->sleepIfRateLimited($response);

            $data = json_decode($response->getBody()->getContents());

            $orders = isset($data->orders) ? $data->orders : [];

            foreach ($orders as $order) {
                if ($order->orderNumber === $orderNumber) {
                    return $order->orderId;
                }
            }
        }

        return null;
    }

    /**
     * Get the total number of pages possible to find the inputted order number.
     *
     * @param  mixed  $orderNumber
     * @return int
     */
    private function getTotalPages($orderNumber)
    {
        $response = $this->api->request('GET', "/orders/", [
            'query' => ['orderNumber' => $orderNumber]
        ]);

        $data = json_decode($response->getBody()->getContents());

        return isset($data->pages) ? $data->pages : 0;
    }
}
