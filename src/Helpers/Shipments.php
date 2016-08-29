<?php
namespace LaravelShipStation\Helpers;

class Shipments extends Endpoint
{
    /**
     * Get shipments for a specific orderNumber.
     *
     * @param  int  $orderNumber
     * @return \stdClass
     */
    public function forOrderNumber($orderNumber)
    {
        $orderId = $this->getOrderId($orderNumber);

        if (!$orderId) {
            return $this->emptyShipment();
        }

        return $this->get(['orderId' => $orderId]);
    }

    /**
     *  An empty shipment in ShipStation's format.
     *
     * @return \stdClass
     */
    private function emptyShipment()
    {
        $shipments = new \stdClass();

        $shipments->shipments = [];
        $shipments->total = 0;
        $shipments->page = 1;
        $shipments->pages = 0;

        return $shipments;
    }

}
