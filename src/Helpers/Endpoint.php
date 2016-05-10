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
     * @return array
     */
    public function get($options = [], $endpoint = '')
    {
        return $this->api->get($options, $endpoint);
    }

    /**
     * Post to a resource using the assigned endpoint ($this->api->endpoint).
     *
     * @param  array  $options
     * @param  string  $endpoint
     * @return array
     */
    public function post($options = [], $endpoint = '')
    {
        return $this->api->post($options, $endpoint);
    }

    /**
     * Delete a resource using the assigned endpoint ($this->api->endpoint).
     *
     * @param  string  $endpoint
     * @return array
     */
    public function delete($endpoint = '')
    {
        return $this->api->delete($endpoint);
    }

    /**
     * Update a resource using the assigned endpoint ($this->api->endpoint).
     *
     * @param  array  $options
     * @param  string  $endpoint
     * @return array
     */
    public function update($options = [], $endpoint = '')
    {
        return $this->api->update($options, $endpoint);
    }
}
