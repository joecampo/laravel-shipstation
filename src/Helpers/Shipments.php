<?php
namespace LaravelShipStation\Helpers;

class Shipments extends Endpoint
{
    /**
     * Get shipments for a specific order number.
     *
     * @param  int  $orderNumber
     * @return array
     */
    public function forOrder($orderNumber)
    {
        return $this->get(['orderNumber' => $orderNumber]);
    }
}
