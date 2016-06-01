<?php
namespace LaravelShipStation\Helpers;

class Orders extends Endpoint
{
    /**
     * Create a single order in ShipStation.
     *
     * @param  array  $order
     * @return array
     */
    public function create($order)
    {
        return $this->post($order, 'createorder');
    }

    /**
     * Does the specified order exist by the given order number?
     *
     * @param  mixed  $orderNumber
     * @return bool
     */
    public function exists($orderNumber)
    {
        $orders = $this->get([
            'orderNumber' => $orderNumber
        ]);

        foreach ($orders->orders as $order) {
            if ($order->orderNumber == $orderNumber) {
                return true;
            }
        }

        return false;
    }

    /**
     * How many orders are awaiting shipment?
     *
     * @return int
     */
    public function awaitingShipmentCount()
    {
        $count = $this->get([
            'orderStatus' => 'awaiting_shipment'
        ]);
        return isset($count->total) ? $count->total : 0;
    }
}
