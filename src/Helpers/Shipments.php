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
    public function forOrderNumber($orderNumber)
    {
        $orderId = $this->getOrderId($orderNumber);

        return $this->get(['orderId' => $orderId]);
    }
}
